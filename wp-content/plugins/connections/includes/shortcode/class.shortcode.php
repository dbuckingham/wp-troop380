<?php

use Connections_Directory\Utility\_array;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Common static methods that can be used across all core shortcodes.
 *
 * @package     Connections
 * @subpackage  Shortcode API
 * @copyright   Copyright (c) 2013, Steven A. Zahm
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.8
 */

class cnShortcode {

	private static $filterRegistry = array();

	/**
	 * Register required actions/filters.
	 *
	 * @access private
	 * @since  0.8
	 * @static
	 *
	 * @return void
	 */
	public static function hooks() {

		// Register the core shortcode with the WordPress Shortcode API.
		add_action( 'init', array( __CLASS__, 'register' ) );

		// add_filter( 'the_posts', array( __CLASS__, 'parse' ), 10, 2 );
		// remove_filter( 'the_content', 'wpautop' );

		add_filter( 'content_save_pre', array( __CLASS__, 'clean' ) );
		// add_filter( 'the_content',  array( __CLASS__, 'clean' ), 5 ); // Run before cnShortcode::single()

		add_filter( 'content_save_pre', array( __CLASS__, 'removeCodePreTags' ) );
		add_filter( 'the_content', array( __CLASS__, 'removeCodePreTags' ), 5 ); // Run before cnShortcode::single()

		// Run this early, before core WP filters.
		add_filter( 'the_content', array( __CLASS__, 'single' ), 6 );
	}

	/**
	 * Register the core shortcodes.
	 *
	 * @access private
	 * @since  0.8
	 * @static
	 * @uses   add_shortcode()
	 *
	 * @return void
	 */
	public static function register() {

		/*
		 * Do not register the shortcode when doing ajax requests.
		 * This is primarily implemented so the shortcodes are not run during Yoast SEO page score admin ajax requests.
		 * The page score can cause the ajax request to fail and/or prevent the page from saving when page score is
		 * being calculated on the output from the `[connections]` shortcode.
		 */
		if ( ! wp_doing_ajax() ) {

			// Register the core shortcodes.
			add_shortcode( 'connections', array( __CLASS__, 'view' ) );
			add_shortcode( 'upcoming_list', '_upcoming_list' );

			add_shortcode( 'cn-mapblock', array( 'Connections_Directory\Shortcode\mapBlock', 'shortcode' ) );

			add_shortcode( 'connections_vcard', '_connections_vcard' ); /* Experimental. Do NOT use. */
			add_shortcode( 'connections_qtip', '_connections_qtip' ); /* Experimental. Do NOT use. */

			add_shortcode( 'cn_thumb', array( 'cnThumb', 'shortcode' ) );
			add_shortcode( 'cn_thumbr', array( 'cnThumb_Responsive', 'shortcode' ) );

			Connections_Directory\Shortcode\Entry::add();
			Connections_Directory\Shortcode\Conditional_Content::add();
		}
	}

