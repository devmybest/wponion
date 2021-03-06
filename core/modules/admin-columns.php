<?php
/**
 *
 * Project : wponion
 * Date : 17-11-2018
 * Time : 08:25 AM
 * File : admin-columns.php
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 * @version 1.0
 * @package wponion
 * @copyright 2018 Varun Sridharan
 * @license GPLV3 Or Greater (https://www.gnu.org/licenses/gpl-3.0.txt)
 */

namespace WPOnion\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\WPOnion\Modules\Admin_Columns' ) ) {
	/**
	 * Class Admin_Columns
	 *
	 * @package WPOnion\Modules
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 */
	class Admin_Columns extends \WPOnion\Bridge\Module {
		/**
		 * already_exists
		 *
		 * @var bool
		 */
		protected $already_exists = false;

		/**
		 * Admin_Columns constructor.
		 *
		 * @param array $post_type
		 * @param array $arguments
		 * @param array $render_callback
		 */
		public function __construct( $post_type = array(), $arguments = array(), $render_callback = array() ) {
			if ( ! empty( $post_type ) && isset( $post_type['title'] ) ) {
				parent::__construct( array(), $post_type );
				$this->on_init();
			} elseif ( ! empty( $post_type ) && ! empty( $arguments ) ) {
				$arguments = ( is_string( $arguments ) ) ? array( 'title' => $arguments ) : $arguments;
				$post_type = ( is_string( $post_type ) ) ? array( 'post_type' => $post_type ) : $post_type;

				if ( isset( $arguments[0] ) ) {
					foreach ( $arguments as $arg ) {
						$arg = ( is_string( $arg ) ) ? array( 'title' => $arg ) : $arg;
						if ( ! empty( $render_callback ) && ! isset( $arg['render'] ) ) {
							$arg['render'] = $render_callback;
						}
						if ( is_array( $post_type ) && isset( $post_type[0] ) ) {
							$arg = $this->parse_args( array( 'post_type' => $post_type ), $arg );
						} else {
							$arg = $this->parse_args( $post_type, $arg );
						}
						new self( $arg );
					}
				} else {
					if ( ! empty( $render_callback ) && ! isset( $arguments['render'] ) ) {
						$arguments['render'] = $render_callback;
					}
					if ( is_array( $post_type ) && isset( $post_type[0] ) ) {
						$arguments = $this->parse_args( array( 'post_type' => $post_type ), $arguments );
					} else {
						$arguments = $this->parse_args( $post_type, $arguments );
					}
					parent::__construct( array(), $arguments );
					$this->on_init();
				}
			} elseif ( ! empty( $post_type ) && empty( $arguments ) ) {
				if ( isset( $post_type[0] ) ) {
					foreach ( $post_type as $types ) {
						new self( $types );
					}
				} else {
					parent::__construct( array(), $post_type );
					$this->on_init();
				}
			}

		}

		/**
		 * Returns A Proper Hook Name.
		 *
		 * @param        $post_type
		 * @param string $surfix
		 * @param string $prefix
		 * @param string $middle
		 *
		 * @return string
		 */
		public function get_hook_name( $post_type, $surfix = 'custom_column', $prefix = 'manage_', $middle = '_posts_' ) {
			return $prefix . $post_type . $middle . $surfix;
		}

		/**
		 * Triggers An Instance.
		 */
		public function on_init() {
			$post_types = $this->option( 'post_type' );
			$post_types = ( ! is_array( $post_types ) ) ? array( $post_types ) : $post_types;
			foreach ( $post_types as $type ) {
				$this->add_filter( $this->get_hook_name( $type, 'columns' ), 'add_custom_column' );
				$this->add_filter( $this->get_hook_name( $type ), 'render_column', 30, 2 );
				if ( false !== $this->option( 'sortable' ) ) {
					$this->add_filter( $this->get_hook_name( $type, '_sortable_columns', 'manage_edit-', '' ), 'sortable_column' );
				}
			}
		}

		/**
		 * Enables Sortable Columns.
		 *
		 * @param $sort_cols
		 *
		 * @return mixed
		 */
		public function sortable_column( $sort_cols ) {
			if ( false === $this->already_exists ) {
				if ( false !== $this->option( 'sortable' ) && true !== $this->option( 'sortable' ) ) {
					$sort_cols[ $this->slug() ] = $this->option( 'sortable' );
				}
			}
			return $sort_cols;
		}

		/**
		 * Renders Col HTML.
		 *
		 * @param $col_name
		 * @param $post_id
		 */
		public function render_column( $col_name, $post_id ) {
			if ( false === $this->already_exists ) {
				$render = $this->option( 'render' );
				if ( $col_name === $this->slug() ) {
					if ( wponion_is_callable( $render ) ) {
						echo wponion_callback( $render, array( $post_id, $col_name, get_post_type( $post_id ) ) );
					} else {
						echo $render;
					}
				}
			}
		}

		/**
		 * Returns A Proper Col Slug.
		 *
		 * @return string
		 */
		public function slug() {
			return ( ! empty( $this->option( 'name' ) ) ) ? $this->option( 'name' ) : sanitize_title( $this->option( 'title' ) );
		}

		/**
		 * Creates A Custom Column.
		 *
		 * @param $data
		 *
		 * @return mixed
		 */
		public function add_custom_column( $data ) {
			global $typenow;

			if ( isset( $data[ $this->slug() ] ) ) {
				$this->already_exists = true;
			}

			if ( false !== $this->option( 'reorder' ) ) {
				if ( wponion_is_callable( $this->option( 'reorder' ) ) ) {
					$slug          = $this->slug();
					$data[ $slug ] = $this->option( 'title' );
					$data          = wponion_callback( $this->option( 'reorder' ), array( $data, $slug, $typenow ) );
				} else {
					$new = array();
					foreach ( $data as $key => $val ) {
						$new[ $key ] = $val;
						if ( $key === $this->option( 'reorder' ) ) {
							$new[ $this->slug() ] = $this->option( 'title' );
						}
					}
					$data = $new;
				}
			} else {
				$data[ $this->slug() ] = $this->option( 'title' );
			}
			return $data;
		}

		/**
		 * Returns Default Values.
		 *
		 * @return array
		 */
		protected function defaults() {
			return $this->parse_args( parent::defaults(), array(
				'post_type' => false,
				'name'      => false,
				'title'     => false,
				'reoder'    => false,
				'render'    => false,
				'sortable'  => false,
			) );
		}

		public function wrap_class( $extra_class = '' ) {
		}
	}
}
