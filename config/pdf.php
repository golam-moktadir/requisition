<?php

return [
    'mode'                     => '',
    'format'                   => 'A4',
    'default_font_size'        => '12',
    'default_font'             => 'solaimanlipi',
    'margin_left'              => 5,
    'margin_right'             => 5,
    'margin_top'               => 25,
    'margin_bottom'            => 18,
    'margin_header'            => 1,
    'margin_footer'            => 1,
    'orientation'              => 'P',
    'title'                    => 'mPDF',
    'subject'                  => '',
    'author'                   => '',
    'watermark'                => '',
    'show_watermark'           => false,
    'show_watermark_image'     => false,
    'watermark_font'           => 'solaimanlipi',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => '',
    'watermark_image_alpha'    => 0.2,
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',
    'auto_language_detection'  => false,
    'temp_dir'                 => storage_path('app'),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
    'custom_font_dir'          => base_path('resources/fonts'),
    'custom_font_data'         => [
        'solaimanlipi' => [ // must be lowercase and snake_case
            'R'  => 'SolaimanLipi.ttf',    // regular font
            'B'  => 'SolaimanLipi-Bold.ttf',       // optional: bold font
            'I'  => 'SolaimanLipi-Italic.ttf',     // optional: italic font
            // 'BI' => 'SolaimanLipi-Bold-Italic.ttf' // optional: bold-italic font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],  
        'kalpurush' => [ // must be lowercase and snake_case
            'R'  => 'kalpurush.ttf',    // regular font
            'I'  => 'SolaimanLipi-Italic.ttf',     // optional: italic font
        ],        
    ],
];
