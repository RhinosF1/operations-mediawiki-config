<?php
/**
 * Configuration for [[:meta:Special:Contact/affcomusergroup]]
 *
 * @see T95789
 */

$wgHooks['ContactForm'][] = static function (
	&$to, $reply, &$subject, &$text, $par, $data
) {
	if ( $par === 'affcomusergroup' ) {
		$subject = "User group request: {$data['GroupName']}";
	}
	return true;
};

$wgContactConfig['affcomusergroup'] = [
	'RecipientUser' => 'Usergroups',
	'SenderName' => 'User group contact form on ' . $wgSitename,
	'SenderEmail' => null,
	'RequireDetails' => true,
	'MustBeLoggedIn' => true,
	'IncludeIP' => false,
	'RLStyleModules' => [
		'ext.wikimediamessages.contactpage.affcomusergroup',
	],
	'AdditionalFields' => [
		'GroupName' => [
			'label-message' => 'contactpage-affcom-user-group-name-label',
			'contactpage-email-label' => 'Group name',
			'type' => 'text'
		],
		'GroupDescription' => [
			'label-message' => 'contactpage-affcom-user-group-description-label',
			'contactpage-email-label' => 'Group description',
			'type' => 'textarea',
			'rows' => 10

		],
		'GroupWikiPage' => [
			'label-message' => 'contactpage-affcom-user-group-wikipage-label',
			'contactpage-email-label' => 'Group wiki page',
			'type' => 'text'
		],
		'GroupLocation' => [
			'label-message' => 'contactpage-affcom-user-group-location-label',
			'contactpage-email-label' => 'Group location',
			'type' => 'text'
		],
		'GroupLeaders' => [
			'label-message' => 'contactpage-affcom-user-group-leaders-label',
			'contactpage-email-label' => 'Active Wikimedians',
			'type' => 'textarea',
			'rows' => 10
		],
		'GroupLogo' => [
			'label-message' => 'contactpage-affcom-user-group-logo-label',
			'contactpage-email-label' => 'Logo',
			'type' => 'radio',
			'options-messages' => [
				'contactpage-affcom-user-group-logo-community' => 'Wikimedia community',
				'contactpage-affcom-user-group-logo-affiliate' => 'Wikipedia affiliate'
			]
		],
		'Rules' => [
			'label-message' => 'contactpage-affcom-user-group-rules-label',
			'type' => 'info',
		],
		'Terms' => [
			'label-message' => 'contactpage-affcom-user-group-terms-label',
			'contactpage-email-label' => 'Terms',
			'type' => 'check',
			'required' => true,
			'validation-callback' => static function ( $value ) {
				return (bool)$value;
			}
		]
	]
];

/**
 * Configuration for legal contact forms (license stuff)
 */
$trademark = [
	'type' => 'multiselect',
	'options-messages' => [
		'contactpage-wikimedia-trademark-globe' => 'wikiglobe',
		'contactpage-wikimedia-trademark-wikiwordmark' => 'wikipediawordmark',
		'contactpage-wikimedia-trademark-w' => 'stylizedw',
		'contactpage-wikimedia-trademark-foundation' => 'foundation',
		'contactpage-wikimedia-trademark-commons' => 'commons',
		'contactpage-wikimedia-trademark-incubator' => 'incubator',
		'contactpage-wikimedia-trademark-mediawiki' => 'mediawiki',
		'contactpage-wikimedia-trademark-wikiquote' => 'wikiquote',
		'contactpage-wikimedia-trademark-wikibooks' => 'wikibooks',
		'contactpage-wikimedia-trademark-wikimania' => 'wikimania',
		'contactpage-wikimedia-trademark-wikimedia' => 'wikimedia',
		'contactpage-wikimedia-trademark-wikinews' => 'wikinews',
		'contactpage-wikimedia-trademark-wikisource' => 'wikisource',
		'contactpage-wikimedia-trademark-wikispecies' => 'wikispecies',
		'contactpage-wikimedia-trademark-wikiversity' => 'wikiversity',
		'contactpage-wikimedia-trademark-wiktionary' => 'wiktionary',
		'contactpage-wikimedia-trademark-wikivoyage' => 'wikivoyage',
	],
	'required' => true,
];

