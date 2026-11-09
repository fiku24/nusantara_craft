<?php

/* Defines highlight select options. */
function dmb_tmm_social_links_options()
{
    $options = [
        __('-', 'team-members') => 'nada',
        __('X (Twitter)', 'team-members') => 'twitter',
        __('LinkedIn', 'team-members') => 'linkedin',
        __('YouTube', 'team-members') => 'youtube',
        __('WhatsApp', 'team-members') => 'whatsapp',
        __('Messenger', 'team-members') => 'messenger',
        __('WeChat', 'team-members') => 'wechat',
        __('Facebook', 'team-members') => 'facebook',
        __('Pinterest', 'team-members') => 'pinterest',
        __('VK', 'team-members') => 'vk',
        __('Instagram', 'team-members') => 'instagram',
        __('Tumblr', 'team-members') => 'tumblr',
        __('Research Gate', 'team-members') => 'researchgate',
        __('Email', 'team-members') => 'email',
        __('Website', 'team-members') => 'website',
        __('Phone', 'team-members') => 'phone',
        __('Other links', 'team-members') => 'customlink',
        __('Google+ (deprecated)', 'team-members') => 'googleplus',
    ];

    return $options;
}

/* Hooks the metabox. */
add_action('admin_init', 'dmb_tmm_add_team', 1);
function dmb_tmm_add_team()
{
    add_meta_box(
        'tmm',
        __('Manage your team', 'team-members'),
        'dmb_tmm_team_display', // Below
        'tmm',
        'normal',
        'high'
    );
}

