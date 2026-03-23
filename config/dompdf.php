<?php

return [

    'show_warnings' => false,
    'public_path'   => null,
    'convert_entities' => true,

    'options' => [

        "font_dir"   => storage_path('fonts'),
        "font_cache" => storage_path('fonts'),
        "temp_dir"   => sys_get_temp_dir(),
        "chroot"     => realpath(base_path()),

        "enable_font_subsetting" => true,
        "pdf_backend"            => "CPDF",

        "font_family" => [
            "serif" => "Times-Roman",
            "sans-serif" => "Helvetica",
            "monospace" => "Courier",

            // Unicode Myanmar font
            "notosansmyanmar" => [
                "normal"      => storage_path('fonts/NotoSansMyanmar-Regular.ttf'),
                "bold"        => storage_path('fonts/NotoSansMyanmar-Regular.ttf'),
                "italic"      => storage_path('fonts/NotoSansMyanmar-Regular.ttf'),
                "bold_italic" => storage_path('fonts/NotoSansMyanmar-Regular.ttf'),
            ],
        ],

        "default_font" => "notosansmyanmar",

        "dpi" => 96,
        "enable_php" => false,
        "enable_javascript" => true,
        "enable_remote" => true,
        "font_height_ratio" => 1.1,
        "enable_html5_parser" => true,
    ],
];
