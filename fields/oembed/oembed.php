<?php
/**
 *
 * Project : wponion
 * Date : 15-11-2018
 * Time : 06:48 AM
 * File : oembed.php
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 * @version 1.0
 * @package wponion
 * @copyright 2018 Varun Sridharan
 * @license GPLV3 Or Greater (https://www.gnu.org/licenses/gpl-3.0.txt)
 */

namespace WPOnion\Field;
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\WPOnion\Field\OEmbed' ) ) {
	/**
	 * Class WPOnion_Field_text
	 *
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 */
	class OEmbed extends \WPOnion\Field\Text {

		protected function after() {
			echo '<div class="wponion-oembed-preview" data-wponion-jsid="' . $this->js_field_id() . '"></div>';
			return parent::after();
		}

		public function handle_field_args( $field_data = array() ) {
			$field_data              = parent::handle_field_args( $field_data ); // TODO: Change the autogenerated stub
			$field_data['text_type'] = 'text';
			return $field_data;
		}
	}
}
