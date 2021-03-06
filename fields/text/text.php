<?php
/**
 *
 * Initial version created 09-05-2018 / 12:15 PM
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 * @version 1.0
 * @since 1.0
 * @package
 * @link
 * @copyright 2018 Varun Sridharan
 * @license GPLV3 Or Greater (https://www.gnu.org/licenses/gpl-3.0.txt)
 */

namespace WPOnion\Field;
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\WPOnion\Field\text' ) ) {
	/**
	 * Class WPOnion_Field_text
	 *
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 */
	class text extends \WPOnion\Field {
		/**
		 * Handles Input Attributes.
		 *
		 * @return string
		 */
		protected function _input_attributes() {
			$field_class = 'wponion-form-control';
			$field_class = ( $this->has_errors() ) ? $field_class . ' is-invalid ' : $field_class;

			return $this->attributes( array(
				'type'              => $this->element_type(),
				'class'             => $this->element_class( $field_class ),
				'value'             => $this->value(),
				'name'              => $this->name(),
				'data-wponion-jsid' => $this->js_field_id(),
			) );
		}

		/**
		 * Final HTML Output;
		 *
		 * @return mixed;
		 */
		protected function output() {
			echo $this->before();

			if ( false !== $this->has_prefix_surfix() ) {
				echo '<div class="wponion-input-group">';
			}

			if ( false !== $this->has( 'prefix' ) ) {
				echo '<div class="input-group-icon input-group-icon-before">' . $this->data( 'prefix' ) . '</div>';
			}

			if ( false !== $this->has_prefix_surfix() ) {
				echo '<div  class="input-group-area">';
			}

			echo '<input ' . $this->_input_attributes() . '/>';

			if ( false !== $this->has_prefix_surfix() ) {
				echo '</div>';
			}

			if ( false !== $this->has( 'surfix' ) ) {
				echo '<div class="input-group-icon input-group-icon-after">' . $this->data( 'surfix' ) . '</div>';
			}

			if ( false !== $this->has_prefix_surfix() ) {
				echo '</div>';
			}

			echo $this->after();
		}

		/**
		 * @return bool
		 */
		protected function has_prefix_surfix() {
			return ( false !== $this->has( 'prefix' ) || false !== $this->has( 'surfix' ) );
		}

		/**
		 * checks and updated fields args based on field config.
		 *
		 * @param array $field_data
		 *
		 * @return array
		 */
		public function handle_field_args( $field_data = array() ) {
			if ( false !== $field_data['inputmask'] ) {
				$field_data['wrap_class']                           = ( false !== $field_data['wrap_class'] ) ? '' : $field_data['wrap_class'];
				$field_data['wrap_class']                           = $field_data['wrap_class'] . ' ' . ' wponion-inputmask ';
				$field_data['attributes']['data-wponion-inputmask'] = 'yes';
			}

			if ( false !== $field_data['placeholder'] ) {
				$field_data['attributes']['placeholder'] = $field_data['placeholder'];
			}

			return $field_data;
		}

		/**
		 * Loads the required plugins assets.
		 *
		 * @return mixed|void
		 */
		public function field_assets() {
			if ( false !== $this->has( 'inputmask' ) ) {
				wponion_load_asset( 'wponion-inputmask' );
			}
		}

		/**
		 * Returns all fields default.
		 *
		 * @return array|mixed
		 */
		protected function field_default() {
			return array(
				'inputmask'   => false,
				'placeholder' => false,
				'prefix'      => false,
				'surfix'      => false,
			);
		}

		/**
		 * Returns required Datas to use in Javascript.
		 *
		 * @return array
		 */
		protected function js_field_args() {
			$args = array();
			if ( false !== $this->has( 'inputmask' ) ) {
				$args['inputmask'] = $this->data( 'inputmask' );
			}
			return $args;
		}
	}
}
