<?php

namespace Talog;

abstract class Logger
{
	protected $label = '';
	protected $hooks = array();
	protected $log_level = Log_Level::DEFAULT_LEVEL;
	protected $priority = 10;
	protected $accepted_args = 1;

	/**
	 * Logger constructor.
	 */
	public function __construct() {
		if ( empty( $this->label ) ) {
			wp_die( '`Talog\Logger\Logger` requires the `$label` property.' );
		}
		if ( empty( $this->hooks ) || ! is_array( $this->hooks ) ) {
			wp_die( '`Talog\Logger\Logger` requires the `$hooks` property.' );
		}
	}

	/**
	 * Set the properties to the `Talog\Log` object for the log.
	 *
	 * @param Log    $log             An instance of `Talog\Log`.
	 * @param mixed  $additional_args An array of the args that was passed from WordPress hook.
	 */
	abstract public function log( Log $log, $additional_args );

	/**
	 * Set the properties to `\WP_Post` for the admin.
	 *
	 * @param \WP_Post $post     The post object.
	 * @param array   $post_meta The post meta of the `$post`.
	 */
	abstract public function admin( \WP_Post $post, $post_meta );

	/**
	 * Returns the label text for the log.
	 *
	 * @return string The label text for the log.
	 */
	public function get_label()
	{
		return $this->label;
	}

	/**
	 * Returns the WordPress's action hook or filter hook.
	 *
	 * @return array The hook that will fire callback.
	 */
	public function get_hooks()
	{
		return $this->hooks;
	}

	/**
	 * Returns the value of `Talog\Log_Level`.
	 *
	 * @return string Log level that come from `Talog\Log_Level` class.
	 */
	public function get_log_level()
	{
		return Log_Level::get_level( $this->log_level );
	}

	/**
	 * Returns integer that will be used for `$priority` of the `add_filter()`.
	 *
	 * @return int Integer that will passed to the `add_filter()`.
	 */
	public function get_priority()
	{
		return $this->priority;
	}

	/**
	 * Returns integer that will be used for `$accepted_args` of the `add_filter()`.
	 *
	 * @return int Integer that will passed to the `add_filter()`.
	 */
	public function get_accepted_args()
	{
		return $this->accepted_args;
	}

	/**
	 *  Registers the callback function for the admin page.
	 */
	public function add_filter()
	{
		$hook = $this->get_hook_name();
		add_action( $hook, array( $this, 'admin' ), 10, 2 );
	}

	/**
	 * Returns the hook name.
	 *
	 * @return string The name of the hook.
	 */
	public function get_hook_name()
	{
		return 'talog_content_' . str_replace( '\\', '_', strtolower( get_class( $this ) ) );
	}
}
