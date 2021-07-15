<?php

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => "/data/data/com.termux/files/usr/var/webzone",
        ],
        'tmp' => [
            'driver' => 'local',
            'root' => "/data/data/com.termux/files/usr/var/webzone/tmp",
        ],
    ],
];
