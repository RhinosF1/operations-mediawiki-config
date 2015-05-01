<?php
/**
 * Helpers for DbListTests.
 *
 * @license GPLv2 or later
 * @author Antoine Musso <hashar at free dot fr>
 * @copyright Copyright © 2012, Antoine Musso <hashar at free dot fr>
 * @file
 */
require_once( __DIR__ . '/../multiversion/MWWikiversions.php' );

class DBList {
	# List of project names. This array is used to verify that the various
	# dblist project files only contains names of databases that belong to them
	static $wiki_projects = array(
		'wikibooks',
		'wikinews',
		'wikipedia',
		'wikiquote',
		'wikisource',
		'wikiversity',
		'wiktionary',
	);

	public static function getall() {
		static $list = null;
		if( $list ) {
			return $list;
		}

		$objects = scandir(  dirname( __FILE__ ) . '/..'  );
		foreach( $objects as $filename ) {
			if( substr( $filename, -7, 7 ) == '.dblist' ) {
				$projectname = substr( $filename, 0, -7 );
				# Happilly prefetch the files content
				$list[$projectname] = MWWikiversions::readDbListFile( $filename );
			}
		}

		return $list;
	}

	public static function isWikiProject( $dbname ) {
		return in_array( $dbname, self::$wiki_projects );
	}
}

