<?php

declare(strict_types=1);

namespace CPJ\ApptScheduler\CustomPostTypes;

class Appointments {

	public const POST_TYPE = 'cpj_appointment';

	/**
	 * Register WordPress hooks.
	 */
	public static function register(): void {
		add_action( 'init', [ static::class, 'registerPostType' ] );
	}

	/**
	 * Register the Appointments custom post type.
	 */
	public static function registerPostType(): void {
		$labels = [
			'name'                  => __( 'Appointments', 'cpj-appt-scheduler' ),
			'singular_name'         => __( 'Appointment', 'cpj-appt-scheduler' ),
			'menu_name'             => __( 'Appointments', 'cpj-appt-scheduler' ),
			'name_admin_bar'        => __( 'Appointment', 'cpj-appt-scheduler' ),
			'add_new'               => __( 'Add New', 'cpj-appt-scheduler' ),
			'add_new_item'          => __( 'Add New Appointment', 'cpj-appt-scheduler' ),
			'new_item'              => __( 'New Appointment', 'cpj-appt-scheduler' ),
			'edit_item'             => __( 'Edit Appointment', 'cpj-appt-scheduler' ),
			'view_item'             => __( 'View Appointment', 'cpj-appt-scheduler' ),
			'all_items'             => __( 'All Appointments', 'cpj-appt-scheduler' ),
			'search_items'          => __( 'Search Appointments', 'cpj-appt-scheduler' ),
			'parent_item_colon'     => __( 'Parent Appointments:', 'cpj-appt-scheduler' ),
			'not_found'             => __( 'No appointments found.', 'cpj-appt-scheduler' ),
			'not_found_in_trash'    => __( 'No appointments found in Trash.', 'cpj-appt-scheduler' ),
			'archives'              => __( 'Appointment Archives', 'cpj-appt-scheduler' ),
			'attributes'            => __( 'Appointment Attributes', 'cpj-appt-scheduler' ),
			'insert_into_item'      => __( 'Insert into appointment', 'cpj-appt-scheduler' ),
			'uploaded_to_this_item' => __( 'Uploaded to this appointment', 'cpj-appt-scheduler' ),
			'filter_items_list'     => __( 'Filter appointments list', 'cpj-appt-scheduler' ),
			'items_list_navigation' => __( 'Appointments list navigation', 'cpj-appt-scheduler' ),
			'items_list'            => __( 'Appointments list', 'cpj-appt-scheduler' ),
		];

		$args = [
			'labels'              => $labels,
			'description'         => __( 'Appointment requests and scheduled appointments.', 'cpj-appt-scheduler' ),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'hierarchical'        => false,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-calendar-alt',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'supports'            => [
				'title',
				'custom-fields',
			],
			'taxonomies'          => [],
		];

		register_post_type( static::POST_TYPE, $args );
	}

}
