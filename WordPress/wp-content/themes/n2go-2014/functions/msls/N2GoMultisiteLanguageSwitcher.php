<?php

/*
Plugin Name: Multisite Language Switcher
Plugin URI: http://msls.co/
Description: A simple but powerful plugin that will help you to manage the relations of your contents in a multilingual multisite-installation.
Version: 0.9.9.3
Author: Dennis Ploetner
Author URI: http://lloc.de/
*/

/*
Copyright 2013  Dennis Ploetner  (email : re@lloc.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * MultisiteLanguageSwitcher
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @since 0.9.8
 */
if ( defined( 'MSLS_PLUGIN_VERSION' ) ) {

    require_once( 'N2GoMslsOptions.php' );
    require_once( 'N2GoMslsOutput.php' );

	if ( function_exists( 'is_multisite' ) && is_multisite() ) {

		/**
		 * Get the output for using the links to the translations in your code
		 *
		 * @package Msls
		 * @uses the_msls
		 *
		 * @param array $arr
		 * @return string
		 */
		function n2go_get_the_msls( array $arr = array() ) {
			$obj = N2GoMslsOutput::init()->set_tags( $arr );
			return ( sprintf( '%s', $obj ) );
		}

		/**
		 * Output the links to the translations in your template
		 *
		 * You can call this function directly like that
		 *
		 *     if ( function_exists ( 'the_msls' ) )
		 *         the_msls();
		 *
		 * @package Msls
		 *
		 * @param array $arr
		 */
		function n2go_the_msls( array $arr = array() ) {
			echo n2go_get_the_msls( $arr );
		}

		function n2go_output_language_selector_toggle() {
			$blog = MslsBlogCollection::instance()->get_current_blog();

			// hotfix: $blog might be NULL here sometimes... :(
			if ( $blog ) {
				$language = $blog->get_language();

				$mslsData = N2GoMslsOptions::create();

				echo '<div class="n2go-offCanvasLanguageSelectorToggle">';
				echo '<span class="n2go-offCanvasLanguageSelectorToggle_flag" style="background-image:url(' . $mslsData->get_flag_url( $language ) . ')"></span>';
				echo '</div>';
			}

		}

		function n2go_output_language_selector() {
			$blogs = MslsBlogCollection::instance()->get_objects();
			$mslsData = N2GoMslsOptions::create();

			$current_blog_id = MslsBlogCollection::instance()->get_current_blog_id();

			switch_to_blog( $current_blog_id );

			//features
			$currentBlogFeaturesSlug = get_field( 'features_slug', 'option' );

			if ( empty( $currentBlogFeaturesSlug ) ) {
				$currentBlogFeaturesSlug = 'features';
			}

			//infographics
			$currentBlogInfographicsSlug = get_field( 'infographics_slug', 'option' );

			if ( empty( $currentBlogInfographicsSlug ) ) {
				$currentBlogInfographicsSlug = 'infographics';
			}

			//whitepaper
			$currentBlogWhitepaperSlug = get_field( 'whitepaper_slug', 'option' );

			if ( empty( $currentBlogWhitepaperSlug ) ) {
				$currentBlogWhitepaperSlug = 'whitepaper';
			}

			//video-knowledge
			$currentBlogVideoKnowledgeSlug = get_field( 'videoKnowledge_slug', 'option' );

			if ( empty( $currentBlogVideoKnowledgeSlug ) ) {
				$currentBlogVideoKnowledgeSlug = 'video-knowledge';
			}

			//faq
			$currentBlogHelpTopicsPage = get_field( 'help_topics_page', 'option' );
			$currentBlogHelpTopicsSlug = 'faq';

			if ( $currentBlogHelpTopicsPage ) {
				$currentBlogHelpTopicsSlug = $currentBlogHelpTopicsPage->post_name;
			}

			restore_current_blog();

			$links = array();

			foreach ( $blogs as $blog ) {
				$link = new stdClass();
				$language = $blog->get_language();

				if ( $blog->userblog_id == $current_blog_id ) {
					$link->url = $mslsData->get_current_link( $language );
					$link->text = $blog->get_description();
					$link->src = $mslsData->get_flag_url( $language );
					$link->active = true;

					array_push( $links, $link );
				} else {
					switch_to_blog( $blog->userblog_id );

					$skip = false;

					// Post Type Archive
					if ( is_a( $mslsData, 'MslsOptionsQueryPostType' ) && wp_count_posts( $mslsData->args[ 0 ] ) ) {
						$skip = true;
					}

					if ( $mslsData->has_value( $language ) && $skip == false ) {

						$link->url = $mslsData->get_permalink( $language );
						$link->text = $blog->get_description();
						$link->src = $mslsData->get_flag_url( $language );
						$link->active = false;

						//features
						$featuresSlug = get_field( 'features_slug', 'option' );

						if ( empty( $featuresSlug ) ) {
							$featuresSlug = 'features';
						}

						//infographics
						$infographicsSlug = get_field( 'infographics_slug', 'option' );

						if ( empty( $infographicsSlug ) ) {
							$infographicsSlug = 'infographics';
						}

						//whitepaper_slug
						$whitepaperSlug = get_field( 'whitepaper_slug', 'option' );

						if ( empty( $whitepaperSlug ) ) {
							$whitepaperSlug = 'whitepaper';
						}

						//video-knowledge
						$videoKnowledgeSlug = get_field( 'videoKnowledge_slug', 'option' );

						if ( empty( $videoKnowledgeSlug ) ) {
							$videoKnowledgeSlug = 'video-knowledge';
						}

						//faq
						$helpTopicsPage = get_field( 'help_topics_page', 'option' );
						$helpTopicsSlug = 'faq';

						if ( $helpTopicsPage ) {
							$helpTopicsSlug = $helpTopicsPage->post_name;
						}

						$link->url = str_replace( '/' . $currentBlogFeaturesSlug . '/', '/' . $featuresSlug . '/', $link->url );
						$link->url = str_replace( '/' . $currentBlogHelpTopicsSlug . '/', '/' . $helpTopicsSlug . '/', $link->url );
						$link->url = str_replace( '/' . $currentBlogInfographicsSlug . '/', '/' . $infographicsSlug . '/', $link->url );
						$link->url = str_replace( '/' . $currentBlogWhitepaperSlug . '/', '/' . $whitepaperSlug . '/', $link->url );
						$link->url = str_replace( '/' . $currentBlogVideoKnowledgeSlug . '/', '/' . $videoKnowledgeSlug . '/', $link->url );

						array_push( $links, $link );
					}

					restore_current_blog();
				}
			}

			echo '<ul class="n2go-offCanvasLanguageSelector_list">';
			foreach ( $links as $link ) {
				$linkClass = $link->active ? 'active' : '';
				echo '<li class="' . $linkClass . '"><a id="link-offCanvasLanguageSelector-' . $link->url . '" href="' . $link->url . '"><span class="n2go-offCanvasLanguageSelector_flag" style="background-image:url(' . $link->src . ')"></span>' . $link->text . '</a></li>';
			}
			echo '</ul>';
		}

		function n2go_output_language_selector_options() {
			$blogs = MslsBlogCollection::instance()->get_objects();
			$mslsData = N2GoMslsOptions::create();

			$current_blog_id = MslsBlogCollection::instance()->get_current_blog_id();

			switch_to_blog( $current_blog_id );

			//features
			$currentBlogFeaturesSlug = get_field( 'features_slug', 'option' );

			if ( empty( $currentBlogFeaturesSlug ) ) {
				$currentBlogFeaturesSlug = 'features';
			}

			//infographics
			$currentBlogInfographicsSlug = get_field( 'infographics_slug', 'option' );

			if ( empty( $currentBlogInfographicsSlug ) ) {
				$currentBlogInfographicsSlug = 'infographics';
			}

			//whitepaper
			$currentBlogWhitepaperSlug = get_field( 'whitepaper_slug', 'option' );

			if ( empty( $currentBlogWhitepaperSlug ) ) {
				$currentBlogWhitepaperSlug = 'whitepaper';
			}

			//video-knowledge
			$currentBlogVideoKnowledgeSlug = get_field( 'videoKnowledge_slug', 'option' );

			if ( empty( $currentBlogVideoKnowledgeSlug ) ) {
				$currentBlogVideoKnowledgeSlug = 'video-knowledge';
			}

			//faq
			$currentBlogHelpTopicsPage = get_field( 'help_topics_page', 'option' );
			$currentBlogHelpTopicsSlug = 'faq';

			if ( $currentBlogHelpTopicsPage ) {
				$currentBlogHelpTopicsSlug = $currentBlogHelpTopicsPage->post_name;
			}

			restore_current_blog();


			$links = array();

			foreach ( $blogs as $blog ) {
				$link = new stdClass();
				$language = $blog->get_language();

				if ( $blog->userblog_id == $current_blog_id ) {
					$link->url = $mslsData->get_current_link();
					$link->text = $blog->get_description();
					$link->src = $mslsData->get_flag_url( $language );
					$link->active = true;

					array_push( $links, $link );
				} else {
					switch_to_blog( $blog->userblog_id );

					$skip = false;

					// Post Type Archive
					if ( is_a( $mslsData, 'MslsOptionsQueryPostType' ) && wp_count_posts( $mslsData->args[ 0 ] ) ) {
						$skip = true;
					}

					if ( $mslsData->has_value( $language ) && $skip == false ) {
						$link->url = $mslsData->get_permalink( $language );
						$link->text = $blog->get_description();
						$link->src = $mslsData->get_flag_url( $language );
						$link->active = false;

						//features
						$featuresSlug = get_field( 'features_slug', 'option' );

						if ( empty( $featuresSlug ) ) {
							$featuresSlug = 'features';
						}

						//infographics
						$infographicsSlug = get_field( 'infographics_slug', 'option' );

						if ( empty( $infographicsSlug ) ) {
							$infographicsSlug = 'infographics';
						}

						//whitepaper_slug
						$whitepaperSlug = get_field( 'whitepaper_slug', 'option' );

						if ( empty( $whitepaperSlug ) ) {
							$whitepaperSlug = 'whitepaper';
						}

						//video-knowledge
						$videoKnowledgeSlug = get_field( 'videoKnowledge_slug', 'option' );

						if ( empty( $videoKnowledgeSlug ) ) {
							$videoKnowledgeSlug = 'video-knowledge';
						}

						//faq
						$helpTopicsPage = get_field( 'help_topics_page', 'option' );
						$helpTopicsSlug = 'faq';

						if ( $helpTopicsPage ) {
							$helpTopicsSlug = $helpTopicsPage->post_name;
						}

						$link->url = str_replace( '/' . $currentBlogFeaturesSlug . '/', '/' . $featuresSlug . '/', $link->url );
						$link->url = str_replace( '/' . $currentBlogHelpTopicsSlug . '/', '/' . $helpTopicsSlug . '/', $link->url );
						$link->url = str_replace( '/' . $currentBlogInfographicsSlug . '/', '/' . $infographicsSlug . '/', $link->url );
						$link->url = str_replace( '/' . $currentBlogWhitepaperSlug . '/', '/' . $whitepaperSlug . '/', $link->url );
						$link->url = str_replace( '/' . $currentBlogVideoKnowledgeSlug . '/', '/' . $videoKnowledgeSlug . '/', $link->url );

						array_push( $links, $link );
					}

					restore_current_blog();
				}
			}

			foreach ( $links as $link ) {
				$selected = $link->active ? ' selected="selected"' : '';
				echo '<option value="' . $link->url . '"' . $selected . ' data-flag-url="' . $link->src . '">' . $link->text . '</option>';
			}
		}

		function n2go_msls_head() {
			$blogs = MslsBlogCollection::instance();
			$mydata = MslsOptions::create();
			foreach ( $blogs->get_objects() as $blog ) {
				$language = $blog->get_language();

				if ( $blog->userblog_id == $blogs->get_current_blog_id() ) {
					$url = $mydata->get_current_link();
				} else {
					switch_to_blog( $blog->userblog_id );

					if ( 'MslsOptions' != get_class( $mydata ) && !$mydata->has_value( $language ) ) {
						restore_current_blog();
						continue;
					}
					$url = $mydata->get_permalink( $language );

					restore_current_blog();
				}

				if ( has_filter( 'msls_head_hreflang' ) ) {

					/**
					 * Overrides the hreflang value
					 * @since 0.9.9
					 * @param string $language
					 */
					$hreflang = (string)apply_filters( 'msls_head_hreflang', $language );
				} else {
					$hreflang = $blog->get_alpha2();
				}


				switch_to_blog( $blog->userblog_id );

				//features
				$featuresSlug = get_field( 'features_slug', 'option' );

				if ( empty( $featuresSlug ) ) {
					$featuresSlug = 'features';
				}

				//infographics
				$InfographicsSlug = get_field( 'infographics_slug', 'option' );

				if ( empty( $InfographicsSlug ) ) {
					$InfographicsSlug = 'infographics';
				}

				//whitepaper
				$WhitepaperSlug = get_field( 'whitepaper_slug', 'option' );

				if ( empty( $WhitepaperSlug ) ) {
					$WhitepaperSlug = 'whitepaper';
				}

				//video-knowledge
				$VideoKnowledgeSlug = get_field( 'videoKnowledge_slug', 'option' );

				if ( empty( $VideoKnowledgeSlug ) ) {
					$VideoKnowledgeSlug = 'video-knowledge';
				}

				//faq
				$helpTopicsPage = get_field( 'help_topics_page', 'option' );
				$helpTopicsSlug = 'faq';

				if ( $helpTopicsPage ) {
					$helpTopicsSlug = $helpTopicsPage->post_name;
				}

				restore_current_blog();

				//features
				$currentBlogFeaturesSlug = get_field( 'features_slug', 'option' );

				if ( empty( $currentBlogFeaturesSlug ) ) {
					$currentBlogFeaturesSlug = 'features';
				}

				//infographics
				$currentBlogInfographicsSlug = get_field( 'infographics_slug', 'option' );

				if ( empty( $currentBlogInfographicsSlug ) ) {
					$currentBlogInfographicsSlug = 'infographics';
				}

				//whitepaper
				$currentBlogWhitepaperSlug = get_field( 'whitepaper_slug', 'option' );

				if ( empty( $currentBlogWhitepaperSlug ) ) {
					$currentBlogWhitepaperSlug = 'whitepaper';
				}

				//video-knowledge
				$currentBlogVideoKnowledgeSlug = get_field( 'videoKnowledge_slug', 'option' );

				if ( empty( $currentBlogVideoKnowledgeSlug ) ) {
					$currentBlogVideoKnowledgeSlug = 'video-knowledge';
				}

				//faq
				$currentBlogHelpTopicsPage = get_field( 'help_topics_page', 'option' );
				$currentBlogHelpTopicsSlug = 'faq';

				if ( $currentBlogHelpTopicsPage ) {
					$currentBlogHelpTopicsSlug = $currentBlogHelpTopicsPage->post_name;
				}

				$url = str_replace( '/' . $currentBlogFeaturesSlug . '/', '/' . $featuresSlug . '/', $url );
				$url = str_replace( '/' . $currentBlogHelpTopicsSlug . '/', '/' . $helpTopicsSlug . '/', $url );
				$url = str_replace( '/' . $currentBlogInfographicsSlug . '/', '/' . $InfographicsSlug . '/', $url );
				$url = str_replace( '/' . $currentBlogWhitepaperSlug . '/', '/' . $WhitepaperSlug . '/', $url );
				$url = str_replace( '/' . $currentBlogVideoKnowledgeSlug . '/', '/' . $VideoKnowledgeSlug . '/', $url );


				printf(
					'<link rel="alternate" hreflang="%s" href="%s" />',
					$hreflang,
					$url
				);
				echo "\n";
			}
		}
	}
}
