<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );


	// Section
	add_filter( 'upb_section_list_toolbar', function ( $tools ) {
		$tools[ 'move' ] = array(
			'icon'  => 'mdi mdi-cursor-move',
			'class' => 'handle',
			'title' => 'Sort',
		);

		$tools[ 'delete' ] = array(
			'icon'  => 'mdi mdi-delete',
			'title' => 'Delete',
		);

		$tools[ 'enable' ]   = array(
			'icon'  => 'mdi mdi-eye',
			'title' => 'Enabled',
		);
		$tools[ 'disable' ]  = array(
			'icon'  => 'mdi mdi-eye-off',
			'title' => 'Disabled',
		);
		$tools[ 'contents' ] = array(
			'icon'  => 'mdi mdi-table-edit',
			'class' => 'show-contents',
			'title' => 'Contents',
		);
		$tools[ 'settings' ] = array(
			'icon'  => 'mdi mdi-settings',
			'class' => 'show-settings',
			'title' => 'Settings',
		);
		$tools[ 'clone' ]    = array(
			'icon'  => 'mdi mdi-content-duplicate',
			'title' => 'Clone',
		);

		return $tools;
	} );

	add_filter( 'upb_section_contents_panel_toolbar', function () {
		return array(
			array(
				'id'     => 'add-new-row',
				'title'  => 'Add Row',
				'icon'   => 'mdi mdi-table-row-plus-after',
				'action' => 'addNew',
				'data'   => apply_filters( 'upb_new_row_data', upb_elements()->generate_element( 'row', upb_elements()->get_element( 'column' ) ) )
			),
			array(
				'id'     => 'section-setting',
				'title'  => 'Settings',
				'icon'   => 'mdi mdi-settings',
				'action' => 'showSettingsPanel'
			),
			array(
				'id'     => 'save-section',
				'title'  => 'Save Section',
				'icon'   => 'mdi mdi-cube-send',
				'action' => 'saveSectionLayout'
			),
		);
	} );

	add_filter( 'upb_section_settings_panel_toolbar', function () {
		return array(
			array(
				'id'     => 'section-contents',
				'title'  => 'Contents',
				'icon'   => 'mdi mdi-file-tree',
				'action' => 'showContentPanel'
			)
		);
	} );

	add_filter( 'upb_sections_panel_contents', function () {


		return upb_elements()->set_upb_options(
			array(
				array(
					'tag'        => 'section',
					'contents'   => array(
						array(
							'tag'        => 'row',
							'contents'   => array(
								array(
									'tag'        => 'column',
									'contents'   => array(),
									'attributes' => array( 'enable' => TRUE, 'background' => '#ddd', 'title' => 'COL', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => '1:2' )
								),
								array(
									'tag'        => 'column',
									'contents'   => array(),
									'attributes' => array( 'enable' => TRUE, 'background' => '#ddd', 'title' => 'COL', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => '1:2' )
								),
							),
							'attributes' => array( 'enable' => TRUE, 'background' => '#ddd', 'title' => 'ROW GEN' )
						)

					),
					'attributes' => array( 'enable' => TRUE, 'background' => '#fff', 'title' => 'Section A' )
				),
				array(
					'tag'        => 'section',
					'contents'   => array(),
					'attributes' => array( 'enable' => TRUE, 'background' => '#ddd', 'title' => 'Section B' )
				)
			)
		);

		/*return upb_elements()->set_upb_options(
			array(
				upb_elements()->demo_data( 'section', upb_elements()->demo_data( 'row', upb_elements()->demo_data( 'column' ) ) ),
				upb_elements()->demo_data( 'section', upb_elements()->demo_data( 'row', upb_elements()->demo_data( 'column' ) ) ),
				upb_elements()->demo_data( 'section', upb_elements()->demo_data( 'row', upb_elements()->demo_data( 'column' ) ) )
			)
		);*/

		// return get_post_meta( get_the_ID(), '_upb_sections', TRUE );
	} );


	// device previews

	add_filter( 'upb_preview_devices', function () {
		return array(
			array(
				'id'     => 'lg',
				'title'  => 'Large',
				'icon'   => 'mdi mdi-desktop-mac',
				'active' => TRUE
			),
			array(
				'id'     => 'md',
				'title'  => 'Medium',
				'icon'   => 'mdi mdi-laptop-mac',
				'active' => FALSE
			),
			array(
				'id'     => 'sm',
				'title'  => 'Small',
				'icon'   => 'mdi mdi-tablet-ipad',
				'active' => FALSE
			),
			array(
				'id'     => 'xs',
				'title'  => 'Extra Small',
				'icon'   => 'mdi mdi-cellphone-iphone',
				'active' => FALSE
			),
		);
	} );

	// grid system

	add_filter( 'upb_grid_system', function () {
		return array(
			'name'              => 'Bootstrap 3',
			'simplifiedRatio'   => 'Its recommended to use simplified form of your grid ratio like: %s',
			'prefixClass'       => 'col',
			'separator'         => '-', // col- deviceId - grid class
			'groupClass'        => 'row',
			'groupWrapper'      => array(
				array(
					'name'  => 'Full Width',
					'class' => 'container-fluid'
				),
				array(
					'name'  => 'Fixed Width',
					'class' => 'container'
				),
			),
			'defaultDeviceId'   => 'xs',
			'deviceSizeTitle'   => 'Screen Sizes',
			'devices'           => apply_filters( 'upb_preview_devices', array() ),
			'totalGrid'         => 12,
			'allowedGrid'       => array( 1, 2, 3, 4, 6, 12 ),
			'nonAllowedMessage' => "Sorry, Bootstrap 3 doesn't support %s grid column."
		);
	} );


	// Row
	add_filter( 'upb_row_list_toolbar', function ( $tools ) {
		$tools[ 'move' ] = array(
			'icon'  => 'mdi mdi-cursor-move',
			'class' => 'handle',
			'title' => 'Sort',
		);

		$tools[ 'delete' ] = array(
			'icon'  => 'mdi mdi-delete',
			'title' => 'Delete',
		);

		$tools[ 'enable' ]   = array(
			'icon'  => 'mdi mdi-eye',
			'title' => 'Enabled',
		);
		$tools[ 'disable' ]  = array(
			'icon'  => 'mdi mdi-eye-off',
			'title' => 'Disabled',
		);
		$tools[ 'contents' ] = array(
			'icon'  => 'mdi mdi-view-column',
			'class' => 'show-contents',
			'title' => 'Column',
		);
		$tools[ 'settings' ] = array(
			'icon'  => 'mdi mdi-settings',
			'class' => 'show-settings',
			'title' => 'Settings',
		);
		$tools[ 'clone' ]    = array(
			'icon'  => 'mdi mdi-content-duplicate',
			'title' => 'Clone',
		);

		return $tools;
	} );


	add_filter( 'upb_row_contents_panel_toolbar', function ( $tools ) {
		$tools[] = array(
			'class' => 'grid-1-1',
			'value' => '1:1',
		);
		$tools[] = array(
			'class' => 'grid-1-2',
			'value' => '1:2 + 1:2',
		);
		$tools[] = array(
			'class' => 'grid-1-3__2-3',
			'value' => '1:3 + 2:3',
		);
		$tools[] = array(
			'class' => 'grid-2-3__1-3',
			'value' => '2:3 + 1:3',
		);
		$tools[] = array(
			'class' => 'grid-1-3__1-3__1-3',
			'value' => '1:3 + 1:3 + 1:3',
		);
		$tools[] = array(
			'class' => 'grid-1-4__2-4__1-4',
			'value' => '1:4 + 2:4 + 1:4',
		);
		$tools[] = array(
			'class' => 'grid-1-4__1-4__1-4__1-4',
			'value' => '1:4 + 1:4 + 1:4 + 1:4',
		);

		return $tools;
	} );

	// Register Tabs
	add_action( 'upb_register_tab', function ( $tab ) {


		$data = array(
			'title'    => 'Sections',
			'help'     => '<h2>Just Getting Starting?</h2><p>Add a section then click on it to manage column layouts or drag it to reorder.</p>',
			'search'   => 'Search Sections',
			'tools'    => apply_filters( 'upb_tab_sections_tools', array(
				                                                     array(
					                                                     'id'     => 'add-new-section',
					                                                     'title'  => 'Add Section',
					                                                     'icon'   => 'mdi mdi-package-variant',
					                                                     'action' => 'addNew',
					                                                     'data'   => apply_filters( 'upb_new_section_data',
					                                                                                upb_elements()->generate_element( 'section', upb_elements()->generate_element( 'row', upb_elements()->get_element( 'column' ), array(
						                                                                                'title' => array(
							                                                                                'type'  => 'text',
							                                                                                'value' => 'New Row 1'
						                                                                                )
					                                                                                ) ) ) )
				                                                     ),
				                                                     array(
					                                                     'id'     => 'load-sections',
					                                                     'title'  => 'Load Section',
					                                                     'icon'   => 'mdi mdi-cube-outline',
					                                                     'action' => 'openSavedSectionPanel'
				                                                     ),
				                                                     array(
					                                                     'id'     => 'saved-layouts',
					                                                     'title'  => 'Layouts',
					                                                     'icon'   => 'mdi mdi-view-quilt',
					                                                     'action' => 'openSavedLayoutPanel'
				                                                     ),
			                                                     )
			), // add section | load section | layouts
			'icon'     => 'mdi mdi-package-variant',
			'contents' => apply_filters( 'upb_sections_panel_contents', array() ), // load from get_post_meta
		);
		$tab->register( 'sections', $data, TRUE );


		/*$data = array(
			'title'    => 'Elements',
			'help'     => '<h2>Just Getting Starting?</h2><p>Add a section</p>',
			'tools'    => apply_filters( 'upb_tab_elements_tools', array() ), // add section | load section | layouts
			'icon'     => 'mdi mdi-shape-plus',
			'contents' => apply_filters( 'upb_tab_elements_contents', array() ),
		);
		$tab->register( 'elements', $data, FALSE );*/


		/*$data = array(
			'title'    => 'Settings',
			'help'     => '<p>Simply enable or disable page builder for this page or set other options.</p>',
			'tools'    => apply_filters( 'upb_tab_settings_tools', array() ), // add section | load section | layouts
			'icon'     => 'mdi mdi-settings',
			'contents' => apply_filters( 'upb_tab_settings_contents', array() ),
		);
		$tab->register( 'settings', $data, FALSE );*/


		/*$data = array(
			'title'    => 'Logical',
			'help'     => '<h2>Just Getting Starting?</h2><p>Add a section</p>',
			'tools'    => apply_filters( 'upb_tab_logical_tools', array() ), // add section | load section | layouts
			'icon'     => 'mdi mdi-json',
			'contents' => apply_filters( 'upb_tab_logical_contents', array() ),
		);
		$tab->register( 'logical', $data, FALSE );*/

	} );


	// Load CSS :)
	add_action( 'upb_boilerplate_print_styles', function () {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		//wp_enqueue_style( 'upb-styles', UPB_PLUGIN_ASSETS_URL . "css/upb-style$suffix.css" );
		wp_enqueue_style( 'upb-boilerplate', UPB_PLUGIN_ASSETS_URL . "css/upb-boilerplate$suffix.css" );
	} );

	add_action( 'upb_boilerplate_print_scripts', function () {
		//$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	} );


	add_action( 'upb_boilerplate_enqueue_scripts', function () {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'upb-script', UPB_PLUGIN_ASSETS_URL . "js/upb-build$suffix.js", array( 'jquery-ui-sortable', 'wp-util' ), '', TRUE );
		wp_enqueue_script( 'upb-boilerplate-script', UPB_PLUGIN_ASSETS_URL . "js/upb-boilerplate-script$suffix.js", array( 'jquery', 'upb-script' ), '', TRUE );


		$data = sprintf( "var _upb_tabs = %s;\n", upb_tabs()->getJSON() );

		$data .= sprintf( "var _upb_status = %s;\n", wp_json_encode( array( 'dirty' => FALSE, '_nonce' => wp_create_nonce( '_upb' ), '_id' => get_the_ID() ) ) );

		$data .= sprintf( "var _upb_settings = %s;", upb_settings()->getJSON() );

		$data .= sprintf( "var _upb_preview_devices = %s;", wp_json_encode( apply_filters( 'upb_preview_devices', array() ) ) );

		$data .= sprintf( "var _upb_grid_system = %s;", wp_json_encode( apply_filters( 'upb_grid_system', array() ) ) );

		wp_script_add_data( 'upb-script', 'data', $data );

		wp_localize_script( 'upb-script', '_upb_l10n',
		                    apply_filters( '_upb_l10n_strings',
		                                   array(
			                                   'save'           => esc_attr__( 'Save' ),
			                                   'create'         => esc_attr__( 'Create' ),
			                                   'delete'         => esc_attr__( 'Are you sure to delete %s?' ),
			                                   'column_manual'  => esc_attr__( 'Manual' ),
			                                   'column_layout'  => esc_attr__( 'Column Layout of - %s' ),
			                                   'column_sort'    => esc_attr__( 'Column Sort - %s' ),
			                                   'close'          => esc_attr__( 'Close' ),
			                                   'help'           => esc_attr__( 'Help' ),
			                                   'search'         => esc_attr__( 'Search' ),
			                                   'back'           => esc_attr__( 'Back' ),
			                                   'breadcrumbRoot' => esc_attr__( 'You are building' ),
			                                   'skeleton'       => esc_attr__( 'Skeleton preview' ),
			                                   'collapse'       => esc_attr__( 'Collapse' ),
			                                   'expand'         => esc_attr__( 'Expand' ),
			                                   //'large'          => esc_attr__( 'Large device preview' ),
			                                   //'medium'         => esc_attr__( 'Medium device preview' ),
			                                   //'small'          => esc_attr__( 'Small device preview' ),
			                                   //'xSmall'         => esc_attr__( 'Extra small preview' ),
		                                   )
		                    )
		);
	} );

	add_action( 'upb_boilerplate_print_scripts', function () {
		//$tabs = upb_tabs()->getAll();
		//printf( '<script>var _upb_tabs = %s;</script>', wp_json_encode( $tabs ) );
	} );



