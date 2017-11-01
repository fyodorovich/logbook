<?php

namespace Hello;
use Talog;

class Test_Log extends Talog\Logger
{
	protected $label = 'Test';
	protected $hooks = array( 'test_hook' );
	protected $log_level = Talog\Log_Level::DEBUG;
	protected $priority = 10;
	protected $accepted_args = 2;

	public function get_log( Talog\Log $log, $additional_args ) {
		$GLOBALS['test-log'] = $additional_args;

		$log->set_title( 'hello' );
		$log->set_content( 'test-content' );

		$log->update_meta( 'name', 'Taro' );
	}

	public function get_the_content( \WP_Post $content, $post_meta ) {
		// TODO: Implement get_the_content() method.
	}
}
