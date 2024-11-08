<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

new Events_Maker_Localisation();

/**
 * Events_Maker_Localisation Class.
 */
class Events_Maker_Localisation {
	public $currencies;
	public $countries;

	public function __construct() {
		// set instance
		Events_Maker()->localisation = $this;

		// actions
		add_action( 'after_setup_theme', array( &$this, 'load_defaults' ) );
	}

	/**
	 * Load defaults.
	 */
	public function load_defaults() {
		$this->currencies = apply_filters( 'em_currencies', array(
			'codes'		 => array(
				'AUD'	 => __( 'Australian Dollar', 'events-maker' ),
				'BDT'	 => __( 'Bangladeshi Taka', 'events-maker' ),
				'BRL'	 => __( 'Brazilian Real', 'events-maker' ),
				'BGN'	 => __( 'Bulgarian Lev', 'events-maker' ),
				'CAD'	 => __( 'Canadian Dollar', 'events-maker' ),
				'CLP'	 => __( 'Chilean Peso', 'events-maker' ),
				'CNY'	 => __( 'Chinese Yuan', 'events-maker' ),
				'COP'	 => __( 'Colombian Peso', 'events-maker' ),
				'HRK'	 => __( 'Croatian kuna', 'events-maker' ),
				'CZK'	 => __( 'Czech Koruna', 'events-maker' ),
				'DKK'	 => __( 'Danish Krone', 'events-maker' ),
				'EUR'	 => __( 'Euro', 'events-maker' ),
				'HKD'	 => __( 'Hong Kong Dollar', 'events-maker' ),
				'HUF'	 => __( 'Hungarian Forint', 'events-maker' ),
				'ISK'	 => __( 'Icelandic krona', 'events-maker' ),
				'INR'	 => __( 'Indian Rupee', 'events-maker' ),
				'IDR'	 => __( 'Indonesian Rupiah', 'events-maker' ),
				'ILS'	 => __( 'Israeli Shekel', 'events-maker' ),
				'IRR'	 => __( 'Iranian Rial', 'events-maker' ),
				'JPY'	 => __( 'Japanese Yen', 'events-maker' ),
				'MYR'	 => __( 'Malaysian Ringgit', 'events-maker' ),
				'MXN'	 => __( 'Mexican Peso', 'events-maker' ),
				'NZD'	 => __( 'New Zealand Dollar', 'events-maker' ),
				'NGN'	 => __( 'Nigerian Naira', 'events-maker' ),
				'NOK'	 => __( 'Norwegian Krone', 'events-maker' ),
				'PHP'	 => __( 'Philippine Peso', 'events-maker' ),
				'PLN'	 => __( 'Polish Zloty', 'events-maker' ),
				'GBP'	 => __( 'Pound Sterling', 'events-maker' ),
				'RON'	 => __( 'Romanian Leu', 'events-maker' ),
				'RUB'	 => __( 'Russian Ruble', 'events-maker' ),
				'SGD'	 => __( 'Singapore Dollar', 'events-maker' ),
				'ZAR'	 => __( 'South African Rand', 'events-maker' ),
				'KRW'	 => __( 'South Korean Won', 'events-maker' ),
				'SEK'	 => __( 'Swedish Krona', 'events-maker' ),
				'CHF'	 => __( 'Swiss Franc', 'events-maker' ),
				'TWD'	 => __( 'Taiwan New Dollar', 'events-maker' ),
				'THB'	 => __( 'Thai Baht', 'events-maker' ),
				'TRY'	 => __( 'Turkish Lira', 'events-maker' ),
				'UAH'	 => __( 'Ukrainian Hryvnia', 'events-maker' ),
				'AED'	 => __( 'United Arab Emirates Dirham', 'events-maker' ),
				'USD'	 => __( 'United States Dollar', 'events-maker' ),
				'VND'	 => __( 'Vietnamese Dong', 'events-maker' )
			),
			'symbols'	 => array(
				'AUD'	 => '&#36;',
				'BDT'	 => '&#2547;',
				'BRL'	 => 'R&#36;',
				'BGN'	 => '&#1083;&#1074;',
				'CAD'	 => '&#36;',
				'CLP'	 => '&#36;',
				'CNY'	 => '&#165;',
				'COP'	 => '&#36;',
				'HRK'	 => 'kn',
				'CZK'	 => 'K&#269;',
				'DKK'	 => 'kr',
				'EUR'	 => '&#8364;',
				'HKD'	 => 'HK&#36;',
				'HUF'	 => 'Ft',
				'ISK'	 => 'kr',
				'INR'	 => '&#8377;',
				'IDR'	 => 'Rp',
				'ILS'	 => '&#8362;',
				'IRR'	 => '&#65020;',
				'JPY'	 => '&#165;',
				'MYR'	 => 'RM',
				'MXN'	 => '&#36;',
				'NZD'	 => '&#36;',
				'NGN'	 => '&#8358;',
				'NOK'	 => 'kr',
				'PHP'	 => 'Php',
				'PLN'	 => 'z&#322;',
				'GBP'	 => '&#163;',
				'RON'	 => 'lei',
				'RUB'	 => '&#1088;&#1091;&#1073;',
				'SGD'	 => '&#36;',
				'ZAR'	 => 'R',
				'KRW'	 => '&#8361;',
				'SEK'	 => 'kr',
				'CHF'	 => 'SFr.',
				'TWD'	 => 'NT&#36;',
				'THB'	 => '&#3647;',
				'TRY'	 => '&#8378;',
				'UAH'	 => '&#8372;',
				'AED'	 => 'د.إ',
				'USD'	 => '&#36;',
				'VND'	 => '&#8363;'
			),
			'positions'	 => array(
				'before' => __( 'before the price', 'events-maker' ),
				'after'	 => __( 'after the price', 'events-maker' )
			),
			'formats'	 => array(
				1	 => '1,234.56',
				2	 => '1,234',
				3	 => '1234',
				4	 => '1234.56',
				5	 => '1 234,56',
				6	 => '1 234.56',
				7	 => '1.234,56',
			)
		) );

		$this->countries = apply_filters( 'em_countries', array(
			'AF' => __( 'Afghanistan', 'events-maker' ),
			'AX' => __( '&#197;land Islands', 'events-maker' ),
			'AL' => __( 'Albania', 'events-maker' ),
			'DZ' => __( 'Algeria', 'events-maker' ),
			'AD' => __( 'Andorra', 'events-maker' ),
			'AO' => __( 'Angola', 'events-maker' ),
			'AI' => __( 'Anguilla', 'events-maker' ),
			'AQ' => __( 'Antarctica', 'events-maker' ),
			'AG' => __( 'Antigua and Barbuda', 'events-maker' ),
			'AR' => __( 'Argentina', 'events-maker' ),
			'AM' => __( 'Armenia', 'events-maker' ),
			'AW' => __( 'Aruba', 'events-maker' ),
			'AU' => __( 'Australia', 'events-maker' ),
			'AT' => __( 'Austria', 'events-maker' ),
			'AZ' => __( 'Azerbaijan', 'events-maker' ),
			'BS' => __( 'Bahamas', 'events-maker' ),
			'BH' => __( 'Bahrain', 'events-maker' ),
			'BD' => __( 'Bangladesh', 'events-maker' ),
			'BB' => __( 'Barbados', 'events-maker' ),
			'BY' => __( 'Belarus', 'events-maker' ),
			'BE' => __( 'Belgium', 'events-maker' ),
			'PW' => __( 'Belau', 'events-maker' ),
			'BZ' => __( 'Belize', 'events-maker' ),
			'BJ' => __( 'Benin', 'events-maker' ),
			'BM' => __( 'Bermuda', 'events-maker' ),
			'BT' => __( 'Bhutan', 'events-maker' ),
			'BO' => __( 'Bolivia', 'events-maker' ),
			'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'events-maker' ),
			'BA' => __( 'Bosnia and Herzegovina', 'events-maker' ),
			'BW' => __( 'Botswana', 'events-maker' ),
			'BV' => __( 'Bouvet Island', 'events-maker' ),
			'BR' => __( 'Brazil', 'events-maker' ),
			'IO' => __( 'British Indian Ocean Territory', 'events-maker' ),
			'VG' => __( 'British Virgin Islands', 'events-maker' ),
			'BN' => __( 'Brunei', 'events-maker' ),
			'BG' => __( 'Bulgaria', 'events-maker' ),
			'BF' => __( 'Burkina Faso', 'events-maker' ),
			'BI' => __( 'Burundi', 'events-maker' ),
			'KH' => __( 'Cambodia', 'events-maker' ),
			'CM' => __( 'Cameroon', 'events-maker' ),
			'CA' => __( 'Canada', 'events-maker' ),
			'CV' => __( 'Cape Verde', 'events-maker' ),
			'KY' => __( 'Cayman Islands', 'events-maker' ),
			'CF' => __( 'Central African Republic', 'events-maker' ),
			'TD' => __( 'Chad', 'events-maker' ),
			'CL' => __( 'Chile', 'events-maker' ),
			'CN' => __( 'China', 'events-maker' ),
			'CX' => __( 'Christmas Island', 'events-maker' ),
			'CC' => __( 'Cocos (Keeling) Islands', 'events-maker' ),
			'CO' => __( 'Colombia', 'events-maker' ),
			'KM' => __( 'Comoros', 'events-maker' ),
			'CG' => __( 'Congo (Brazzaville)', 'events-maker' ),
			'CD' => __( 'Congo (Kinshasa)', 'events-maker' ),
			'CK' => __( 'Cook Islands', 'events-maker' ),
			'CR' => __( 'Costa Rica', 'events-maker' ),
			'HR' => __( 'Croatia', 'events-maker' ),
			'CU' => __( 'Cuba', 'events-maker' ),
			'CW' => __( 'Cura&Ccedil;ao', 'events-maker' ),
			'CY' => __( 'Cyprus', 'events-maker' ),
			'CZ' => __( 'Czech Republic', 'events-maker' ),
			'DK' => __( 'Denmark', 'events-maker' ),
			'DJ' => __( 'Djibouti', 'events-maker' ),
			'DM' => __( 'Dominica', 'events-maker' ),
			'DO' => __( 'Dominican Republic', 'events-maker' ),
			'EC' => __( 'Ecuador', 'events-maker' ),
			'EG' => __( 'Egypt', 'events-maker' ),
			'SV' => __( 'El Salvador', 'events-maker' ),
			'GQ' => __( 'Equatorial Guinea', 'events-maker' ),
			'ER' => __( 'Eritrea', 'events-maker' ),
			'EE' => __( 'Estonia', 'events-maker' ),
			'ET' => __( 'Ethiopia', 'events-maker' ),
			'FK' => __( 'Falkland Islands', 'events-maker' ),
			'FO' => __( 'Faroe Islands', 'events-maker' ),
			'FJ' => __( 'Fiji', 'events-maker' ),
			'FI' => __( 'Finland', 'events-maker' ),
			'FR' => __( 'France', 'events-maker' ),
			'GF' => __( 'French Guiana', 'events-maker' ),
			'PF' => __( 'French Polynesia', 'events-maker' ),
			'TF' => __( 'French Southern Territories', 'events-maker' ),
			'GA' => __( 'Gabon', 'events-maker' ),
			'GM' => __( 'Gambia', 'events-maker' ),
			'GE' => __( 'Georgia', 'events-maker' ),
			'DE' => __( 'Germany', 'events-maker' ),
			'GH' => __( 'Ghana', 'events-maker' ),
			'GI' => __( 'Gibraltar', 'events-maker' ),
			'GR' => __( 'Greece', 'events-maker' ),
			'GL' => __( 'Greenland', 'events-maker' ),
			'GD' => __( 'Grenada', 'events-maker' ),
			'GP' => __( 'Guadeloupe', 'events-maker' ),
			'GT' => __( 'Guatemala', 'events-maker' ),
			'GG' => __( 'Guernsey', 'events-maker' ),
			'GN' => __( 'Guinea', 'events-maker' ),
			'GW' => __( 'Guinea-Bissau', 'events-maker' ),
			'GY' => __( 'Guyana', 'events-maker' ),
			'HT' => __( 'Haiti', 'events-maker' ),
			'HM' => __( 'Heard Island and McDonald Islands', 'events-maker' ),
			'HN' => __( 'Honduras', 'events-maker' ),
			'HK' => __( 'Hong Kong', 'events-maker' ),
			'HU' => __( 'Hungary', 'events-maker' ),
			'IS' => __( 'Iceland', 'events-maker' ),
			'IN' => __( 'India', 'events-maker' ),
			'ID' => __( 'Indonesia', 'events-maker' ),
			'IR' => __( 'Iran', 'events-maker' ),
			'IQ' => __( 'Iraq', 'events-maker' ),
			'IE' => __( 'Republic of Ireland', 'events-maker' ),
			'IM' => __( 'Isle of Man', 'events-maker' ),
			'IL' => __( 'Israel', 'events-maker' ),
			'IT' => __( 'Italy', 'events-maker' ),
			'CI' => __( 'Ivory Coast', 'events-maker' ),
			'JM' => __( 'Jamaica', 'events-maker' ),
			'JP' => __( 'Japan', 'events-maker' ),
			'JE' => __( 'Jersey', 'events-maker' ),
			'JO' => __( 'Jordan', 'events-maker' ),
			'KZ' => __( 'Kazakhstan', 'events-maker' ),
			'KE' => __( 'Kenya', 'events-maker' ),
			'KI' => __( 'Kiribati', 'events-maker' ),
			'KW' => __( 'Kuwait', 'events-maker' ),
			'KG' => __( 'Kyrgyzstan', 'events-maker' ),
			'LA' => __( 'Laos', 'events-maker' ),
			'LV' => __( 'Latvia', 'events-maker' ),
			'LB' => __( 'Lebanon', 'events-maker' ),
			'LS' => __( 'Lesotho', 'events-maker' ),
			'LR' => __( 'Liberia', 'events-maker' ),
			'LY' => __( 'Libya', 'events-maker' ),
			'LI' => __( 'Liechtenstein', 'events-maker' ),
			'LT' => __( 'Lithuania', 'events-maker' ),
			'LU' => __( 'Luxembourg', 'events-maker' ),
			'MO' => __( 'Macao S.A.R., China', 'events-maker' ),
			'MK' => __( 'Macedonia', 'events-maker' ),
			'MG' => __( 'Madagascar', 'events-maker' ),
			'MW' => __( 'Malawi', 'events-maker' ),
			'MY' => __( 'Malaysia', 'events-maker' ),
			'MV' => __( 'Maldives', 'events-maker' ),
			'ML' => __( 'Mali', 'events-maker' ),
			'MT' => __( 'Malta', 'events-maker' ),
			'MH' => __( 'Marshall Islands', 'events-maker' ),
			'MQ' => __( 'Martinique', 'events-maker' ),
			'MR' => __( 'Mauritania', 'events-maker' ),
			'MU' => __( 'Mauritius', 'events-maker' ),
			'YT' => __( 'Mayotte', 'events-maker' ),
			'MX' => __( 'Mexico', 'events-maker' ),
			'FM' => __( 'Micronesia', 'events-maker' ),
			'MD' => __( 'Moldova', 'events-maker' ),
			'MC' => __( 'Monaco', 'events-maker' ),
			'MN' => __( 'Mongolia', 'events-maker' ),
			'ME' => __( 'Montenegro', 'events-maker' ),
			'MS' => __( 'Montserrat', 'events-maker' ),
			'MA' => __( 'Morocco', 'events-maker' ),
			'MZ' => __( 'Mozambique', 'events-maker' ),
			'MM' => __( 'Myanmar', 'events-maker' ),
			'NA' => __( 'Namibia', 'events-maker' ),
			'NR' => __( 'Nauru', 'events-maker' ),
			'NP' => __( 'Nepal', 'events-maker' ),
			'NL' => __( 'Netherlands', 'events-maker' ),
			'AN' => __( 'Netherlands Antilles', 'events-maker' ),
			'NC' => __( 'New Caledonia', 'events-maker' ),
			'NZ' => __( 'New Zealand', 'events-maker' ),
			'NI' => __( 'Nicaragua', 'events-maker' ),
			'NE' => __( 'Niger', 'events-maker' ),
			'NG' => __( 'Nigeria', 'events-maker' ),
			'NU' => __( 'Niue', 'events-maker' ),
			'NF' => __( 'Norfolk Island', 'events-maker' ),
			'KP' => __( 'North Korea', 'events-maker' ),
			'NO' => __( 'Norway', 'events-maker' ),
			'OM' => __( 'Oman', 'events-maker' ),
			'PK' => __( 'Pakistan', 'events-maker' ),
			'PS' => __( 'Palestinian Territory', 'events-maker' ),
			'PA' => __( 'Panama', 'events-maker' ),
			'PG' => __( 'Papua New Guinea', 'events-maker' ),
			'PY' => __( 'Paraguay', 'events-maker' ),
			'PE' => __( 'Peru', 'events-maker' ),
			'PH' => __( 'Philippines', 'events-maker' ),
			'PN' => __( 'Pitcairn', 'events-maker' ),
			'PL' => __( 'Poland', 'events-maker' ),
			'PT' => __( 'Portugal', 'events-maker' ),
			'QA' => __( 'Qatar', 'events-maker' ),
			'RE' => __( 'Reunion', 'events-maker' ),
			'RO' => __( 'Romania', 'events-maker' ),
			'RU' => __( 'Russia', 'events-maker' ),
			'RW' => __( 'Rwanda', 'events-maker' ),
			'BL' => __( 'Saint Barth&eacute;lemy', 'events-maker' ),
			'SH' => __( 'Saint Helena', 'events-maker' ),
			'KN' => __( 'Saint Kitts and Nevis', 'events-maker' ),
			'LC' => __( 'Saint Lucia', 'events-maker' ),
			'MF' => __( 'Saint Martin (French part)', 'events-maker' ),
			'SX' => __( 'Saint Martin (Dutch part)', 'events-maker' ),
			'PM' => __( 'Saint Pierre and Miquelon', 'events-maker' ),
			'VC' => __( 'Saint Vincent and the Grenadines', 'events-maker' ),
			'SM' => __( 'San Marino', 'events-maker' ),
			'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'events-maker' ),
			'SA' => __( 'Saudi Arabia', 'events-maker' ),
			'SN' => __( 'Senegal', 'events-maker' ),
			'RS' => __( 'Serbia', 'events-maker' ),
			'SC' => __( 'Seychelles', 'events-maker' ),
			'SL' => __( 'Sierra Leone', 'events-maker' ),
			'SG' => __( 'Singapore', 'events-maker' ),
			'SK' => __( 'Slovakia', 'events-maker' ),
			'SI' => __( 'Slovenia', 'events-maker' ),
			'SB' => __( 'Solomon Islands', 'events-maker' ),
			'SO' => __( 'Somalia', 'events-maker' ),
			'ZA' => __( 'South Africa', 'events-maker' ),
			'GS' => __( 'South Georgia/Sandwich Islands', 'events-maker' ),
			'KR' => __( 'South Korea', 'events-maker' ),
			'SS' => __( 'South Sudan', 'events-maker' ),
			'ES' => __( 'Spain', 'events-maker' ),
			'LK' => __( 'Sri Lanka', 'events-maker' ),
			'SD' => __( 'Sudan', 'events-maker' ),
			'SR' => __( 'Suriname', 'events-maker' ),
			'SJ' => __( 'Svalbard and Jan Mayen', 'events-maker' ),
			'SZ' => __( 'Swaziland', 'events-maker' ),
			'SE' => __( 'Sweden', 'events-maker' ),
			'CH' => __( 'Switzerland', 'events-maker' ),
			'SY' => __( 'Syria', 'events-maker' ),
			'TW' => __( 'Taiwan', 'events-maker' ),
			'TJ' => __( 'Tajikistan', 'events-maker' ),
			'TZ' => __( 'Tanzania', 'events-maker' ),
			'TH' => __( 'Thailand', 'events-maker' ),
			'TL' => __( 'Timor-Leste', 'events-maker' ),
			'TG' => __( 'Togo', 'events-maker' ),
			'TK' => __( 'Tokelau', 'events-maker' ),
			'TO' => __( 'Tonga', 'events-maker' ),
			'TT' => __( 'Trinidad and Tobago', 'events-maker' ),
			'TN' => __( 'Tunisia', 'events-maker' ),
			'TR' => __( 'Turkey', 'events-maker' ),
			'TM' => __( 'Turkmenistan', 'events-maker' ),
			'TC' => __( 'Turks and Caicos Islands', 'events-maker' ),
			'TV' => __( 'Tuvalu', 'events-maker' ),
			'UG' => __( 'Uganda', 'events-maker' ),
			'UA' => __( 'Ukraine', 'events-maker' ),
			'AE' => __( 'United Arab Emirates', 'events-maker' ),
			'GB' => __( 'United Kingdom', 'events-maker' ),
			'US' => __( 'United States', 'events-maker' ),
			'UY' => __( 'Uruguay', 'events-maker' ),
			'UZ' => __( 'Uzbekistan', 'events-maker' ),
			'VU' => __( 'Vanuatu', 'events-maker' ),
			'VA' => __( 'Vatican', 'events-maker' ),
			'VE' => __( 'Venezuela', 'events-maker' ),
			'VN' => __( 'Vietnam', 'events-maker' ),
			'WF' => __( 'Wallis and Futuna', 'events-maker' ),
			'EH' => __( 'Western Sahara', 'events-maker' ),
			'WS' => __( 'Western Samoa', 'events-maker' ),
			'YE' => __( 'Yemen', 'events-maker' ),
			'ZM' => __( 'Zambia', 'events-maker' ),
			'ZW' => __( 'Zimbabwe', 'events-maker' )
		) );
	}

}
