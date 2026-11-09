<?php

/* Enqueues admin scripts. */
add_action('admin_enqueue_scripts', 'add_admin_tmm_style');
function add_admin_tmm_style()
{
    /* Gets the post type. */
    global $post_type;

    if ('tmm' == $post_type) {
        /* CSS for metaboxes. */
        wp_enqueue_style('tmm_dmb_styles', plugins_url('dmb/dmb.min.css', __FILE__));
        /* CSS for preview.s */
        wp_enqueue_style('tmm_styles', plugins_url('css/tmm_style.min.css', __FILE__));
        /* Others. */
        wp_enqueue_style('wp-color-picker');

        /* Ensure media library is available for image uploader. */
        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        }

        /* Ensure the WordPress editor assets are loaded even if no editors exist initially. */
        if (function_exists('wp_enqueue_editor')) {
            wp_enqueue_editor();
        }

        /* JS for metaboxes. Ensure editor scripts load before our code. */
        wp_enqueue_script('tmm', plugins_url('dmb/dmb.js', __FILE__), ['jquery', 'thickbox', 'wp-color-picker', 'editor', 'wp-editor']);

        /* Localizes string for JS file. */
        wp_localize_script('tmm', 'objectL10n', [
          'untitled' => __('Untitled', 'team-members'),
          'copy' => __('copy', 'team-members'),
          'noMemberNotice' => __('Add at least <strong>1</strong> member to preview the team.', 'team-members'),
          'previewAccuracy' => __('This is only a preview, shortcodes used in the fields will not be rendered and results may vary depending on your container\'s width.', 'team-members'),
        ]);
        wp_enqueue_style('thickbox');
    }
}