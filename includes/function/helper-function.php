<?php
/**
 * Helper function
 *
 * @version 1.0.0
 * @package wpm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function wpm_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'wpm_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}
/**
 * Get data if set, otherwise return a default value or null. Prevents notices when data is not set.
 *
 * @since  1.0.0
 * @param  mixed  $var     Variable.
 * @param  string $default Default value.
 * @return mixed
 */
function wpm_get_var( &$var, $default = null ) {
	return isset( $var ) ? $var : $default;
}
/**
 * Get full list of currency codes.
 *
 * Currency symbols and names should follow the Unicode CLDR recommendation (http://cldr.unicode.org/translation/currency-names)
 *
 * @return array
 */
function wpm_get_currencies() {
	$currencies = array_unique(
		array(
			'AED' => __( 'United Arab Emirates dirham', 'wp-music' ),
			'AFN' => __( 'Afghan afghani', 'wp-music' ),
			'ALL' => __( 'Albanian lek', 'wp-music' ),
			'AMD' => __( 'Armenian dram', 'wp-music' ),
			'ANG' => __( 'Netherlands Antillean guilder', 'wp-music' ),
			'AOA' => __( 'Angolan kwanza', 'wp-music' ),
			'ARS' => __( 'Argentine peso', 'wp-music' ),
			'AUD' => __( 'Australian dollar', 'wp-music' ),
			'AWG' => __( 'Aruban florin', 'wp-music' ),
			'AZN' => __( 'Azerbaijani manat', 'wp-music' ),
			'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'wp-music' ),
			'BBD' => __( 'Barbadian dollar', 'wp-music' ),
			'BDT' => __( 'Bangladeshi taka', 'wp-music' ),
			'BGN' => __( 'Bulgarian lev', 'wp-music' ),
			'BHD' => __( 'Bahraini dinar', 'wp-music' ),
			'BIF' => __( 'Burundian franc', 'wp-music' ),
			'BMD' => __( 'Bermudian dollar', 'wp-music' ),
			'BND' => __( 'Brunei dollar', 'wp-music' ),
			'BOB' => __( 'Bolivian boliviano', 'wp-music' ),
			'BRL' => __( 'Brazilian real', 'wp-music' ),
			'BSD' => __( 'Bahamian dollar', 'wp-music' ),
			'BTC' => __( 'Bitcoin', 'wp-music' ),
			'BTN' => __( 'Bhutanese ngultrum', 'wp-music' ),
			'BWP' => __( 'Botswana pula', 'wp-music' ),
			'BYR' => __( 'Belarusian ruble (old)', 'wp-music' ),
			'BYN' => __( 'Belarusian ruble', 'wp-music' ),
			'BZD' => __( 'Belize dollar', 'wp-music' ),
			'CAD' => __( 'Canadian dollar', 'wp-music' ),
			'CDF' => __( 'Congolese franc', 'wp-music' ),
			'CHF' => __( 'Swiss franc', 'wp-music' ),
			'CLP' => __( 'Chilean peso', 'wp-music' ),
			'CNY' => __( 'Chinese yuan', 'wp-music' ),
			'COP' => __( 'Colombian peso', 'wp-music' ),
			'CRC' => __( 'Costa Rican col&oacute;n', 'wp-music' ),
			'CUC' => __( 'Cuban convertible peso', 'wp-music' ),
			'CUP' => __( 'Cuban peso', 'wp-music' ),
			'CVE' => __( 'Cape Verdean escudo', 'wp-music' ),
			'CZK' => __( 'Czech koruna', 'wp-music' ),
			'DJF' => __( 'Djiboutian franc', 'wp-music' ),
			'DKK' => __( 'Danish krone', 'wp-music' ),
			'DOP' => __( 'Dominican peso', 'wp-music' ),
			'DZD' => __( 'Algerian dinar', 'wp-music' ),
			'EGP' => __( 'Egyptian pound', 'wp-music' ),
			'ERN' => __( 'Eritrean nakfa', 'wp-music' ),
			'ETB' => __( 'Ethiopian birr', 'wp-music' ),
			'EUR' => __( 'Euro', 'wp-music' ),
			'FJD' => __( 'Fijian dollar', 'wp-music' ),
			'FKP' => __( 'Falkland Islands pound', 'wp-music' ),
			'GBP' => __( 'Pound sterling', 'wp-music' ),
			'GEL' => __( 'Georgian lari', 'wp-music' ),
			'GGP' => __( 'Guernsey pound', 'wp-music' ),
			'GHS' => __( 'Ghana cedi', 'wp-music' ),
			'GIP' => __( 'Gibraltar pound', 'wp-music' ),
			'GMD' => __( 'Gambian dalasi', 'wp-music' ),
			'GNF' => __( 'Guinean franc', 'wp-music' ),
			'GTQ' => __( 'Guatemalan quetzal', 'wp-music' ),
			'GYD' => __( 'Guyanese dollar', 'wp-music' ),
			'HKD' => __( 'Hong Kong dollar', 'wp-music' ),
			'HNL' => __( 'Honduran lempira', 'wp-music' ),
			'HRK' => __( 'Croatian kuna', 'wp-music' ),
			'HTG' => __( 'Haitian gourde', 'wp-music' ),
			'HUF' => __( 'Hungarian forint', 'wp-music' ),
			'IDR' => __( 'Indonesian rupiah', 'wp-music' ),
			'ILS' => __( 'Israeli new shekel', 'wp-music' ),
			'IMP' => __( 'Manx pound', 'wp-music' ),
			'INR' => __( 'Indian rupee', 'wp-music' ),
			'IQD' => __( 'Iraqi dinar', 'wp-music' ),
			'IRR' => __( 'Iranian rial', 'wp-music' ),
			'IRT' => __( 'Iranian toman', 'wp-music' ),
			'ISK' => __( 'Icelandic kr&oacute;na', 'wp-music' ),
			'JEP' => __( 'Jersey pound', 'wp-music' ),
			'JMD' => __( 'Jamaican dollar', 'wp-music' ),
			'JOD' => __( 'Jordanian dinar', 'wp-music' ),
			'JPY' => __( 'Japanese yen', 'wp-music' ),
			'KES' => __( 'Kenyan shilling', 'wp-music' ),
			'KGS' => __( 'Kyrgyzstani som', 'wp-music' ),
			'KHR' => __( 'Cambodian riel', 'wp-music' ),
			'KMF' => __( 'Comorian franc', 'wp-music' ),
			'KPW' => __( 'North Korean won', 'wp-music' ),
			'KRW' => __( 'South Korean won', 'wp-music' ),
			'KWD' => __( 'Kuwaiti dinar', 'wp-music' ),
			'KYD' => __( 'Cayman Islands dollar', 'wp-music' ),
			'KZT' => __( 'Kazakhstani tenge', 'wp-music' ),
			'LAK' => __( 'Lao kip', 'wp-music' ),
			'LBP' => __( 'Lebanese pound', 'wp-music' ),
			'LKR' => __( 'Sri Lankan rupee', 'wp-music' ),
			'LRD' => __( 'Liberian dollar', 'wp-music' ),
			'LSL' => __( 'Lesotho loti', 'wp-music' ),
			'LYD' => __( 'Libyan dinar', 'wp-music' ),
			'MAD' => __( 'Moroccan dirham', 'wp-music' ),
			'MDL' => __( 'Moldovan leu', 'wp-music' ),
			'MGA' => __( 'Malagasy ariary', 'wp-music' ),
			'MKD' => __( 'Macedonian denar', 'wp-music' ),
			'MMK' => __( 'Burmese kyat', 'wp-music' ),
			'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'wp-music' ),
			'MOP' => __( 'Macanese pataca', 'wp-music' ),
			'MRU' => __( 'Mauritanian ouguiya', 'wp-music' ),
			'MUR' => __( 'Mauritian rupee', 'wp-music' ),
			'MVR' => __( 'Maldivian rufiyaa', 'wp-music' ),
			'MWK' => __( 'Malawian kwacha', 'wp-music' ),
			'MXN' => __( 'Mexican peso', 'wp-music' ),
			'MYR' => __( 'Malaysian ringgit', 'wp-music' ),
			'MZN' => __( 'Mozambican metical', 'wp-music' ),
			'NAD' => __( 'Namibian dollar', 'wp-music' ),
			'NGN' => __( 'Nigerian naira', 'wp-music' ),
			'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'wp-music' ),
			'NOK' => __( 'Norwegian krone', 'wp-music' ),
			'NPR' => __( 'Nepalese rupee', 'wp-music' ),
			'NZD' => __( 'New Zealand dollar', 'wp-music' ),
			'OMR' => __( 'Omani rial', 'wp-music' ),
			'PAB' => __( 'Panamanian balboa', 'wp-music' ),
			'PEN' => __( 'Sol', 'wp-music' ),
			'PGK' => __( 'Papua New Guinean kina', 'wp-music' ),
			'PHP' => __( 'Philippine peso', 'wp-music' ),
			'PKR' => __( 'Pakistani rupee', 'wp-music' ),
			'PLN' => __( 'Polish z&#x142;oty', 'wp-music' ),
			'PRB' => __( 'Transnistrian ruble', 'wp-music' ),
			'PYG' => __( 'Paraguayan guaran&iacute;', 'wp-music' ),
			'QAR' => __( 'Qatari riyal', 'wp-music' ),
			'RON' => __( 'Romanian leu', 'wp-music' ),
			'RSD' => __( 'Serbian dinar', 'wp-music' ),
			'RUB' => __( 'Russian ruble', 'wp-music' ),
			'RWF' => __( 'Rwandan franc', 'wp-music' ),
			'SAR' => __( 'Saudi riyal', 'wp-music' ),
			'SBD' => __( 'Solomon Islands dollar', 'wp-music' ),
			'SCR' => __( 'Seychellois rupee', 'wp-music' ),
			'SDG' => __( 'Sudanese pound', 'wp-music' ),
			'SEK' => __( 'Swedish krona', 'wp-music' ),
			'SGD' => __( 'Singapore dollar', 'wp-music' ),
			'SHP' => __( 'Saint Helena pound', 'wp-music' ),
			'SLL' => __( 'Sierra Leonean leone', 'wp-music' ),
			'SOS' => __( 'Somali shilling', 'wp-music' ),
			'SRD' => __( 'Surinamese dollar', 'wp-music' ),
			'SSP' => __( 'South Sudanese pound', 'wp-music' ),
			'STN' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'wp-music' ),
			'SYP' => __( 'Syrian pound', 'wp-music' ),
			'SZL' => __( 'Swazi lilangeni', 'wp-music' ),
			'THB' => __( 'Thai baht', 'wp-music' ),
			'TJS' => __( 'Tajikistani somoni', 'wp-music' ),
			'TMT' => __( 'Turkmenistan manat', 'wp-music' ),
			'TND' => __( 'Tunisian dinar', 'wp-music' ),
			'TOP' => __( 'Tongan pa&#x2bb;anga', 'wp-music' ),
			'TRY' => __( 'Turkish lira', 'wp-music' ),
			'TTD' => __( 'Trinidad and Tobago dollar', 'wp-music' ),
			'TWD' => __( 'New Taiwan dollar', 'wp-music' ),
			'TZS' => __( 'Tanzanian shilling', 'wp-music' ),
			'UAH' => __( 'Ukrainian hryvnia', 'wp-music' ),
			'UGX' => __( 'Ugandan shilling', 'wp-music' ),
			'USD' => __( 'United States (US) dollar', 'wp-music' ),
			'UYU' => __( 'Uruguayan peso', 'wp-music' ),
			'UZS' => __( 'Uzbekistani som', 'wp-music' ),
			'VEF' => __( 'Venezuelan bol&iacute;var', 'wp-music' ),
			'VES' => __( 'Bol&iacute;var soberano', 'wp-music' ),
			'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'wp-music' ),
			'VUV' => __( 'Vanuatu vatu', 'wp-music' ),
			'WST' => __( 'Samoan t&#x101;l&#x101;', 'wp-music' ),
			'XAF' => __( 'Central African CFA franc', 'wp-music' ),
			'XCD' => __( 'East Caribbean dollar', 'wp-music' ),
			'XOF' => __( 'West African CFA franc', 'wp-music' ),
			'XPF' => __( 'CFP franc', 'wp-music' ),
			'YER' => __( 'Yemeni rial', 'wp-music' ),
			'ZAR' => __( 'South African rand', 'wp-music' ),
			'ZMW' => __( 'Zambian kwacha', 'wp-music' ),
		)
	);
	return $currencies;
}
