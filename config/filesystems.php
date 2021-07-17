<?php

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => "/data/data/com.termux/files/usr/var/webzone",
        ],
        'projects' => [
            'driver' => 'local',
            'root' => "/sdcard/www",
        ],
        'tmp' => [
            'driver' => 'local',
            'root' => "/data/data/com.termux/files/usr/var/webzone/tmp",
        ],
    ],
];
