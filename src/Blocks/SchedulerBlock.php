<?php

namespace CPJ\ApptScheduler\Blocks;

class SchedulerBlock {

	/**
	 * @return void
	 */
	public static function init(): void {
		add_filter(
			'block_categories_all',
			function ( $categories ) {
				return in_array( 'cpj_blocks', array_column( $categories, 'slug' ), true )
					? $categories
					: array_merge(
						$categories,
						[
							[

								'slug'  => 'cpj_blocks',

								'title' => 'CPJ Blocks',
							],
						]
					);
			},
			10,
			1
		);

		add_action(
			'init',
			function () {
				register_block_type(
                    CPJ_APPT_SCHED_PLUGIN_BASE_PATH . '/assets/build/block',
					[
						'render_callback' => function () {
							echo '<div id="cpj-appt-sched-block-root"></div>';
						},
					]
				);
			}
		);
	}

}
