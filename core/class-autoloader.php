<?php
/**
 * WPOnion Autoloader Class.
 * Initial version created 05-05-2018 / 04:38 PM
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 * @version 1.0
 * @since 1.0
 * @package wponion
 * @link http://github.com/wponion
 * @copyright 2018 Varun Sridharan
 * @license GPLV3 Or Greater (https://www.gnu.org/licenses/gpl-3.0.txt)
 */

namespace WPOnion;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\WPOnion\Autoloader' ) ) {
	/**
	 * Class Autoloader
	 *
	 * @package WPOnion
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 */
	final class Autoloader {
		/**
		 * Inits WPOnion_Autoloader.
		 *
		 * @static
		 */
		public static function init() {
			spl_autoload_register( '\WPOnion\Autoloader::load' );
		}

		/**
		 * Checks And Loads Required File Based on (Core/Fields).
		 *
		 * @param string $class_name
		 *
		 * @static
		 */
		public static function load( $class_name = '' ) {
			if ( false !== strpos( $class_name, 'WPOnion\\Field\\' ) ) {
				self::load_field( $class_name );
			} elseif ( false !== strpos( $class_name, 'WPOnion\\Value\\' ) ) {
				self::load_field_value( $class_name );
			} elseif ( false !== strpos( $class_name, 'WPOnion\\' ) ) {
				self::load_core( $class_name );
			}
		}

		/**
		 * Returns WPOnion Path.
		 *
		 * @param string $extra
		 *
		 * @return string
		 * @static
		 */
		public static function path( $extra = '' ) {
			return untrailingslashit( WPONION_PATH ) . '/' . $extra;
		}

		/**
		 * Converts Class Name into filename.
		 *
		 * @param        $class_name
		 * @param string $replace
		 *
		 * @return string
		 * @static
		 */
		public static function get_filename( $class_name, $replace = 'WPOnion_' ) {
			$file_name = strtolower( str_replace( $replace, '', $class_name ) );
			$file_name = str_replace( '_', '-', $file_name ) . '.php';
			return $file_name;
		}

		/**
		 * Loads Framework Core Files.
		 *
		 * @param $class_name
		 *
		 * @static
		 */
		public static function load_core( $class_name ) {
			$file_name = explode( '\\', $class_name );
			$file_name = end( $file_name );
			$file_name = self::get_filename( $file_name );
			if ( file_exists( self::path( 'core/' . 'class-' . $file_name ) ) ) {
				include_once self::path( 'core/' . 'class-' . $file_name );
			} elseif ( file_exists( self::path( 'core/abstract/' . $file_name ) ) ) {
				include_once self::path( 'core/abstract/' . $file_name );
			} elseif ( file_exists( self::path( 'core/db/' . 'class-' . $file_name ) ) ) {
				include_once self::path( 'core/db/' . 'class-' . $file_name );
			} elseif ( file_exists( self::path( 'core/modules/' . $file_name ) ) ) {
				include_once self::path( 'core/modules/' . $file_name );
			} elseif ( file_exists( self::path( 'core/modules/customizer/' . $file_name ) ) ) {
				include_once self::path( 'core/modules/customizer/' . $file_name );
			} elseif ( file_exists( self::path( 'core/modules/customizer/control/' . $file_name ) ) ) {
				include_once self::path( 'core/modules/customizer/control/' . $file_name );
			}
		}

		/**
		 * Loads Framework Field.
		 *
		 * @param $class_name
		 *
		 * @static
		 */
		public static function load_field( $class_name ) {
			$file_name = explode( '\\', $class_name );
			$file_name = end( $file_name );
			$file_name = self::get_filename( $file_name, 'WPOnion\\Field\\' );
			$folder    = str_replace( '.php', '', $file_name );
			if ( file_exists( self::path( 'fields/' . $file_name ) ) ) {
				include_once self::path( 'fields/' . $file_name );
			} elseif ( file_exists( self::path( 'fields/' . $folder . '/' . $file_name ) ) ) {
				include_once self::path( 'fields/' . $folder . '/' . $file_name );
			}
		}

		/**
		 * Loads Framework Field.
		 *
		 * @param $class_name
		 *
		 * @static
		 */
		public static function load_field_value( $class_name ) {
			$file_name = explode( '\\', $class_name );
			$file_name = end( $file_name );
			$file_name = self::get_filename( $file_name, 'WPOnion\\Value\\' );
			$folder    = str_replace( '.php', '', $file_name );
			if ( file_exists( self::path( 'fields/value-' . $file_name ) ) ) {
				include_once self::path( 'fields/value-' . $file_name );
			} elseif ( file_exists( self::path( 'fields/' . $folder . '/value.php' ) ) ) {
				include_once self::path( 'fields/' . $folder . '/value.php' );
			}
		}
	}
}
Autoloader::init();