	/**
	 * Find the shortcode tag within the supplied string.
	 *
	 * @access public
	 * @since  8.4.5
	 * @static
	 *
	 * @param string $tag     The shortcode tag.
	 * @param string $content The string to find the shortcode tag in.
	 * @param string $return  What to return:
	 *                        Default: bool
	 *                        Accepts: atts, bool, matches
	 *
	 * @return array|bool     FALSE if shortcode tag is not found. Array of atts for each instance found or array of all
	 *                        matches found for the supplied shortcode tag.
	 */
	public static function find( $tag, $content, $return = 'bool' ) {

		global $shortcode_tags;

		// Exit early if the shortcode does not exist in content.
		if ( false === strpos( $content, "[$tag" ) || ! array_key_exists( $tag, $shortcode_tags ) ) {

			return false;
		}

		// Backup the registered shortcode tags, so they can be restored after searching for the requested shortcode.
		$registeredTags = $shortcode_tags;

		// Set the registered shortcodes to only the shortcode being searched for because this effects the results
		// returned by get_shortcode_regex() as it sets up the pattern to search for all registered shortcodes.
		// Limiting it to only the shortcode being searched for greatly improves this method's accuracy.
		$shortcode_tags = array( $tag => $shortcode_tags[ $tag ] );

		$pattern = get_shortcode_regex();
		$found   = array();

		if ( preg_match_all( '/' . $pattern . '/s', $content, $matches, PREG_SET_ORDER ) ) {

			if ( empty( $matches ) ) {

				return false;
			}

			foreach ( $matches as $shortcode ) {

				/*
				 * $shortcode[0] == If self-closing, The entire shortcode and options, including the opening and closing brackets.
				 *                  If enclosing, The entire shortcode and options, including the opening/closing brackets, content and closing shortcode.
				 * $shortcode[1] == Second opening bracket for escaping shortcodes: [[tag]].
				 * $shortcode[2] == The shortcode tag.
				 * $shortcode[3] == The shortcode options and their values as a string.
				 * $shortcode[4] == The `/` of a self closing shortcode.
				 * $shortcode[5] == If self-closing, unknown.
				 *                  If enclosing, the opening shortcode and options, including the opening/closing brackets and the content.
				 * $shortcode[6] == Second closing bracket for escaping shortcodes: [[tag]].
				 */

				// Allow [[foo]] syntax for escaping a tag.
				if ( '[' == $shortcode[1] && ']' == $shortcode[6] ) {

					// $found[] = substr( $shortcode[0], 1, -1 );
					continue;
				}

				if ( $tag === $shortcode[2] ) {

					$found[] = $shortcode;

				} elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {

					$found[] = $shortcode;
				}
			}
		}

		// Restore the registered shortcodes from the backup.
		$shortcode_tags = $registeredTags;

		switch ( $return ) {

			case 'atts':
				$atts = array();

				foreach ( $found as $shortcode ) {

					// Parse the shortcode atts.
					$atts[] = shortcode_parse_atts( $shortcode[3] );
				}

				return $atts;

			case 'bool':
				return ! empty( $found );

			case 'matches':
				return $found;

			default:
				return false;
		}
	}

	/**
	 * Programmatically write a shortcode.
	 *
	 * Rewrite bool strings (true|false) to (TRUE|FALSE) with quotes.
	 * Rewrite is_numeric() with no quotes.
	 * Check string to see if it has one or both single or double quotes and ensure to use the opposite when rewriting the value.
	 *
	 * @access public
	 * @since  8.4.5
	 * @since  8.5.21 Refactor to be more "smart" in writing the option values with/without quotes.
	 * @static
	 *
	 * @param string $tag  The shortcode tag.
	 * @param array  $atts An associative array where the key is the option name and the value is the option value.
	 *
	 * @return string
	 */
	public static function write( $tag, $atts = array() ) {

		$options = '';

		if ( is_array( $atts ) || ! empty( $atts ) ) {

			foreach ( $atts as $key  => $value ) {

				$options .= " $key=";

				if ( 'TRUE' == strtoupper( $value ) ) {

					$options .= "'TRUE'";

				} elseif ( 'FALSE' == strtoupper( $value ) ) {

					$options .= "'FALSE'";

				} elseif ( is_numeric( $value ) ) {

					$options .= $value;

				} elseif ( false === strpos( $value, '"' ) ) {

					$options .= '"' . $value . '"';

				} elseif ( false === strpos( $value, '\'' ) ) {

					$options .= '\'' . $value . '\'';

				} else {

					$options .= '\'' . $value . '\'';
				}
			}

		}

		return '[' . $tag . $options . ']';
	}

	/**
	 * Callback for `content_save_pre` filter.
	 * Callback for `the_content` filter.
	 *
	 * Users copy/paste shortcode examples from the website into the WP Visual editor.
	 * When pasting the code/pre tags will also be pasted.
	 * This filter should help those users by removing those tags when the post is saved and displayed.
	 *
	 * The `the_content` filter is used to apply this backwards on posts where the tags have already been saved.
	 *
	 * @access public
	 * @since  8.5.21
	 * @static
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public static function removeCodePreTags( $content ) {

		$original = $content;

		// $content = preg_replace( '/<(pre|code)(?:.*)>\s*(\[connections(?:.*)\])\s*<\/\1>/isu', '$2', $content );
		$content = preg_replace( '/<(pre|code)(?:.*)>\s*(\[connections(?:.*)\])\s*<\/\1>/iu', '$2', $content );

		/*
		 * If the pre_replace errors for some reason, return the original content.
		 */
		if ( is_null( $content ) ) {

			return $original;
		}

