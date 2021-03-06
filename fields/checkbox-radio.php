<?php
/**
 *
 * Initial version created 15-05-2018 / 06:17 AM
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

if ( ! class_exists( '\WPOnion\Field\checkbox_radio' ) ) {
	/**
	 * Class checkbox_radio
	 *
	 * @package WPOnion\Field
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 */
	class checkbox_radio extends \WPOnion\Field {
		/**
		 * Loads the required plugins assets.
		 *
		 * @return mixed|void
		 */
		public function field_assets() {
			// TODO: Implement field_assets() method.
		}

		/**
		 * Returns all fields default.
		 *
		 * @return array|mixed
		 */
		protected function field_default() {
			return array(
				'options' => array(),
				'label'   => false,
				'pretty'  => false,
			);
		}

		/**
		 * Final HTML Output;
		 *
		 * @return mixed;
		 */
		protected function output() {
			echo $this->before();
			$options = $this->data( 'options' );
			$options = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );
			$this->catch_output( 'start' );
			if ( is_array( $options ) && ! empty( $options ) ) {
				foreach ( $options as $option_key => $option ) {
					if ( ! is_array( $option ) || is_array( $option ) && isset( $option['label'] ) ) {
						echo $this->render_element( $this->handle_options( $option_key, $option ) );
					} elseif ( is_array( $option ) && false === isset( $option['label'] ) ) {
						echo '<div class="wponion-checkbox-radio-group">';
						echo '<div class="wponion-group-header"><h4>' . $option_key . '</h4></div>';
						echo '<ul class="wponion-checkbox-group-lists">';
						foreach ( $option as $key => $value ) {
							echo '<li>';
							echo $this->render_element( $this->handle_options( $key, $value ), true, $option_key );
							echo '</li>';
						}
						echo '</ul>';
						echo '</div>';
					}
				}
			} elseif ( in_array( $this->element_type(), array( 'switcher', 'checkbox' ) ) ) {
				echo $this->render_element( $this->handle_options( $this->field_id(), $this->data( 'label' ) ), 'single' );
			}

			echo $this->catch_output( 'stop' );
			echo $this->after();
		}

		/**
		 * @param $id
		 * @param $value
		 * @param $unique
		 * @param $field_args
		 *
		 * @return mixed
		 */
		protected function get_custom_input( $id, $value, $field_args ) {
			$field_args = ( true === $field_args ) ? 'text' : $field_args;
			return $this->sub_field( $this->handle_args( 'type', $field_args, array(
				'id'    => $id,
				'type'  => 'text',
				'class' => array( 'wponion-custom-value-input' ),
			), array( 'only_field' => true ) ), $value, $this->name() );
		}

		/**
		 * Returns Elements Value.
		 *
		 * @param $options
		 *
		 * @return mixed
		 */
		protected function element_value( $options ) {
			return $options['key'];
		}

		/**
		 * Renders Elements Options as html.
		 *
		 * @param      $options
		 * @param bool $in_group
		 * @param bool $group_title
		 *
		 * @return string
		 */
		protected function render_element( $options, $in_group = false, $group_title = false ) {
			$attr         = $options['attributes'];
			$attr['type'] = ( 'switcher' === $this->element_type() ) ? 'checkbox' : $this->element_type();

			if ( ! isset( $attr['class'] ) ) {
				$attr['class'] = array();
			}
			$attr['class'] = wponion_html_class( $attr['class'], $this->element_class( 'form-check-input' ) );
			$attr['value'] = $this->element_value( $options );
			$value         = $this->value();
			$wrap_attr     = array();
			$label_attr    = array();

			if ( true === $in_group || 'group' === $in_group ) {
				$gptitle      = sanitize_key( $group_title );
				$is_checkbox  = ( 'radio' !== $this->element_type() ) ? '[]' : '';
				$attr['name'] = $this->name( '[' . $gptitle . ']' . $is_checkbox );
				$value        = $this->get_value( $gptitle );
				$dep_id       = $gptitle . '_' . $options['key'];
			} elseif ( 'single' === $in_group ) {
				$attr['name']  = $this->name();
				$attr['value'] = $options['key'];
				$dep_id        = $options['key'];
			} else {
				$is_checkbox  = ( 'radio' !== $this->element_type() ) ? '[]' : '';
				$attr['name'] = $this->name( $is_checkbox );
				$dep_id       = $options['key'];
			}

			$elem_id    = sanitize_title( $attr['name'] . '_' . $options['key'] );
			$attr['id'] = $elem_id;

			if ( isset( $options['tooltip'] ) && is_array( $options['tooltip'] ) ) {
				$label_attr                      = $options['tooltip']['attr'];
				$label_attr['data-wponion-jsid'] = $this->js_field_id();
				$label_attr['data-field-jsid']   = $elem_id;
				$label_attr['class']             = ' wponion-field-tooltip ';
				wponion_localize()->add( $this->js_field_id(), array( $elem_id . 'tooltip' => $options['tooltip']['data'] ) );
			}

			$wrap_attr['class'] = wponion_html_class( array( 'form-group', 'form-check' ) );
			$field_attr         = $this->attributes( $attr, $dep_id );

			if ( true === $options['custom_input'] || true === is_array( $options['custom_input'] ) ) {
				$name             = $options['key'];
				$value            = ( false !== $this->value( $name ) ) ? $name : $value;
				$options['label'] = $this->get_custom_input( $name, $this->value( $name ), $options['custom_input'] );
			}

			return $this->_element_html( $label_attr, $field_attr, $value, $attr, $options );
		}

		/**
		 * Renders Single Option as html.
		 *
		 * @param $label_attr
		 * @param $field_attr
		 * @param $value
		 * @param $attr
		 * @param $options
		 *
		 * @return string
		 */
		protected function _element_html( $label_attr, $field_attr, $value, $attr, $options ) {
			if ( false !== $this->data( 'pretty' ) || false !== $options['pretty'] ) {
				if ( false !== $this->data( 'pretty' ) && is_array( $options['pretty'] ) ) {
					$pretty = $this->handle_args( 'class', $this->data( 'pretty' ), array(
						'state' => '',
						'class' => '',
					) );

					$pretty['state'] = $pretty['state'] . ' ' . $options['pretty']['state'];
					$pretty['class'] = $pretty['class'] . ' ' . $options['pretty']['class'];
				} elseif ( is_array( $options['pretty'] ) ) {
					$pretty = $options['pretty'];
				} else {
					$pretty = $this->handle_args( 'class', $this->data( 'pretty' ), array(
						'state' => '',
						'class' => '',
					) );
				}

				$wrap_class  = wponion_html_class( $pretty['class'], array( 'pretty' ) );
				$state_class = wponion_html_class( $pretty['state'], array( 'state' ) );
				$checkbox    = '<input ' . $field_attr . ' ' . $this->checked( $value, $attr['value'], 'checked' ) . ' >';
				$label       = $options['label'];
				return <<<HTML
<div class="{$wrap_class}"> {$checkbox} <div class="{$state_class}"> <label>{$label}</label> </div> </div>
HTML;

			}


			return '<div class=" form-group form-check ">
				<label ' . wponion_array_to_html_attributes( $label_attr ) . '>
					<input ' . $field_attr . ' ' . $this->checked( $value, $attr['value'], 'checked' ) . '  />' . $options['label'] . '
				</label>
			</div>';
		}
	}
}
