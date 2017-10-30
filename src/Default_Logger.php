<?php

namespace Talog;

class Default_Logger
{
	public function __construct() {}

	public function get_loggers()
	{
		// Arrays that will be passed to `Talog\Logger\watch()`.
		$loggers = array(
			array(
				'Post',                                  // Label of the log.
				array( 'publish_post', 'publish_page' ), // Hooks.
				array( $this, 'publish_post_log' ),      // Callback function for log.
				array( $this, 'publish_post_message' ),  // Callback function for long message.
				Log_Level::DEFAULT_LEVEL,                // Log level.
				10,                                      // Priority.
				2,                                       // Number of accepted args.
			),
			array(
				'Post',
				array( 'post_updated' ),
				array( $this, 'post_updated_log' ),
				array( $this, 'post_updated_message' ),
				Log_Level::DEFAULT_LEVEL,
				10,
				3,
			),
			array(
				'Plugin',
				array( 'activated_plugin', 'deactivated_plugin' ),
				array( $this, 'activated_plugin' ),
				null,
				Log_Level::DEFAULT_LEVEL,
				10,
				1,
			),
			array(
				'User',
				array( 'wp_login' ),
				array( $this, 'wp_login_log' ),
				null,
				Log_Level::DEFAULT_LEVEL,
				10,
				2,
			),
			array(
				'Media',
				array( 'wp_delete_file' ),
				array( $this, 'wp_delete_file' ),
				null,
				Log_Level::DEFAULT_LEVEL,
				10,
				1,
			),
			array(
				'Post',
				array( 'delete_post' ),
				array( $this, 'delete_post' ),
				null,
				Log_Level::DEFAULT_LEVEL,
				10,
				1,
			),
			array(
				'Debug',
				array( 'shutdown' ),
				array( $this, 'shutdown_log' ),
				array( $this, 'shutdown_message' ),
				Log_Level::DEBUG,
				10,
				1,
			),
		);

		return apply_filters( 'talog_default_loggers', $loggers );
	}

	public function delete_post( $args )
	{
		list( $post_id ) = $args['additional_args'];

		$post_title = get_post( $post_id )->post_title;
		if ( empty( $post_title ) ) {
			$post_title = '(empty)';
		}

		return sprintf(
			'"%s" #%s has been deleted.',
			esc_html( $post_title ),
			esc_html( $post_id )
		);
	}

	public function wp_delete_file( $args )
	{
		list( $file ) = $args['additional_args'];

		return sprintf(
			'File "%s" was deleted.',
			esc_html( str_replace( untrailingslashit( ABSPATH ), '', $file ) )
		);
	}

	public function wp_login_log( $args )
	{
		list( $user_login, $user ) = $args['additional_args'];
		return sprintf(
			'User "%s" logged in.',
			esc_html( $user_login )
		);
	}

	public function post_updated_log( $args )
	{
		list( $post_id, $post_after, $post_before ) = $args['additional_args'];

		// Followings are always changed.
		unset( $post_after->post_modified_gmt, $post_after->post_modified,
				$post_before->post_modified_gmt, $post_before->post_modified );

		// Don't save log when it has no changes.
		if ( json_encode( $post_after ) === json_encode( $post_before ) ) {
			return '';
		}
		return esc_html( 'Updated "' . $post_after->post_title . '" #' . $post_id . '.' );
	}

	public function post_updated_message( $args )
	{
		list( $post_id, $post_after, $post_before ) = $args['additional_args'];

		$title = wp_text_diff( $post_before->post_title, $post_after->post_title );
		if ( $title ) {
			$title = '<div><h2 class="diff-title">Title</h2>' . $title . '</div>';
		}

		$content = wp_text_diff( $post_before->post_content, $post_after->post_content );
		if ( $content ) {
			$content = '<div><h2 class="diff-title">Contents</h2>' . $content . '</div>';
		}

		$status = wp_text_diff( $post_before->post_status, $post_after->post_status );
		if ( $status ) {
			$status = '<div><h2 class="diff-title">Status</h2>' . $status . '</div>';
		}

		$date = wp_text_diff( $post_before->post_date, $post_after->post_date );
		if ( $date ) {
			$date = '<div><h2 class="diff-title">Date</h2>' . $date . '</div>';
		}

		return '<div class="post-diff">' . $title . $content . $status . $date . '</div>';
	}

	public function publish_post_log( $args )
	{
		$post_id = $args['additional_args'][0];
		$post = $args['additional_args'][1];
		return esc_html( 'Published "' . $post->post_title . '" #' . $post_id . '.' );
	}

	public function publish_post_message( $args )
	{
		$post_id = $args['additional_args'][0];

		return sprintf(
			'<p><strong>URL:</strong> <a href="%1$s">%1$s</a></p>',
			esc_url( get_the_permalink( $post_id ) )
		);
	}

	public function activated_plugin( $args )
	{
		$plugin = $args['additional_args'][0];
		if ( 'activated_plugin' === $args['current_hook'] ) {
			return 'Plugin "' . dirname( $plugin ) . '" was activated.';
		} else {
			return 'Plugin "' . dirname( $plugin ) . '" was deactivated.';
		}
	}

	public function shutdown_log( $args )
	{
		if ( $args['last_error'] ) {
			return esc_html( $args['last_error']['message'] );
		} else {
			return null;
		}
	}

	public function shutdown_message( $args )
	{
		if ( $args['last_error'] ) {
			$err = $args['last_error'];
			if ( is_readable( $err['file'] ) ) {
				return sprintf(
					'<div><pre data-line="%d">%s</pre></div>',
					$err['line'],
					esc_html( file_get_contents( $err['file'] ) )
				);
			}
			return null;
		} else {
			return null;
		}
	}
}
