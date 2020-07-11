<?php

namespace system;
use \system\Database;

class Menu extends Database
{
    public $fitures = [
        'help_info' => [
            [
                'command' => '/help',
                'args' => '',
                'description' => 'Menampilkan command / informasi',
            ], [
                'command' => '/hits',
                'args' => '',
                'description' => 'Menampilkan total hits & status command',
            ]
        ],
        'admin' => [
            [
                'command' => '/total_user',
                'args' => '',
                'description' => 'Total pengguna',
            ],[
                'command' => '/enable',
                'args' => '<command>',
                'description' => 'Mengaktifkan fitur',
            ],[
                'command' => '/disable',
                'args' => '<command>',
                'description' => 'Menonaktifkan fitur',
            ],
        ],
        'prank' => [
            [
                'command' => '/klikdok',
                'args' => '<nomor> <limit>',
                'description' => 'Spam OTP klikdok unlimited'
            ], [
                'command' => '/codashop',
                'args' => '<nomor>',
                'description' => 'Spam OTP codashop telkomsel delay 10 detik'
            ], [
                'command' => '/sms',
                'args' => '<nomor> <pesan>',
                'description' => 'SMS gratis seluruh OP Indonesia'
            ],
        ],
        'downloader' => [
            [
                'command' => '/fbdl',
                'args' => '<post_url>',
                'description' => 'Download video Facebook'
            ], [
                'command' => '/igdl',
                'args' => '<post_url>',
                'description' => 'Download video Instagram'
            ],
        ],
        'others' => [
            [
                'command' => '/proxy',
                'args' => '',
                'description' => 'Free random proxy list (max 100)'
            ], [
                'command' => '/random_word',
                'args' => '',
                'description' => 'Random kata-kata bijak'
            ], [
                'command' => '/random_email',
                'args' => '<domain>',
                'description' => 'Random email generator (max 100)'
            ], [
                'command' => '/random_phone_numbers',
                'args' => '',
                'description' => 'Random nomor telphone ID generator (max 100)'
            ], [
                'command' => '/email_checker',
                'args' => '<email>',
                'description' => 'Valid email checker support (yahoo, gmail, microsoft)'
            ],
        ],
    ];
}