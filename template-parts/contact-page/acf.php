<?php

/**
 * ACF Field Group: Contact Page
 * Đăng ký các trường ACF cho trang Liên hệ
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('acf/init', 'cosplay_register_contact_page_fields');

function cosplay_register_contact_page_fields()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'      => 'group_contact_page',
        'title'    => 'Contact Page',
        'fields'   => [

            // 1. Tiêu đề trang
            [
                'key'           => 'field_contact_title',
                'label'         => 'Tiêu đề trang',
                'name'          => 'contact_title',
                'type'          => 'text',
                'default_value' => 'Liên hệ với chúng tôi',
            ],

            // 2. Giờ làm việc
            [
                'key'           => 'field_contact_working_hours',
                'label'         => 'Giờ làm việc',
                'name'          => 'contact_working_hours',
                'type'          => 'text',
                'default_value' => '6h → 20h (Thứ 2 → Thứ 7)',
            ],

            // 3. Địa chỉ
            [
                'key'           => 'field_contact_address',
                'label'         => 'Địa chỉ',
                'name'          => 'contact_address',
                'type'          => 'textarea',
                'default_value' => '31 Trần Kim Xuyến, Yên Hoà, Cầu Giấy, Hà Nội, Việt Nam',
                'rows'          => 3,
                'new_lines'     => 'br',
            ],

            // 4. Mạng xã hội (repeater)
            [
                'key'        => 'field_contact_social_media',
                'label'      => 'Mạng xã hội',
                'name'       => 'contact_social_media',
                'type'       => 'repeater',
                'min'        => 1,
                'max'        => 6,
                'layout'     => 'table',
                'sub_fields' => [
                    [
                        'key'     => 'field_contact_social_platform',
                        'label'   => 'Nền tảng',
                        'name'    => 'platform',
                        'type'    => 'select',
                        'choices' => [
                            'facebook'  => 'Facebook',
                            'tiktok'    => 'TikTok',
                            'instagram' => 'Instagram',
                            'youtube'   => 'YouTube',
                        ],
                    ],
                    [
                        'key'   => 'field_contact_social_name',
                        'label' => 'Tên hiển thị',
                        'name'  => 'name',
                        'type'  => 'text',
                    ],
                    [
                        'key'   => 'field_contact_social_url',
                        'label' => 'Đường dẫn',
                        'name'  => 'url',
                        'type'  => 'url',
                    ],
                    [
                        'key'           => 'field_contact_social_image',
                        'label'         => 'Hình ảnh',
                        'name'          => 'image',
                        'type'          => 'image',
                        'return_format' => 'id',
                        'preview_size'  => 'thumbnail',
                    ],
                ],
            ],

            // 5. CF7 Form ID
            [
                'key'          => 'field_contact_cf7_form_id',
                'label'        => 'CF7 Form ID',
                'name'         => 'contact_cf7_form_id',
                'type'         => 'number',
                'instructions' => 'ID của Contact Form 7 form (từ WP Admin > Contact > Forms)',
            ],

        ],
        'location' => [
            [
                [
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'page-contact.php',
                ],
            ],
        ],
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ]);
}
