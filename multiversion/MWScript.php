<?php
if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
	echo "This script can only be run from the command line or via phpdbg.\n";
	exit( 1 );
}

function usage() {
	global $argv;
	fwrite( STDERR, <<<EOT
Usage: php $argv[0] SCRIPT --wiki=WIKI <script args>

SCRIPT must be the relative (to MediaWiki) script file path.
If only a filename is given, it will be assumed to reside in /maintenance.

The --wiki option is required for most maintenance scripts.

EOT
	);
	exit( 1 );
}

/**
 * Run a MediaWiki script based on the parameters (like --wiki) given to CLI.
 *
 * The first argument must be the relative (to MediaWiki) script file path.
 * If only a filename is given, it will be assumed to reside in /maintenance.
 * The second argument must be the --wiki parameter. This is to avoid
 * any "options with args" ambiguity (see Maintenance.php).
 *
 * When the actual script is run, $argv[0] (this file's name) will be not be kept.
 * Also, $argv[1] (the script path) will be changed to the script file name.
 * All other arguments will be preserved.
 *
 * @return string Absolute MediaWiki script path
 */
function getMWScriptWithArgs() {
	global $argv;
	if ( count( $argv ) < 2 ) {
		usage();
	}

	# Security check -- don't allow scripts to run as privileged users
	$gids = posix_getgroups();
	foreach ( $gids as $gid ) {
		$info = posix_getgrgid( $gid );
		if ( $info && in_array( $info['name'], [ 'sudo', 'wikidev', 'root' ] ) ) {
			fwrite( STDERR, "Cannot run a MediaWiki script as a user in the " .
				"group {$info['name']}\n" );
			fwrite( STDERR, <<<EOT
Maintenance scripts should generally be run using sudo -u www-data which
is available to all wikidev users.  Running a maintenance script as a
privileged user risks compromise of the user account.

You should run this script as the www-data user:

 sudo -u www-data <command>

EOT
			);
			exit( 1 );
		}
	}

	$relFile = $argv[1]; // the script file to run
	# If no MW directory is given then assume this is a /maintenance script
	if ( strpos( $relFile, '/' ) === false ) {
		$relFile = "maintenance/$relFile"; // convenience
	} elseif ( getenv( 'MEDIAWIKI_MAINT_INIT_ONLY' ) ) {
		$relFile = 'maintenance/commandLine.inc';
	}

	# Remove effects of this wrapper from $argv...
	array_shift( $argv ); // remove this file's name from args
	# Code stolen from wfBasename() in GlobalFunctions.php :)
	if ( preg_match( "#([^/\\\\]*?)[/\\\\]*$#", $argv[0], $matches ) ) {
		$argv[0] = $matches[1]; // make first arg the script file name
	}

	# For addwiki.php, the wiki DB doesn't yet exist, and for some
	# other maintenance scripts we don't care what wiki DB is used...
	$wikiless = [
		'maintenance/purgeList.php',
		'maintenance/purgeMessageBlobStore.php',
		'extensions/WikimediaMaintenance/addWiki.php',
		'extensions/WikimediaMaintenance/dumpInterwiki.php',
		'extensions/WikimediaMaintenance/getJobQueueLengths.php',
		'extensions/WikimediaMaintenance/rebuildInterwiki.php',
		'extensions/WikimediaMaintenance/filebackend/setZoneAccess.php',
		'extensions/WikimediaMaintenance/purgeUrls.php',
		'extensions/WikimediaMaintenance/refreshMessageBlobs.php',
		'maintenance/mctest.php',
		'maintenance/mcc.php',
	];

	# Check if a --wiki param was given...
	# Maintenance.php will treat $argv[1] as the wiki if it doesn't start '-'
	if ( !isset( $argv[1] ) || !preg_match( '/^([^-]|--wiki(=|$))/', $argv[1] ) ) {
		if ( in_array( $relFile, $wikiless ) ) {
			# Assume aawiki as Maintenance.php does.
			$argv = array_merge( [ $argv[0], "--wiki=aawiki" ], array_slice( $argv, 1 ) );
		}
	}

	# MWScript.php should be in common/
	require_once __DIR__ . '/MWMultiVersion.php';
	$file = MWMultiVersion::getMediaWikiCli( $relFile );
	if ( !file_exists( $file ) ) {
		fwrite( STDERR, "The MediaWiki script file \"{$file}\" does not exist.\n" );
		exit( 1 );
	}

	return $file;
}

# Run the script!
require_once getMWScriptWithArgs();