		return $content;
	}

	/**
	 * Callback for `content_save_pre` filter.
	 *
	 * Users copy/paste shortcode examples from the website into the WP Visual editor.
	 * When pasting the fancy "smart" quotes will also be pasted.
	 * This filter should help those users by removing those when the post is saved.
	 *
	 * @link http://stackoverflow.com/a/21491305/5351316
	 *
	 * @access public
	 * @since  8.5.21
	 * @static
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public static function clean( $content ) {

		// error_log( 'CURRENT FILTER: ' . current_filter() );
		// error_log( 'PRE-CONTENT: ' . $content );

		/*
		 * The $content is slashed in the `content_save_pre` filter, need to unslash it.
		 */
		$content = 'content_save_pre' == current_filter() ? wp_unslash( $content ) : $content;

		$matches = cnShortcode::find( 'connections', $content, 'matches' );
		// error_log( 'MATCHES: ' . json_encode( $matches, JSON_PRETTY_PRINT ) );

		if ( $matches ) {

			foreach ( $matches as $match ) {

				// $atts = shortcode_parse_atts( $match[3] );
				// error_log( 'PRE-PARSE: ' . json_encode( $atts, JSON_PRETTY_PRINT ) );

				$chr_map = array(
					// Windows codepage 1252.
					"\xC2\x82"     => "'", // U+0082⇒U+201A single low-9 quotation mark.
					"\xC2\x84"     => '"', // U+0084⇒U+201E double low-9 quotation mark.
					"\xC2\x8B"     => "'", // U+008B⇒U+2039 single left-pointing angle quotation mark.
					"\xC2\x91"     => "'", // U+0091⇒U+2018 left single quotation mark.
					"\xC2\x92"     => "'", // U+0092⇒U+2019 right single quotation mark.
					"\xC2\x93"     => '"', // U+0093⇒U+201C left double quotation mark.
					"\xC2\x94"     => '"', // U+0094⇒U+201D right double quotation mark.
					"\xC2\x9B"     => "'", // U+009B⇒U+203A single right-pointing angle quotation mark.

					// Regular Unicode     // U+0022 quotation mark (")
					// U+0027 apostrophe     (').
					"\xC2\xAB"     => '"', // U+00AB left-pointing double angle quotation mark.
					"\xC2\xBB"     => '"', // U+00BB right-pointing double angle quotation mark.
					"\xE2\x80\x98" => "'", // U+2018 left single quotation mark.
					"\xE2\x80\x99" => "'", // U+2019 right single quotation mark.
					"\xE2\x80\x9A" => "'", // U+201A single low-9 quotation mark.
					"\xE2\x80\x9B" => "'", // U+201B single high-reversed-9 quotation mark.
					"\xE2\x80\x9C" => '"', // U+201C left double quotation mark.
					"\xE2\x80\x9D" => '"', // U+201D right double quotation mark.
					"\xE2\x80\x9E" => '"', // U+201E double low-9 quotation mark.
					"\xE2\x80\x9F" => '"', // U+201F double high-reversed-9 quotation mark.
					"\xE2\x80\xB9" => "'", // U+2039 single left-pointing angle quotation mark.
					"\xE2\x80\xBA" => "'", // U+203A single right-pointing angle quotation mark.
				);

				$chr = array_keys( $chr_map );   // but: for efficiency you should.
				$rpl = array_values( $chr_map ); // pre-calculate these two arrays.

				$match[3] = str_replace( $chr, $rpl, html_entity_decode( $match[3], ENT_QUOTES, 'UTF-8' ) );

				$atts = shortcode_parse_atts( wp_unslash( $match[3] ) );
				// error_log( 'POST-PARSE: ' . json_encode( $atts, JSON_PRETTY_PRINT ) );

				$shortcode = cnShortcode::write( 'connections', $atts );

				$content = str_replace( $match[0], $shortcode, $content );
			}

		}

		/*
		 * The $content is slashed in the `content_save_pre` filter, need to slash it.
		 */
		$content = 'content_save_pre' == current_filter() ? wp_slash( $content ) : $content;
		// error_log( 'POST-CONTENT: ' . $content . PHP_EOL );

		return $content;
	}

	/**
	 * Callback for `the_content` filter.
	 *
	 * Checks for the `cn-entry-slug` query var and if it is set. Replace the post content with a shortcode to query
	 * only the queried entry.
	 *
	 * NOTE: The Divi theme has a visual page layout builder which uses shortcodes to generate the layout.
	 *       So if Divi is the child or root theme, replace just the shortcode instance rather than the post contents.
	 *       BUG: If multiple instances of the shortcode are on the page, only the first instance will be replaced,
	 *       defeating the purpose of this code -- to only display the first instance on the shortcode.
	 *       Possible solution is to check for multiple matches and replace all but the initial match with an empty string.
	 *
	 * @access private
	 * @since  unknown
	 * @since  8.5.21 Refactor to remove theme specific exclusion by remove all but the initial shortcode in the content
	 *                when viewing a single entry profile page.
	 * @static
	 *
	 * @param string $content Post content.
	 *
	 * @return string
	 */
	public static function single( $content ) {

		//error_log( "\n" . 'PRE-SINGLE: ' . $content . "\n" );

		$slug    = cnQuery::getVar( 'cn-entry-slug' );
		$matches = self::find( 'connections', $content, 'matches' );

		if ( $slug && $matches ) {

			//$atts = shortcode_parse_atts( $matches[0][3] );

			//$atts['slug'] = sanitize_title( $slug );

			//$shortcode = self::write( 'connections', $atts );
			//
			//$theme  = wp_get_theme();
			//$parent = $theme->parent();
			//
			//if ( FALSE === $parent ) {
			//
			//	$replace = in_array( $theme->get( 'Name' ), array( 'Divi', 'Enfold', 'Kleo' ), TRUE ) ? TRUE : FALSE;
			//
			//} elseif ( $parent instanceof WP_Theme ) {
			//
			//	$replace = in_array( $parent->get( 'Name' ), array( 'Divi', 'Enfold', 'Kleo' ), TRUE ) ? TRUE : FALSE;
			//
			//} else {
			//
			//	$replace = FALSE;
			//}
			//
			//if ( $replace ) {
			//
			//	$content = str_replace( $matches[0][0], $shortcode, $content );
			//
			//} else {
			//
			//	$content = $shortcode;
			//}

			foreach ( $matches as $key => $match ) {

				// Remove all but the first shortcode from the post content.
				if ( 0 < $key ) {

					//$content = str_replace( $match[0], '', $content );
					$content = cnString::replaceFirst( $match[0], '', $content );

				// Rewrite the shortcode, adding the entry slug to the shortcode.
				} else {

					$atts = shortcode_parse_atts( $match[3] );

					if ( ! is_array( $atts ) ) {
						$atts = array( $atts );
					}

					cnArray::set( $atts, 'slug', sanitize_title( $slug ) );

					// Do not apply `array_filter()` on the `$atts` because it will remove necessary options from the shortcode.
					//$atts = array_filter( $atts );

					$shortcode = cnShortcode::write( 'connections', $atts );

					//$content = str_replace( $match[0], $shortcode, $content );
					$content = cnString::replaceFirst( $match[0], $shortcode, $content );
				}
			}
		}

		//error_log( "\n" . 'POST-SINGLE: ' . $content . "\n" );

		return $content;
	}

	/**
	 * Experimental code to parse and process the shortcode very early in the
	 * WordPress execution stack. This allows the modification of its attributes
	 * before being processed by the WordPress Shortcode API.
	 *
	 * @access private
	 * @since  0.8
	 * @static
	 * @param  array  $posts
	 * @param  object $WP_Query
	 *
	 * @return array
	 */
	public static function parse( $posts, $WP_Query ) {

		$pattern = get_shortcode_regex();

		// Grab the array containing all query vars registered by Connections.
		$registeredQueryVars = cnRewrite::queryVars( array() );

		foreach ( $posts as $post ) {

			// $WP_Query->queried_object_id -- This will only be set on pages, not posts. Why? Good question!

			// If we're in the main query, proceed!
			if ( isset( $WP_Query->queried_object_id ) && $WP_Query->queried_object_id == $post->ID ) {

				/*
				 * $matches[0] == An array of all shortcodes that were found with its options.
				 * $matches[1] == Unknown.
				 * $matches[2] == An array of all shortcode tags that were found.
				 * $matches[3] == An array of the shortcode options that were found.
				 * $matches[4] == Unknown.
				 * $matches[5] == Unknown.
				 * $matches[6] == Unknown.
				 */

				if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) ) {

					// Build the results in a more usable format.
					foreach ( $matches[2] as $key => $shortcode ) {

						// Parse the shortcode atts.
						self::$shortcode[ $shortcode ] = shortcode_parse_atts( $matches[3][ $key ] );
					}


					// Show the just the search form w/o showing the initial results?
					// If a Connections query var is set, show the results instead.
					// if ( isset( $atts['initial_results'] )
					// 	&& strtolower( $atts['initial_results'] ) == 'false'
					// 	&& ! (bool) array_intersect( $registeredQueryVars, array_keys( (array) $WP_Query->query_vars ) )
					// 	)
					// {



					// } else {

					// 	// Rewrite the $atts array to prep it to be imploded.
					// 	array_walk( $atts, create_function( '&$i,$k','$i=" $k=\"$i\"";' ) );

					// 	$replace = '[' . $shortcode . ' ' . implode( ' ', $atts ) . ']';
					// }

					// All returns/end of lines and tabs should be removed so wpautop() doesn't insert <p> and <br> tags in the form output.
					// $replace = str_replace( array( "\r\n", "\r", "\n", "\t" ), array( ' ', ' ', ' ', ' ' ), $replace );

					// Replace the shortcode in the post with a new one based on the changes to $atts.
					// $post->post_content = str_replace( $matches[0][ array_search( $shortcode, $matches[2] ) ], $replace, $post->post_content );
				}

			}

		}

		return $posts;
	}

	/**
	 * Callback for the `[connections]` shortcode.
	 *
	 * Display results based on query var `cn-view`.
	 *
	 * @internal
	 * @since 0.7.3
	 *
	 * @param array|string $atts    Shortcode attributes array or empty string.
	 * @param string|null  $content The content of a shortcode when it wraps some content.
	 * @param string       $tag     Shortcode name.
	 *
	 * @return string
	 */
	public static function view( $atts, $content = '', $tag = 'connections' ) {

		// Ensure that the $atts var passed from WordPress is an array.
		if ( ! is_array( $atts ) ) {
			$atts = (array) $atts;
		}

		// Grab an instance of the Connections object.
		$instance = Connections_Directory();

		/*$getAllowPublic = $instance->options->getAllowPublic();
		var_dump($getAllowPublic);
		$getAllowPublicOverride = $instance->options->getAllowPublicOverride();
		var_dump($getAllowPublicOverride);
		$getAllowPrivateOverride = $instance->options->getAllowPrivateOverride();
		var_dump($getAllowPrivateOverride);*/

		/*
		 * Only show this message under the following condition:
		 * - ( The user is not logged in AND the 'Login Required' is checked ) AND ( neither of the shortcode visibility overrides are enabled ).
		 */
		if ( ( ! is_user_logged_in() && ! $instance->options->getAllowPublic() ) && ! ( $instance->options->getAllowPublicOverride() || $instance->options->getAllowPrivateOverride() ) ) {
			$message = $instance->settings->get( 'connections', 'connections_login', 'message' );

			// Format and texturize the message.
			$message = wptexturize( wpautop( $message ) );

			// Make any links and such clickable.
			$message = make_clickable( $message );

			// Apply the shortcodes.
			$message = do_shortcode( $message );

			return $message;
		}

		$view = cnQuery::getVar( 'cn-view' );

		switch ( $view ) {

			case 'submit':
				if ( has_action( 'cn_submit_entry_form' ) ) {

					ob_start();

					/**
					 * @todo There s/b capability checks just like when editing an entry so users can only submit when they have the permissions.
					 */
					do_action( 'cn_submit_entry_form', $atts, $content, $tag );

					return ob_get_clean();

				} else {

					return '<p>' . esc_html__( 'Future home of front end submissions.', 'connections' ) . '</p>';
				}

			case 'landing':
				return '<p>' . esc_html__( 'Future home of the landing pages, such a list of categories.', 'connections' ) . '</p>';

			case 'search':
				if ( has_action( 'cn_submit_search_form' ) ) {

					ob_start();

					do_action( 'cn_submit_search_form', $atts, $content, $tag );

					return ob_get_clean();

				} else {

					return '<p>' . esc_html__( 'Future home of the search page.', 'connections' ) . '</p>';
				}

			case 'results':
				if ( has_action( 'cn_submit_search_results' ) ) {

					ob_start();

					do_action( 'cn_submit_search_results', $atts, $content, $tag );

					return ob_get_clean();

				} else {

					return '<p>' . esc_html__( 'Future home of the search results landing page.', 'connections' ) . '</p>';
				}

			// Show the standard result list.
			case 'card':
				return cnShortcode_Connections::shortcode( $atts, $content );

			// Show the "View All" result list using the "Names" template.
			case 'all':
				if ( ! is_array( $atts ) ) {
					$atts = array();
				}

				// Disable the output of the repeat character index.
				cnArray::set( $atts, 'repeat_alphaindex', false );

				// Force the use of the Names template.
				cnArray::set( $atts, 'template', 'names' );

				return cnShortcode_Connections::shortcode( $atts, $content );

			// Show the entry detail using a template based on the entry type.
			case 'detail':
				switch ( cnQuery::getVar( 'cn-process' ) ) {

					case 'edit':
						if ( has_action( 'cn_edit_entry_form' ) ) {

							// Check to see if the entry has been linked to a user ID.
							$entryID = get_user_meta( get_current_user_id(), 'connections_entry_id', true );
							// var_dump( $entryID );

							$results = $instance->retrieve->entries( array( 'status' => 'approved,pending' ) );
							// var_dump( $results );

							/*
							 * The `cn_edit_entry_form` action should only be executed if the user is
							 * logged in and they have the `connections_manage` capability and either the
							 * `connections_edit_entry` or `connections_edit_entry_moderated` capability.
							 */

							if ( is_user_logged_in() &&
								( current_user_can( 'connections_manage' ) || ( (int) $entryID == (int) $results[0]->id ) ) &&
								( current_user_can( 'connections_edit_entry' ) || current_user_can( 'connections_edit_entry_moderated' ) )
								) {

								ob_start();

								if ( ! current_user_can( 'connections_edit_entry' ) && 'pending' === $results[0]->status ) {

									echo '<p>' . esc_html__( 'Your entry submission is currently under review, however, you can continue to make edits to your entry submission while your submission is under review.', 'connections' ) . '</p>';
								}

								do_action( 'cn_edit_entry_form', $atts, $content, $tag );

								return ob_get_clean();

							} else {

								return esc_html__( 'You are not authorized to edit entries. Please contact the admin if you received this message in error.', 'connections' );
							}

						}

						break;

					default:
						// Ensure an array is passed the the cnRetrieve::entries method.
						// if ( ! is_array( $atts ) ) $atts = (array) $atts;

						$results = $instance->retrieve->entries( $atts );
						// var_dump($results);

						$atts['list_type'] = $instance->settings->get( 'connections', 'connections_display_single', 'template' ) ? $results[0]->entry_type : null;

						return cnShortcode_Connections::shortcode( $atts, $content );
				}

				break;

			// Show the standard result list.
			default:
				// return cnShortcode_Connections::shortcode( $atts, $content );

				if ( has_action( "cn_view_$view" ) ) {

					ob_start();

					do_action( "cn_view_$view", $atts, $content, $tag );

					return ob_get_clean();
				}

				break;
		}

		return cnShortcode_Connections::shortcode( $atts, $content );
	}

	/**
	 * Whether or not the supplied WP_Post is of a supported post type.
	 *
	 * @since 10.2
	 *
	 * @param WP_Post $post
	 *
	 * @return bool
	 */
	public static function isSupportedPostType( $post ) {

		$supported = false;

		if ( ! $post instanceof WP_Post ) {

			return $supported;
		}

		// Create an array of supported post types.
		$supportedPostTypes = array( 'page' );
		$CPTOptions         = get_option( 'connections_cpt', array() );
		$supportedCPTTypes  = _array::get( $CPTOptions, 'supported', array() );

		// The `$supportedCPTTypes` should always be an array, but had at least one user where this was not the case.
		// To prevent PHP error notice, do an array check.
		if ( is_array( $supportedCPTTypes ) ) {

			$supportedPostTypes = array_merge( $supportedPostTypes, $supportedCPTTypes );
		}

		if ( in_array( $post->post_type, $supportedPostTypes ) ) {

			$supported = true;
		}

		return $supported;
	}

	/**
	 * Get the Directory Homepage ID based on context.
	 *
	 * @since 10.2
	 * @private
	 *
	 * @return int
	 */
	public static function getHomeID() {

		$homeID = cnSettingsAPI::get( 'connections', 'home_page', 'page_id' );
		$post   = get_queried_object();

		if ( in_the_loop() && ( is_page() || self::isSupportedPostType( $post ) ) ) {

			$homeID = get_the_ID();
		}

		return (int) $homeID;
	}

	/**
	 * The core `connections` shortcode can be used multiple times on a page.
	 * Because of this we need to keep track of the filters that are added
	 * during execution of the shortcode so the filters can be cleared and
	 * not applied to the other instance of the shortcode. This basically limits
	 * the filters to per shortcode instance.
	 *
	 * @access private
	 * @since  0.8
	 * @static
	 * @param  string $tag The action of filter hook tag.
	 *
	 * @return void
	 */
	public static function addFilterRegistry( $tag ) {

		self::$filterRegistry[] = $tag;
	}

	/**
	 * Clear the action/filter registry.
	 *
	 * @access private
	 * @since  0.8
	 * @static
	 *
	 * @return void
	 */
	public static function clearFilterRegistry() {
		global $wp_filter;

		/*
		 * Remove any filters a template may have added
		 * so it is not run again if more than one template
		 * is in use on the same page.
		 */
		foreach ( self::$filterRegistry as $filter ) {

			if ( isset( $wp_filter[ $filter ] ) ) {
				unset( $wp_filter[ $filter ] );
			}
		}
	}

	/**
	 * Remove end of line characters to support the themes that insist
	 * on running wpautop() AFTER the shortcode filter has been run on
	 * the_content.
	 *
	 * @access private
	 * @since  0.8
	 * @static
	 * @param  string $string The result of executing any of the core Connections shortcodes.
	 *
	 * @return string         The string with all EOL characters removed.
	 */
	public static function removeEOL( $string ) {

		if ( cnSettingsAPI::get( 'connections', 'compatibility', 'strip_rnt' ) ) {

			$search  = array( "\r\n", "\r", "\n", "\t", PHP_EOL );
			$replace = array( ' ' );
			$string  = str_replace( $search, $replace, $string );
		}

		return trim( $string );
	}

	/**
	 * Attempts to intelligently remove <p> and <br> tags added around
	 * the shortcodes by wpautop().
	 *
	 * @access public
	 * @since  0.8
	 * @param  string $content The content captured by the cn_template shortcode.
	 *
	 * @return string
	 */
	public static function removePBR( $content ) {

		$content = strtr(
			$content,
			array(
				'<p><!--'  => '<!--',
				'--></p>'  => '-->',
				'<p>['     => '[',
				']</p>'    => ']',
				'/]</p>'   => ']',
				']<br />'  => ']',
				'/]<br />' => ']',
			)
		);

		return $content;
	}
}
