<?php
/*
Copyright: © 2009 WebSharks, Inc. ( coded in the USA )
<mailto:support@websharks-inc.com> <http://www.websharks-inc.com/>

Released under the terms of the GNU General Public License.
You should have received a copy of the GNU General Public License,
along with this software. In the main directory, see: /licensing/
If not, see: <http://www.gnu.org/licenses/>.
*/
/*
WARNING: This is a system configuration file, please DO NOT EDIT this file directly!
... Instead, use the widget options panel in WordPress® to override these settings.
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit ("Do not access this file directly.");
/*
Configure important global values for this widget + default values for options.
*/
$GLOBALS["WS_WIDGET__"]["super_tags"]["c"]["name"] = "Super Tags";
$GLOBALS["WS_WIDGET__"]["super_tags"]["c"]["control_w"] = "400"; /* Width of the control box. */
$GLOBALS["WS_WIDGET__"]["super_tags"]["c"]["description"] = "Highly customizable tag cloud for WordPress®.";
/*
Determine the full url to the directory this plugin resides in.
*/
$GLOBALS["WS_WIDGET__"]["super_tags"]["c"]["dir_url"] = (stripos (__FILE__, WP_CONTENT_DIR) !== 0) ? /* Have to assume plugins dir? */
plugins_url ("/" . basename (dirname (dirname (__FILE__)))) : /* Otherwise, this gives it a chance to live anywhere in the content dir. */
content_url (preg_replace ("/^(.*?)\/" . preg_quote (basename (WP_CONTENT_DIR), "/") . "/", "", str_replace (DIRECTORY_SEPARATOR, "/", dirname (dirname (__FILE__)))));
/*
Check if the plugin has been configured *should be set after the first config via options panel*.
*/
$GLOBALS["WS_WIDGET__"]["super_tags"]["c"]["configured"] = get_option ("ws_widget__super_tags_configured");
/*
Configure the right menu options panel for this software.
*/
$GLOBALS["WS_WIDGET__"]["super_tags"]["c"]["menu_pages"] = array ("installation" => false, "tools" => true, "videos" => false, "support" => true, "donations" => true);
/*
Configure multibyte detection order when charset is unknown ( used by calls to `mb_convert_encoding()` ).
*/
$GLOBALS["WS_WIDGET__"]["super_tags"]["c"]["mb_detection_order"] = "UTF-8, ISO-8859-1, WINDOWS-1252, ASCII, JIS, EUC-JP, SJIS";
/*
Configure checksum time for the syscon.inc.php file.
*/
$GLOBALS["WS_WIDGET__"]["super_tags"]["c"]["checksum"] = filemtime (__FILE__);
/*
Configure & validate all of the widget options; and set their defaults.
*/
if (!function_exists ("ws_widget__super_tags_configure_options_and_their_defaults"))
	{
		function ws_widget__super_tags_configure_options_and_their_defaults ($options = FALSE, $per_widget_options = FALSE)
			{
				/*
				Here we build the default options array, which will be merged with the user options.
				It is important to note that sometimes default options may not or should not be pre-filled on an options form.
				These defaults are for the system to use in various ways, we may choose not to pre-fill certain fields.
				In other words, some defaults may be used internally, but to the user, the option will be empty. */
				$default_options = apply_filters ("ws_widget__super_tags_default_options", array ( /* For filters. */
				/**/
				"options_checksum" => "", /* Used internally to maintain the integrity of all options in the array. */
				/**/
				"options_version" => "1.0", /* Used internally to maintain the integrity of all options in the array. */
				/**/
				"run_deactivation_routines" => "1")); /* Should deactivation routines be processed? */
				/*
				We also build the default widget options array, these get merged on a per-widget basis.
				It is important to note that sometimes default options may not or should not be pre-filled on an options form.
				These defaults are for the system to use in various ways, we may choose not to pre-fill certain fields.
				In other words, some defaults may be used internally, but to the user, the option will be empty. */
				$default_per_widget_options = apply_filters ("ws_widget__super_tags_default_per_widget_options", array (/**/
				/**/
				"title" => "Super Tags Widget", /* Ok for this particular option to be empty. */
				/**/
				"smallest" => "13", /* The text size of the tag with the smallest count value (units given by unit parameter). */
				"largest" => "20", /* The text size of the tag with the highest count value (units given by the unit parameter). */
				"unit" => "px", /* Unit of measure as pertains to the smallest and largest values. This can be any CSS length value, e.g. pt, px, em, %; default is pt (points). */
				"number" => "20", /* The number of actual tags to display in the cloud. (Use '0' to display all tags.) */
				"format" => "flat", /* Format of the cloud display. This can be flat or list.  */
				"orderby" => "name", /* Order of the tags. This can be name or count. */
				"order" => "RAND", /* Sort order. This can be ASC DESC or RAND. */
				"exclude" => "", /* Comma separated list of term_id's to exclude. */
				"include" => "", /* Comma separated list of term_id's to include. */
				"link" => "view", /* Set link to allow edit of a particular tag. */
				"taxonomy" => "post_tag")); /* This can be post_tag, category, or link_category. */
				/*
				Are we dealing with per-widget options here? 
				*/
				if ($per_widget_options !== false): /* If so we need to work differently. */
					/*
					Here they are merged. User options will overwrite some or all defaults. 
					*/
					$WS_WIDGET__per_widget_options = array_merge ($default_per_widget_options, (array)$per_widget_options);
					/*
					Validate each option, possibly reverting back to default if invalid.
					*/
					foreach ($WS_WIDGET__per_widget_options as $key => &$value)
						{
							if (!isset ($default_per_widget_options[$key]) && !preg_match ("/^pro_/", $key))
								unset ($WS_WIDGET__per_widget_options[$key]);
							/**/
							else if ($key === "title" && !is_string ($value))
								$value = $default_per_widget_options[$key]; /* Can be empty. */
							/**/
							else if ($key === "smallest" && (!is_string ($value) || !is_numeric ($value) || $value < 1))
								$value = $default_per_widget_options[$key]; /* Must be >= 1. */
							/**/
							else if ($key === "largest" && (!is_string ($value) || !is_numeric ($value) || $value < 1))
								$value = $default_per_widget_options[$key]; /* Must be >= 1. */
							/**/
							else if ($key === "unit" && (!is_string ($value) || !preg_match ("/^(pt|px|em|%)$/i", $value)))
								$value = $default_per_widget_options[$key]; /* Must be css compatible. */
							/**/
							else if ($key === "number" && (!is_string ($value) || !is_numeric ($value) || $value < 1))
								$value = $default_per_widget_options[$key]; /* Must be >= 1. */
							/**/
							else if ($key === "format" && (!is_string ($value) || !preg_match ("/^(flat|list)$/i", $value)))
								$value = $default_per_widget_options[$key]; /* Must be flat or list. */
							/**/
							else if ($key === "orderby" && (!is_string ($value) || !preg_match ("/^(name|count)$/i", $value)))
								$value = $default_per_widget_options[$key]; /* Must be name or count. */
							/**/
							else if ($key === "order" && (!is_string ($value) || !preg_match ("/^(ASC|DESC|RAND)$/i", $value)))
								$value = $default_per_widget_options[$key]; /* Must be sql compatible. */
							/**/
							else if ($key === "exclude" && (!is_string ($value) || !is_string ($value = preg_replace ("/[^0-9,]/", "", $value))))
								$value = $default_per_widget_options[$key]; /* This can be empty. */
							/**/
							else if ($key === "include" && (!is_string ($value) || !is_string ($value = preg_replace ("/[^0-9,]/", "", $value))))
								$value = $default_per_widget_options[$key]; /* This can be empty. */
							/**/
							else if ($key === "link" && (!is_string ($value) || !preg_match ("/^(view|edit)$/i", $value)))
								$value = $default_per_widget_options[$key]; /* Must be view or edit. */
							/**/
							else if ($key === "taxonomy" && (!is_string ($value) || !preg_match ("/^(post_tag|category|link_category)$/i", $value)))
								$value = $default_per_widget_options[$key]; /* Must be post_tag, category, or link_category. */
						}
					/**/
					return apply_filters ("ws_widget__super_tags_per_widget_options", $WS_WIDGET__per_widget_options);
				/*
				Else we are dealing with the main plugin option configuration or validation. 
				*/
				else: /* We need to validate these while checking to see if options were passed in. */
					/*
					Here they are merged. User options will overwrite some or all defaults. 
					*/
					$GLOBALS["WS_WIDGET__"]["super_tags"]["o"] = array_merge ($default_options, (($options !== false) ? (array)$options : (array)get_option ("ws_widget__super_tags_options")));
					/*
					This builds an MD5 checksum for the full array of options. This also includes the config checksum and the current set of default options. 
					*/
					$checksum = md5 (($checksum_prefix = $GLOBALS["WS_WIDGET__"]["super_tags"]["c"]["checksum"] . serialize ($default_options)) . serialize (array_merge ($GLOBALS["WS_WIDGET__"]["super_tags"]["o"], array ("options_checksum" => 0))));
					/*
					Validate each option, possibly reverting back to default if invalid.
					*/
					if ($options !== false || ($GLOBALS["WS_WIDGET__"]["super_tags"]["o"]["options_checksum"] !== $checksum && $GLOBALS["WS_WIDGET__"]["super_tags"]["o"] !== $default_options))
						{
							foreach ($GLOBALS["WS_WIDGET__"]["super_tags"]["o"] as $key => &$value)
								{
									if (!isset ($default_options[$key]) && !preg_match ("/^pro_/", $key))
										unset ($GLOBALS["WS_WIDGET__"]["super_tags"]["o"][$key]);
									/**/
									else if ($key === "options_checksum" && (!is_string ($value) || !strlen ($value)))
										$value = $default_options[$key];
									/**/
									else if ($key === "options_version" && (!is_string ($value) || !is_numeric ($value)))
										$value = $default_options[$key];
									/**/
									else if ($key === "run_deactivation_routines" && (!is_string ($value) || !is_numeric ($value)))
										$value = $default_options[$key];
								}
							/**/
							$GLOBALS["WS_WIDGET__"]["super_tags"]["o"] = apply_filters_ref_array ("ws_widget__super_tags_options_before_checksum", array (&$GLOBALS["WS_WIDGET__"]["super_tags"]["o"]));
							/**/
							$GLOBALS["WS_WIDGET__"]["super_tags"]["o"]["options_checksum"] = md5 ($checksum_prefix . serialize (array_merge ($GLOBALS["WS_WIDGET__"]["super_tags"]["o"], array ("options_checksum" => 0))));
						}
					/**/
					return apply_filters_ref_array ("ws_widget__super_tags_options", array (&$GLOBALS["WS_WIDGET__"]["super_tags"]["o"]));
				/**/
				endif;
			}
	}
?>