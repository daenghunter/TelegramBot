<?php

declare(strict_types=1);

namespace system;
use \system\TelegramMessage;

class Command extends TelegramMessage
{
    /*
    * Array command / perintah untuk menampung command
    */
    private $commands = array();
    
    /*
    * Menambahkan command / perintah
    */
    public function add($action, $callback)
    {
        /*
        * menghapus blank spasi
        */
        $action = trim($action);
        /*
        * manampung command / perintah
        */
        $this->commands[$action] = $callback;
    }
    
    public function render($command)
    {
        /*
        * menghapus blank spasi
        */
        $action = trim($command);
        /*
        * mengambil command / perintah
        */
        $callback = isset($this->commands[$action])
        ? $this->commands[$action]
        : $this->commands['404'];
        /*
        * memanggil function
        * jika ada akan melanjutkan dan ngebales pesan telegram
        * jika tidak ada akan memanggil function '404' dari Routes.php
        */
        echo call_user_func($callback);
        exit;
    }
    
    public function run()
    {
        (DEBUG) ?
          $this->checkFitures() // mengambil commad / fitures dari database nanti akan berguna untuk mengecek apakah commandnya di disabled
                ->getUpdates() // mengambil updatean, pesan telegram
                ->getFrom() // memgambil id, username, pesan, dll.
                ->getChat() // memgambil id, chat, judul, dll.
                ->isNewChatMemberOrLeaveMember() // mengecek jika ada member baru bergabung ke grup atau ada member yang keluar dari grup akan di Repply.
                ->getUserBot() // mengambil user bot dari database
                ->app() // run app
        : $this->checkFitures() // mengambil commad / fitures dari database nanti akan berguna untuk mengecek apakah commandnya di disabled
                ->getWebhook() // mengambil webhook, pesan telegram
                ->getFrom() // memgambil id, username, pesan, dll.
                ->getChat() // memgambil id, chat, judul, dll.
                ->isNewChatMemberOrLeaveMember() // mengecek jika ada member baru bergabung ke grup atau ada member yang keluar dari grup akan di Repply.
                ->getUserBot() // mengambil user bot dari database
                ->app() // run app
        ; return $this;
    }
}