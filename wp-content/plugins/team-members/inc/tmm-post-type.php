<?php

/* Registers the teams post type. */
add_action('init', 'register_tmm_type');
function register_tmm_type()
{

	/* Defines labels. */
	$labels = array(
		'name'               => __('Teams', 'team-members'),
		'singular_name'      => __('Team', 'team-members'),
		'menu_name'          => __('Teams', 'team-members'),
		'name_admin_bar'     => __('Team', 'team-members'),
		'add_new'            => __('Add New', 'team-members'),
		'add_new_item'       => __('Add New Team', 'team-members'),
		'new_item'           => __('New Team', 'team-members'),
		'edit_item'          => __('Edit Team', 'team-members'),
		'view_item'          => __('View Team', 'team-members'),
		'all_items'          => __('All Teams', 'team-members'),
		'search_items'       => __('Search Teams', 'team-members'),
		'not_found'          => __('No Teams found.', 'team-members'),
		'not_found_in_trash' => __('No Teams found in Trash.', 'team-members')
	);

	/* Defines permissions. */
	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_admin_bar'  => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => array('title'),
		'menu_icon'          => 'dashicons-plus'
	);

	/* Registers post type. */
	register_post_type('tmm', $args);
}

/* Customizes teams update messages. */
add_filter('post_updated_messages', 'tmm_updated_messages');
function tmm_updated_messages($messages)
{
	$post             = get_post();
	$post_type        = get_post_type($post);
	$post_type_object = get_post_type_object($post_type);

	/* Defines update messages. */
	$messages['tmm'] = array(
		1  => __('Team updated.', 'team-members'),
		4  => __('Team updated.', 'team-members'),
		6  => __('Team published.', 'team-members'),
		7  => __('Team saved.', 'team-members'),
		10 => __('Team draft updated.', 'team-members')
	);

	return $messages;
}