<?php


return [
    'mode'                 => '',
    'format'               => 'A4',
    'default_font_size'    => '12',
//    'default_font'         => 'sans-serif',
    'font_path' => base_path('resources/fonts/kalpurush.ttf'),
    'font_data' => [
        'examplefont' => [
            'R'  => 'kalpurush.ttf',    // regular font
            'B'  => 'kalpurush.ttf',       // optional: bold font
            'I'  => 'kalpurush.ttf',     // optional: italic font
            'BI' => 'kalpurush.ttf' // optional: bold-italic font
            //'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            //'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ]
        // ...add as many as you want.
    ],
    'margin_left'          => 10,
    'margin_right'         => 10,
    'margin_top'           => 10,
    'margin_bottom'        => 10,
    'margin_header'        => 0,
    'margin_footer'        => 0,
    'orientation'          => 'P',
    'title'                => 'Laravel mPDF',
    'author'               => '',
    'watermark'            => '',
    'show_watermark'       => false,
    'watermark_font'       => 'sans-serif',
    'display_mode'         => 'fullpage',
    'watermark_text_alpha' => 0.1
];