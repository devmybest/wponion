<?php
/**
 *
 * Project : bullet-wp
 * Date : 17-08-2018
 * Time : 06:40 AM
 * File : fresh-init.php
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 * @version 1.0
 * @package bullet-wp
 * @copyright 2018 Varun Sridharan
 * @license GPLV3 Or Greater (https://www.gnu.org/licenses/gpl-3.0.txt)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'WPOnion_Fresh_Theme' ) ) {
	/**
	 * Class WPOnion_Theme_WP
	 *
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 */
	class WPOnion_Fresh_Theme extends \WPOnion\Theme_API {
		/**
		 * WPOnion_modern_Theme constructor.
		 *
		 * @param array  $data
		 * @param string $theme_file
		 */
		public function __construct( $data, $theme_file = __FILE__ ) {
			parent::__construct( $data, __FILE__, 'fresh' );
		}

		/**
		 * Registers Assets.
		 *
		 * @return mixed|void
		 */
		public function register_assets() {
			wp_enqueue_style( 'wponion-fresh-theme', $this->url( 'assets/wponion-fresh-theme.css' ), array( 'wponion-core' ) );
			wp_enqueue_script( 'wponion-fresh-theme', $this->url( 'assets/wponion-fresh-theme.js' ), array( 'wponion-core' ) );
		}

		/**
		 * Renders Metabox MENU HTML.
		 *
		 * @param $menu
		 *
		 * @return string
		 */
		public function metabox_menu_html( $menu, $parent_name = '' ) {
			$attr                    = isset( $menu['attributes'] ) ? $menu['attributes'] : array();
			$attr['title']           = isset( $attr['title'] ) ? $attr['title'] : $menu['title'];
			$page_title              = $menu['title'];
			$attr['data-href']       = $menu['href'];
			$attr['href']            = 'javascript:void(0);';
			$attr['class']           = isset( $attr['class'] ) ? $attr['class'] : array();
			$attr['class']           = wponion_html_class( $attr['class'], array(
				wponion_html_class( $menu['class'] ),
				( ! empty( $men['icon'] ) ) ? 'nav-with-icon' : '',
				( isset( $menu['is_internal_href'] ) && true === $menu['is_internal_href'] ) ? 'nav-internal-href' : '',
				( isset( $menu['is_active'] ) && true === $menu['is_active'] ) ? 'active' : '',
			) );
			$attr['data-wponion-id'] = ( ! empty( $parent_name ) ) ? 'wponion_menu_' . $parent_name . '_' . $menu['name'] : 'wponion_menu_' . $menu['name'];
			$attr                    = wponion_array_to_html_attributes( $attr );
			return '<a ' . $attr . '>' . wponion_icon( $menu['icon'] ) . $page_title . '</a>';
		}

		/**
		 * Generates Main Menu HTML.
		 *
		 * @return bool|string
		 */
		public function get_main_menu_html() {
			$return = '<ul class="wpo-ftnav">';
			$menus  = $this->settings()
				->settings_menus();

			if ( is_array( $menus ) ) {
				foreach ( $menus as $slug => $menu ) {
					if ( isset( $menu['is_seperator'] ) && true === $menu['is_seperator'] ) {
						continue;
					}
					$sub_menu      = $this->submenu_html( $slug );
					$attr          = isset( $menu['attributes'] ) ? $menu['attributes'] : array();
					$attr['title'] = isset( $attr['title'] ) ? $attr['title'] : $menu['title'];
					$page_title    = $menu['title'];
					$attr['href']  = $menu['href'];
					$attr['class'] = isset( $attr['class'] ) ? $attr['class'] : array();
					$attr['class'] = wponion_html_class( $attr['class'], array(
						( empty( $sub_menu ) ) ? '' : 'dropdown',
						wponion_html_class( $menu['class'] ),
						'wpo-ftnav-tab',
						( ! empty( $men['icon'] ) ) ? 'wpo-ftnav-with-icon' : '',
						( isset( $menu['is_internal_href'] ) && true === $menu['is_internal_href'] ) ? 'nav-internal-href' : '',
						( true === $menu['is_active'] ) ? 'active child-show' : '',
					) );
					$attr          = wponion_array_to_html_attributes( $attr );

					$return .= '<li><a ' . $attr . '>' . wponion_icon( $menu['icon'] ) . $page_title . '</a>' . $sub_menu . '</li>';
				}
			} else {
				return false;
			}

			$return .= '</ul>';
			return $return;
		}

		/**
		 * @param string $menu_slug
		 *
		 * @return string
		 */
		public function submenu_html( $menu_slug = '' ) {
			$menus = $this->settings()
				->settings_menus();

			if ( isset( $menus[ $menu_slug ]['submenu'] ) && ! empty( $menus[ $menu_slug ]['submenu'] ) && is_array( $menus[ $menu_slug ]['submenu'] ) ) {
				if ( count( $menus[ $menu_slug ]['submenu'] ) <= 1 ) {
					return '';
				}
				$return = array();
				foreach ( $menus[ $menu_slug ]['submenu'] as $slug => $menu ) {
					if ( isset( $menu['is_seperator'] ) && true === $menu['is_seperator'] ) {
						continue;
					}

					$attr          = isset( $menu['attributes'] ) ? $menu['attributes'] : array();
					$attr['title'] = isset( $attr['title'] ) ? $attr['title'] : $menu['title'];
					$page_title    = $menu['title'];
					$attr['href']  = $menu['href'];
					$attr['class'] = isset( $attr['class'] ) ? $attr['class'] : array();
					$attr['class'] = wponion_html_class( $attr['class'], array(
						wponion_html_class( $menu['class'] ),
						( ! empty( $men['icon'] ) ) ? 'nav-with-icon' : '',
						( isset( $menu['is_internal_href'] ) && true === $menu['is_internal_href'] ) ? 'nav-internal-href' : '',
						( true === $menu['is_active'] ) ? 'active' : '',
					) );

					$attr     = wponion_array_to_html_attributes( $attr );
					$return[] = '<li> <a ' . $attr . '>' . wponion_icon( $menu['icon'] ) . $page_title . '</a> ';
				}
				$return = implode( '  </li>', $return );
				$return = '<ul class="meta-submenu"  id="wponion-tab-' . $menus[ $menu_slug ]['name'] . '" >' . $return . '</ul>';
				return $return;# '<h2 class="wponion-subnav-container hndle">' . $return . '</h2>';
			} else {
				return '';
			}
		}
	}
}
