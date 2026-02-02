<?php

return [

    'temporary_file_upload' => [
        'disk' => 'public',  // usa el disco público
        'directory' => 'livewire-tmp',
        'middleware' => 'throttle:60,1',
        'preview_mimes' => [
            'png', 'jpg', 'jpeg', 'webp', 'gif', 'bmp', 'svg',
            'mp4', 'mov', 'avi', 'mkv', 'mp3', 'wav', 'pdf'
        ],
        'max_upload_time' => 5, // minutos
    ],

];