$wgContactConfig['requestlicense'] = [
	'RecipientUser' => 'Trademarks (WMF)',
	'SenderEmail' => $wmgNotificationSender, // TODO: Replace with details submitted on form
	'SenderName' => 'Contact Page',
	'RequireDetails' => true,
	'IncludeIP' => false,
	'AdditionalFields' => [
		'Username' => [
			'type' => 'text',
			'required' => true,
			'label-message' => 'contactpage-license-request-username',
		],
		'Site' => [
			'type' => 'text',
			'label-message' => 'contactpage-license-request-relevantsite',
		],
		'Group' => [
			'type' => 'text',
			'label-message' => 'contactpage-license-request-group',
		],
		'Title' => [
			'type' => 'text',
			'label-message' => 'contactpage-license-request-title',
		],
		'Org' => [
			'type' => 'text',
			'label-message' => 'contactpage-license-request-organization',
		],
		'OrgType' => [
			'type' => 'text',
			'label-message' => 'contactpage-license-request-organization-type',
		],
		'ProposedUse' => [
			'type' => 'selectorother',
			'label-message' => 'contactpage-license-request-use-proposed',
			'options-messages' => [
				'contactpage-license-request-use-online' => 'online',
				'contactpage-license-request-use-book' => 'book',
				'contactpage-license-request-use-print' => 'print',
				'contactpage-license-request-use-tv' => 'tv',
			],
			'default' => 'online',
			'required' => true,
		],

		'Description' => [
			'label-message' => 'contactpage-license-request-description',
			'type' => 'textarea',
			'rows' => 10,
			'required' => true,
		],

		'Trademark' => [
			'label-message' => 'contactpage-license-request-selectmark',
		] + $trademark,

		'use-note' => [
			'type' => 'info',
			'help-messages' => [ 'contactpage-license-request-use-note' ],
		],
	]
];

unset( $trademark );

/**
 * Configuration for signup form for [[:meta:Movement_communications_group]]
 *
 * @see T218363
 */

$wgContactConfig['movecomsignup'] = [
	'RecipientUser' => 'MoveCom-WMF',
	'SenderName' => 'Movement communications group signup form on ' . $wgSitename,
	'SenderEmail' => null,
	'RequireDetails' => true,
	'MustBeLoggedIn' => true,
	'IncludeIP' => false,
	'RLStyleModules' => [
		'ext.wikimediamessages.contactpage.affcomusergroup',
	],
	'AdditionalFields' => [
		'Username' => [
			'label-message' => 'contactpage-movecom-signup-username-label',
			'type' => 'text',
			'required' => true,
		],
		'Affiliation' => [
			'type' => 'selectorother',
			'label-message' => 'contactpage-movecom-signup-affiliation-label',
			'options-messages' => [
				'contactpage-movecom-signup-affiliation-affiliates' => 'affiliates',
				'contactpage-movecom-signup-affiliation-foundation' => 'foundation',
				'contactpage-movecom-signup-affiliation-group' => 'group',
				'contactpage-movecom-signup-affiliation-projects' => 'projects'
			],
			'required' => true,
		],
		'Affiliate' => [
			'label-message' => 'contactpage-movecom-signup-affiliate-label',
			'type' => 'text',
		],
		'Display' => [
			'label-message' => 'contactpage-movecom-signup-display-label',
			'type' => 'radio',
			'options-messages' => [
				'contactpage-movecom-signup-display-name' => 'Name',
				'contactpage-movecom-signup-display-username' => 'Username',
				'contactpage-movecom-signup-display-nameusername' => 'NameUsername'
			],
			'required' => true,
		],
		'Terms' => [
			'label-message' => 'contactpage-movecom-signup-terms-label',
			'type' => 'check',
			'required' => true,
			'validation-callback' => static function ( $value ) {
				return (bool)$value;
			}
		]
	]
];

/**
 * Configuration for contact form for [[:meta:Ombuds commission]]
 *
 * @see T271828
 */

$wgContactConfig['ombudscommission'] = [
	'RecipientUser' => 'Ombuds commission',
	'SenderEmail' => $wmgNotificationSender,
	'RequireDetails' => true,
	'IncludeIP' => false,
	'AdditionalFields' => [
		'CaseExplanation' => [
			'label-message' => 'contactpage-ombudscommission-case-explanation',
			'type' => 'textarea',
			'rows' => 10,
			'required' => true
		],
		'RelevantLinks' => [
			'label-message' => 'contactpage-ombudscommission-relevant-links',
			'type' => 'textarea',
			'rows' => 10,
			'required' => false
		],
		'ViolationType' => [
			'label-message' => 'contactpage-ombudscommission-violation-type',
			'type' => 'textarea',
			'rows' => 10,
			'required' => true
		],
		'InvolvedUsers' => [
			'label-message' => 'contactpage-ombudscommission-involved-users',
			'type' => 'textarea',
			'rows' => 5,
			'required' => false
		],
		'AffectedAccounts' => [
			'label-message' => 'contactpage-ombudscommission-affected-accounts',
			'type' => 'textarea',
			'rows' => 5,
			'required' => false
		],
		'ProposedSolution' => [
			'label-message' => 'contactpage-ombudscommission-proposed-solution',
			'type' => 'textarea',
			'rows' => 10,
			'required' => false
		],
		'AdditionalInformation' => [
			'label-message' => 'contactpage-ombudscommission-additional-information',
			'type' => 'textarea',
			'rows' => 10,
			'required' => false
		],
		'Disclaimer' => [
			'label-message' => 'contactpage-ombudscommission-disclaimer-label',
			'type' => 'info'
		]
	]
];