/* Displays the metabox. */
function dmb_tmm_team_display()
{
    global $post;

    /* Gets team data. */
    $team = get_post_meta($post->ID, '_tmm_head', true);

    $fields_to_process = [
        '_tmm_firstname',
        '_tmm_lastname',
        '_tmm_job',
        '_tmm_desc',
        '_tmm_sc_type1', '_tmm_sc_title1', '_tmm_sc_url1',
        '_tmm_sc_type2', '_tmm_sc_title2', '_tmm_sc_url2',
        '_tmm_sc_type3', '_tmm_sc_title3', '_tmm_sc_url3',
        '_tmm_photo',
        '_tmm_photo_url',
    ];

    /* Retrieves select options. */
    $social_links_options = dmb_tmm_social_links_options();

    wp_nonce_field('dmb_tmm_meta_box_nonce', 'dmb_tmm_meta_box_nonce'); ?>

<div id="dmb_preview_team">
    <!-- Closes preview button. -->
    <a class="dmb_button dmb_button_huge dmb_button_gold dmb_preview_team_close" href="#">
        <?php esc_html_e('Close preview', 'team-members'); ?>
    </a>
</div>

<!-- Toolbar for member metabox -->
<div class="dmb_toolbar">
    <a class="dmb_button dmb_button_large dmb_expand_rows" href="#"><span
            class="dashicons dashicons-editor-expand"></span>
        <?php esc_html_e('Expand all', 'team-members'); ?></a>
    <a class="dmb_button dmb_button_large dmb_collapse_rows" href="#"><span
            class="dashicons dashicons-editor-contract"></span>
        <?php esc_html_e('Collapse all', 'team-members'); ?></a>
    <a
        class="dmb_show_preview_team dmb_button dmb_button_huge dmb_button_gold"><?php esc_html_e('Instant preview', 'team-members'); ?></a>
    <div class="dmb_clearfix"></div>
</div>

<?php if ($team) {
    /* Loops through rows. */
    $member_index = 0;
    foreach ($team as $team_member) {
        /* Retrieves each field for current member. */
        $member = [];
        foreach ($fields_to_process as $field) {
            switch ($field) {
                default:
                    $member[$field] = (isset($team_member[$field])) ? $team_member[$field] : '';
                    break;
            }
        }
        $editor_id = 'dmb_bio_editor_' . $member_index;
        $member_index++;
        ?>

<!-- START member -->
<div class="dmb_main">

    <textarea class="dmb_data_dump" name="tmm_data_dumps[]"></textarea>

    <!-- Member handle bar -->
    <div class="dmb_handle">
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_move_row_up" href="#" title="Move up"><span
                class="dashicons dashicons-arrow-up-alt2"></span></a>
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_move_row_down" href="#" title="Move down"><span
                class="dashicons dashicons-arrow-down-alt2"></span></a>
        <div class="dmb_handle_title"></div>
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_remove_row_btn" href="#" title="Remove"><span
                class="dashicons dashicons-trash"></span></a>
        <a class="dmb_button dmb_button_large dmb_clone_row" href="#" title="Clone"><span
                class="dashicons dashicons-admin-page"></span><?php esc_html_e('Clone', 'team-members'); ?></a>
        <div class="dmb_clearfix"></div>
    </div>

    <!-- START inner -->
    <div class="dmb_inner">

        <div class="dmb_section_title">
            <?php esc_html_e('Member details', 'team-members'); ?>
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <div class="dmb_field_title">
                <?php esc_html_e('First name', 'team-members'); ?>
            </div>
            <input class="dmb_field dmb_highlight_field dmb_firstname_of_member" type="text"
                value="<?php echo esc_attr($member['_tmm_firstname']); ?>"
                placeholder="<?php esc_attr_e('e.g. John', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 ">
            <div class="dmb_field_title">
                <?php esc_html_e('Lastname', 'team-members'); ?>
            </div>
            <input class="dmb_field dmb_lastname_of_member" type="text"
                value="<?php echo esc_attr($member['_tmm_lastname']); ?>"
                placeholder="<?php esc_attr_e('e.g. Doe', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <div class="dmb_field_title">
                <?php esc_html_e('Job/role', 'team-members'); ?>
            </div>
            <input class="dmb_field dmb_job_of_member" type="text" value="<?php echo esc_attr($member['_tmm_job']); ?>"
                placeholder="<?php esc_attr_e('e.g. Project manager', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_100 dmb_grid_first dmb_grid_last">

            <div class="dmb_field_title">
                <?php esc_html_e('Description/biography', 'team-members'); ?>
            </div>

            <div class="dmb_bio_editor_wrapper">
                <?php
                $editor_settings = [
                    'textarea_name' => '',
                    'textarea_rows' => 6,
                    'editor_height' => 150,
                    'teeny' => true,
                    'quicktags' => false,
                    'media_buttons' => false,
                ];
                wp_editor($member['_tmm_desc'], $editor_id, $editor_settings);
                ?>
            </div>

            <div class="dmb_clearfix"></div>

        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_section_title">
            <?php esc_html_e('Social links', 'team-members'); ?>
            <a class="dmb_inline_tip dmb_tooltip_large"
                data-tooltip="<?php esc_attr_e('These links will appear below your members\' biography.', 'team-members'); ?>">[?]</a>
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <div class="dmb_field_title">
                <?php esc_html_e('Link type', 'team-members'); ?>
            </div>
            <select class="dmb_scl_type_select dmb_scl_type1_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($member['_tmm_sc_type1'], $value); ?>>
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33">
            <div class="dmb_field_title">
                <?php esc_html_e('Title attribute', 'team-members'); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('Optional. This is the HTML <a> tag\'s title attribute.', 'team-members'); ?>">[?]</a>
            </div>
            <input class="dmb_field dmb_scl_title1_of_member" type="text"
                value="<?php echo esc_attr($member['_tmm_sc_title1']); ?>"
                placeholder="<?php esc_attr_e('e.g. Facebook page', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <div class="dmb_field_title">
                <?php esc_attr_e('Link URL', 'team-members'); ?>
            </div>
            <input class="dmb_field dmb_scl_url1_of_member" type="text"
                value="<?php echo esc_attr($member['_tmm_sc_url1']); ?>"
                placeholder="<?php esc_attr_e('e.g. http://fb.com/member-profile', 'team-members'); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <select class="dmb_scl_type_select dmb_scl_type2_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($member['_tmm_sc_type2'], $value); ?>>
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33 ">
            <input class="dmb_field dmb_scl_title2_of_member" type="text"
                value="<?php echo esc_attr($member['_tmm_sc_title2']); ?>"
                placeholder="<?php esc_attr_e('e.g. Facebook page', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <input class="dmb_field dmb_scl_url2_of_member" type="text"
                value="<?php echo esc_attr($member['_tmm_sc_url2']); ?>"
                placeholder="<?php esc_attr_e('e.g. http://fb.com/member-profile', 'team-members'); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first dmb_grid_first">
            <select class="dmb_scl_type_select dmb_scl_type3_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($member['_tmm_sc_type3'], $value); ?>>
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33 ">
            <input class="dmb_field dmb_scl_title3_of_member" type="text"
                value="<?php echo esc_attr($member['_tmm_sc_title3']); ?>"
                placeholder="<?php esc_attr_e('e.g. Google+ page', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <input class="dmb_field dmb_scl_url3_of_member" type="text"
                value="<?php echo esc_attr($member['_tmm_sc_url3']); ?>"
                placeholder="<?php esc_attr_e('e.g. http://gp.com/member-profile', 'team-members'); ?>" />
        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_tip">
            <span class="dashicons dashicons-yes"></span>
            <?php esc_html_e('Links with the email type open your visitors\' mail client.', 'team-members'); ?>
            <a class="dmb_inline_tip dmb_tooltip_large"
                data-tooltip="<?php esc_attr_e('Your member\'s email address must be entered in the Link URL field. Title attribute can be left blank.', 'team-members'); ?>">[?]</a>
            <br /><span class="dashicons dashicons-yes"></span>
            <?php esc_html_e('Links with the phone type open your visitors\' default phone app.', 'team-members'); ?>
            <a class="dmb_inline_tip dmb_tooltip_large"
                data-tooltip="<?php esc_attr_e('Your member\'s phone number must be entered in the Link URL field (e.g. tel:+11234567890). Title attribute can be left blank.', 'team-members'); ?>">[?]</a>
        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_section_title">
            <?php esc_html_e('Photo', 'team-members'); ?>
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">

            <div class="dmb_field_title">
                <?php esc_html_e('Member\'s photo', 'team-members'); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('We recommend that all photos are the same sizes.', 'team-members'); ?>">[?]</a>
            </div>

            <div class="dmb_photo_of_member">
                <div class="dmb_field_title dmb_img_data_url" data-img="<?php echo esc_attr($member['_tmm_photo']); ?>">
                </div>
                <div class="dmb_upload_img_btn dmb_button dmb_button_large dmb_button_blue">
                    <?php esc_html_e('Upload photo', 'team-members'); ?>
                </div>
            </div>

        </div>

        <div class="dmb_grid dmb_grid_100 dmb_grid_first dmb_grid_last" style="margin-top:7px;">
            <div class="dmb_field_title">
                <?php esc_html_e('Photo link', 'team-members'); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('Your visitors will be redirected to this link if they click the member\'s photo.', 'team-members'); ?>">[?]</a>
            </div>
            <input class="dmb_field dmb_photo_url_of_member" type="text"
                value="<?php echo esc_attr($member['_tmm_photo_url']); ?>"
                placeholder="<?php esc_attr_e('e.g. http://your-site.com/full-member-page/', 'team-members'); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <!-- END inner -->
    </div>

    <!-- END row -->
</div>

<?php
    }
} ?>

<!-- START empty member -->
<div class="dmb_main dmb_empty_row" style="display:none;">

    <textarea class="dmb_data_dump" name="tmm_data_dumps[]"></textarea>

    <!-- Member handle bar -->
    <div class="dmb_handle">
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_move_row_up" href="#" title="Move up"><span
                class="dashicons dashicons-arrow-up-alt2"></span></a>
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_move_row_down" href="#" title="Move down"><span
                class="dashicons dashicons-arrow-down-alt2"></span></a>
        <div class="dmb_handle_title"></div>
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_remove_row_btn" href="#" title="Remove"><span
                class="dashicons dashicons-trash"></span></a>
        <a class="dmb_button dmb_button_large dmb_clone_row" href="#" title="Clone"><span
                class="dashicons dashicons-admin-page"></span><?php esc_html_e('Clone', 'team-members'); ?></a>
        <div class="dmb_clearfix"></div>
    </div>

    <!-- START inner -->
    <div class="dmb_inner">

        <div class="dmb_section_title">
            <?php esc_html_e('Member details', 'team-members'); ?>
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <div class="dmb_field_title">
                <?php esc_html_e('First name', 'team-members'); ?>
            </div>
            <input class="dmb_field dmb_highlight_field dmb_firstname_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. John', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 ">
            <div class="dmb_field_title">
                <?php esc_html_e('Lastname', 'team-members'); ?>
            </div>
            <input class="dmb_field dmb_lastname_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. Doe', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <div class="dmb_field_title">
                <?php esc_html_e('Job/role', 'team-members'); ?>
            </div>
            <input class="dmb_field dmb_job_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. Project manager', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_100 dmb_grid_first dmb_grid_last">

            <div class="dmb_field_title">
                <?php esc_html_e('Description/biography', 'team-members'); ?>
            </div>

            <div class="dmb_bio_editor_wrapper">
                <textarea class="dmb_bio_textarea_empty" style="width:100%; min-height:150px;"></textarea>
            </div>

            <div class="dmb_clearfix"></div>

        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_section_title">
            <?php esc_html_e('Social links', 'team-members'); ?>
            <a class="dmb_inline_tip dmb_tooltip_large"
                data-tooltip="<?php esc_attr_e('These links will appear below your members\' biography.', 'team-members'); ?>">[?]</a>
        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <div class="dmb_field_title">
                <?php esc_html_e('Link type', 'team-members'); ?>
            </div>

            <select class="dmb_scl_type_select dmb_scl_type1_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo wp_kses_post($value); ?>">
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33">
            <div class="dmb_field_title">
                <?php esc_html_e('Title attribute', 'team-members'); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('Optional. This is the HTML <a> tag\'s title attribute.', 'team-members'); ?>">[?]</a>
            </div>
            <input class="dmb_field dmb_scl_title1_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. Facebook page', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <div class="dmb_field_title">
                <?php esc_html_e('Link URL', 'team-members'); ?>
            </div>
            <input class="dmb_field dmb_scl_url1_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. http://fb.com/member-profile', 'team-members'); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <select class="dmb_scl_type_select dmb_scl_type2_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo wp_kses_post($value); ?>">
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33">
            <input class="dmb_field dmb_scl_title2_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. Facebook page', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <input class="dmb_field dmb_scl_url2_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. http://fb.com/member-profile', 'team-members'); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <select class="dmb_scl_type_select dmb_scl_type3_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo wp_kses_post($value); ?>">
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33">
            <input class="dmb_field dmb_scl_title3_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. Google+ page', 'team-members'); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <input class="dmb_field dmb_scl_url3_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. http://gp.com/member-profile', 'team-members'); ?>" />
        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_tip">
            <span class="dashicons dashicons-yes"></span> Links with the email type open your visitors' mail client. <a
                class="dmb_inline_tip dmb_tooltip_large"
                data-tooltip="<?php esc_attr_e('Your member\'s email address must be entered in the Link URL field. Title attribute can be left blank.', 'team-members'); ?>">[?]</a>
        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_section_title">
            <?php esc_html_e('Photo', 'team-members'); ?>
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">

            <div class="dmb_field_title">
                <?php esc_html_e('Member\'s photo', 'team-members'); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('We recommend that all photos are the same sizes.', 'team-members'); ?>">[?]</a>
            </div>

            <div class="dmb_photo_of_member">
                <div class="dmb_field_title dmb_img_data_url" data-img=""></div>
                <div class="dmb_upload_img_btn dmb_button dmb_button_large dmb_button_blue">
                    <?php esc_html_e('Upload photo', 'team-members'); ?>
                </div>
            </div>

        </div>

        <div class="dmb_grid dmb_grid_100 dmb_grid_first dmb_grid_last" style="margin-top:7px;">
            <div class="dmb_field_title">
                <?php esc_html_e('Photo link', 'team-members'); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('Your visitors will be redirected to this link if they click the member\'s photo.', 'team-members'); ?>">[?]</a>
            </div>
            <input class="dmb_field dmb_photo_url_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. http://your-site.com/full-member-page/', 'team-members'); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <!-- END inner -->
    </div>

    <!-- END empty row -->
</div>

<div class="dmb_clearfix"></div>

<div class="dmb_no_row_notice">
    <?php /* translators: Leave HTML tags */ esc_html_e('Click the Add a member button below to get started.', 'team-members'); ?>
</div>

<!-- Add row button -->
<a class="dmb_button dmb_button_huge dmb_button_green dmb_add_row" href="#">
    <?php esc_html_e('Add a member', 'team-members'); ?>
</a>

<?php }
?>