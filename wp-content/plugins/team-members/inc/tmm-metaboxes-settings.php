<?php

/* Defines force font select options. */
function dmb_tmm_force_fonts_options()
{
    $options = [
        __('Plugin', 'team-members') => 'yes',
        __('Theme', 'team-members') => 'no',
    ];

    return $options;
}

/* Defines picture link behavior options. */
function dmb_tmm_piclink_beh_options()
{
    $options = [
        __('New window', 'team-members') => 'new',
        __('Same window', 'team-members') => 'same',
    ];

    return $options;
}

/**
 * Defines display order options.
 *
 * @since 5.1.1
 *
 * @return array options
 */
function dmb_tmm_display_order_options()
{
    $options = [
        __('Default', 'team-members') => 'default',
        __('Random', 'team-members') => 'random',
    ];

    return $options;
}

/* Defines bio alignment options. */
function dmb_tmm_bio_align_options()
{
    $options = [
        __('Center', 'team-members') => 'center',
        __('Left', 'team-members') => 'left',
        __('Right', 'team-members') => 'right',
        __('Justify', 'team-members') => 'justify',
    ];

    return $options;
}

/* Defines team columns options. */
function dmb_tmm_columns_options()
{
    $options = [
        __('1 per line', 'team-members') => '1',
        __('2 per line', 'team-members') => '2',
        __('3 per line', 'team-members') => '3',
        __('4 per line', 'team-members') => '4',
        __('5 per line', 'team-members') => '5',
    ];

    return $options;
}

/* Hooks the metabox. */
add_action('admin_init', 'dmb_tmm_add_settings', 1);
function dmb_tmm_add_settings()
{
    add_meta_box(
        'tmm_settings',
        'Settings',
        'dmb_tmm_settings_display',
        'tmm',
        'side',
        'high'
    );
}

/* Displays the metabox. */
function dmb_tmm_settings_display()
{
    global $post;

    /* Retrieves select options. */
    $team_columns = dmb_tmm_columns_options();
    $team_bio_align = dmb_tmm_bio_align_options();
    $team_piclink_beh = dmb_tmm_piclink_beh_options();
    $team_display_order = dmb_tmm_display_order_options();
    $team_force_font = dmb_tmm_force_fonts_options();

    /* Processes retrieved fields. */
    $settings = [];

    $settings['_tmm_columns'] = get_post_meta($post->ID, '_tmm_columns', true);
    if (!$settings['_tmm_columns']) {
        $settings['_tmm_columns'] = '3';
    }

    $settings['_tmm_color'] = get_post_meta($post->ID, '_tmm_color', true);
    if (!$settings['_tmm_color']) {
        $settings['_tmm_color'] = '#333333';
    }

    $settings['_tmm_bio_alignment'] = get_post_meta($post->ID, '_tmm_bio_alignment', true);

    /* Checks if member links open in new window. */
    $settings['_tmm_piclink_beh'] = get_post_meta($post->ID, '_tmm_piclink_beh', true);
    'new' == $settings['_tmm_piclink_beh'] ? $tmm_plb = 'target="_blank"' : $tmm_plb = '';

    /* Checks if forcing original fonts. */
    $settings['_tmm_original_font'] = get_post_meta($post->ID, '_tmm_original_font', true);
    if (!$settings['_tmm_original_font']) {
        $settings['_tmm_original_font'] = 'yes';
    }

    /* Checks display order settings. */
    $settings['_tmm_display_order'] = get_post_meta($post->ID, '_tmm_display_order', true);
    if (!$settings['_tmm_display_order']) {
        $settings['_tmm_display_order'] = 'default';
    }

?>

<div class="dmb_settings_box dmb_sidebar">

    <div class="dmb_section_title">
        <?php /* translators: General settings */ esc_html_e('General', 'team-members'); ?>
    </div>

    <!-- Team layout -->
    <div class="dmb_grid dmb_grid_50 dmb_grid_first">
        <div class="dmb_field_title">
            <?php esc_html_e('Members per line', 'team-members'); ?>
        </div>
        <select class="dmb_side_select" name="team_columns">
            <?php foreach ($team_columns as $label => $value) { ?>
            <option value="<?php echo esc_attr($value); ?>"
                <?php selected((isset($settings['_tmm_columns'])) ? $settings['_tmm_columns'] : '3', $value); ?>>
                <?php echo esc_attr($label); ?>
            </option>
            <?php } ?>
        </select>
    </div>

    <!-- Photo link behavior -->
    <div class="dmb_grid dmb_grid_50 dmb_grid_last">
        <div class="dmb_field_title">
            <?php esc_html_e('Photo link behavior', 'team-members'); ?>
        </div>
        <select class="dmb_side_select" name="team_piclink_beh">
            <?php foreach ($team_piclink_beh as $label => $value) { ?>
            <option value="<?php echo esc_attr($value); ?>"
                <?php selected((isset($settings['_tmm_piclink_beh'])) ? $settings['_tmm_piclink_beh'] : 'new', $value); ?>>
                <?php echo esc_attr($label); ?>
            </option>
            <?php } ?>
        </select>
    </div>

    <!-- Display order -->
    <div class="dmb_grid dmb_grid_50 dmb_grid_first">
        <div class="dmb_field_title">
            <?php esc_html_e('Display order', 'team-members'); ?>
            <a class="dmb_inline_tip dmb_tooltip_small"
                data-tooltip="<?php esc_attr_e('Order in which your team members will be sorted when displayed.', 'team-members'); ?>">
                [?]
            </a>
        </div>
        <select class="dmb_side_select" name="team_display_order">
            <?php foreach ($team_display_order as $label => $value) { ?>
            <option value="<?php echo esc_attr($value); ?>"
                <?php selected((isset($settings['_tmm_display_order'])) ? $settings['_tmm_display_order'] : 'default', $value); ?>>
                <?php echo esc_attr($label); ?>
            </option>
            <?php } ?>
        </select>
    </div>

    <!-- Font option -->
    <div class="dmb_grid dmb_grid_50 dmb_grid_last">
        <div class="dmb_field_title">
            <?php esc_html_e('Fonts to use', 'team-members'); ?>
        </div>
        <select class="dmb_side_select" name="team_force_font">
            <?php foreach ($team_force_font as $label => $value) { ?>
            <option value="<?php echo esc_attr($value); ?>"
                <?php selected((isset($settings['_tmm_original_font'])) ? $settings['_tmm_original_font'] : 'yes', $value); ?>>
                <?php echo esc_attr($label); ?>
            </option>
            <?php } ?>
        </select>
    </div>

    <!-- Main color -->
    <div class="dmb_color_of_team dmb_grid dmb_grid_100 dmb_grid_first dmb_grid_last">
        <div class="dmb_field_title">
            <?php esc_html_e('Main color', 'team-members'); ?>
        </div>
        <input class="dmb_color_picker dmb_field dmb_color_of_team" name="team_color" type="text"
            value="<?php echo (isset($settings['_tmm_color'])) ? esc_attr($settings['_tmm_color']) : '#333333'; ?>" />
    </div>

    <div class="dmb_clearfix"></div>

</div>

<?php } ?>