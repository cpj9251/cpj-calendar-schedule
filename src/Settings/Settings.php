<?php

namespace CPJ\ApptScheduler\Settings;

class Settings {

	/**
	 * @var string
	 */
	public const PAGE_SLUG = 'cpj_appt_sched';

	/**
	 * @var string
	 */
	public const SCREEN_ID = 'toplevel_page_' . self::PAGE_SLUG;

	/**
	 * @var string
	 */
	public const SCRIPT_HANDLE = 'cpj-appt-sched-settings-script';

	/**
	 * @return void
	 */
	public static function init(): void {
		add_action( 'admin_menu', [ static::class, 'addSettingsMenu' ] );
		add_action( 'admin_enqueue_scripts', [ static::class, 'enqueueAssets' ] );
	}

	/**
	 * @return void
	 */
	public static function addSettingsMenu(): void {

		add_options_page(
			'Appointment Scheduler Settings',
			'Appt Scheduler',
			'manage_options',
			'cpj-appt-sched-settings-menu',
			[ static::class, 'renderSettingsPage' ]
		);
	}

	/**
	 * @return void
	 */
	public static function enqueueAssets(): void {
		/*
		$screen = get_current_screen();

		if ( !$screen || $screen->id !== static::SCREEN_ID ) {
			return;
		}*/

		wp_enqueue_style( 'wp-components' );

		$assetFile  = 'assets/build/admin/index.asset.php';
		$scriptFile = 'assets/build/admin/index.js';
		$cssFile    = 'assets/build/admin/index.css';

		/*
		foreach ( [ $assetFile, $scriptFile, $cssFile ] as $filePath ) {
			if ( !file_exists( UMS_SPECIAL_AGENT_DIRECTORY_DIR . "/$filePath" ) ) {
				$message = esc_html( 'UMS Special Agent: missing build file ' . $filePath . '. Please run the build.' );
				add_action(
					'admin_notices',
					static function () use ( $message ): void {
						echo '<div class="notice notice-error"><p>' . $message . '</p></div>';
					}
				);

				return;
			}
		}
		*/

		$asset = include CPJ_APPT_SCHED_DIR . "/$assetFile";

		wp_enqueue_script(
			static::SCRIPT_HANDLE,
			CPJ_APPT_SCHED_PLUGIN_BASE_URL . "/$scriptFile",
			$asset['dependencies'],
			$asset['version'],
			[ 'in_footer' => true ]
		);

		/*
		wp_enqueue_script( 'wp-api' );
		wp_enqueue_script( 'wp-i18n' );
		wp_enqueue_script( 'media-editor' );
		wp_enqueue_script( 'media-views' );

		wp_enqueue_media();
		*/

		wp_localize_script(
			static::SCRIPT_HANDLE,
			'cpjApptSchedSettingsScript',
			[
				'nonce'   => wp_create_nonce( 'wp_rest' ),
				'restURL' => 'cpj-appt-sched/v1/',
			]
		);

		wp_enqueue_style(
			static::SCRIPT_HANDLE . '-css',
			CPJ_APPT_SCHED_PLUGIN_BASE_URL . "/$cssFile",
			[],
			$asset['version'],
			'all'
		);
	}

	/**
	 * @return void
	 */
	public static function renderSettingsPage(): void {
		echo '<div class="wrap">';
		echo '<h1>Appointment Scheduler Settings</h1>';
		echo '<div id="cpj-appt-sched-settings-root"></div>';
		echo '</div>';
	}

}
