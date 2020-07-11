<?php

declare(strict_types=1);

namespace system;
use \system\Menu;

class Apps extends Menu
{
    // <-- properti variabel class -->
    // --------------------------------
    //
    // nama depan pengirim
    public $from_first_name = null;

    // nama belakang pengirim
    public $from_last_name = null;

    // username pengirim
    public $from_username = null;

    // id pengirim
    public $from_id = null;

    // pesan pengirim
    public $texts = null;

    // chat namao depan
    public $chat_first_name = null;

    // chat nama belakang
    public $chat_last_name = null;

    // chat username
    public $chat_username = null;

    // chat id
    public $chat_id = null;

    // chat title / nama grup
    public $chat_title = null;

    // data user bot
    public $bot_user = null;

    // semua command
    public $db_command = [];

    // comand yang dapat di nonaktifkan
    public $allow_command = [];

    // command yang tidak dapat di nonaktifkan
    public $disallow_command = [];

    //
    // menghubungkan ke Database
    //
    public function __construct()
    {
        $this->dbconnect();
    }

    public function checkFitures()
    {
        $this->db_fitures = $this->db()
            ->from('fitures')
            ->get();
        foreach ($this->db_fitures as $values) {
            if (isset($values['command'])) {
                $this->db_command[] = $values['command'];
            }
        }
        foreach ($this->fitures as $key => $menu) {
            foreach ($menu as $value) {
                if (in_array($key, ['prank'])) {
                    $this->allow_command[] = $value['command'];
                } else {
                    $this->disallow_command[] = $value['command'];
                }
                if (!in_array($value['command'], $this->db_command)) {
                    //
                    // Menambahkan data ke Databaee -> tabel fitures
                    //
                    $data = [
                        'command' => $value['command'],
                        'hits' => '0',
                        'disable' => 'false',
                    ];
                    $this->db()
                        ->insert('fitures')
                        ->addValue($data)
                        ->addData();
                }
            }
        }
        return $this;
    }

    public function getFrom()
    {
        $data = isset($this->message['new_chat_member']) ? $this->message['new_chat_member'] : $this->message['from'];
        $this->from_first_name = isset($data['first_name']) ? $data['first_name'] : '';
        $this->from_last_name = isset($data['last_name']) ? $data['last_name'] : '';
        $this->from_username = isset($data['username']) ? $data['username'] : '';
        $this->from_id = isset($data['id']) ? $data['id'] : '';
        return $this;
    }

    public function getChat()
    {
        $data = isset($this->message['chat']) ? $this->message['chat'] : [];
        $this->chat_first_name = isset($data['first_name']) ? $data['first_name'] : '';
        $this->chat_last_name = isset($data['last_name']) ? $data['last_name'] : '';
        $this->chat_username = isset($data['username']) ? $data['username'] : '';
        $this->chat_id = isset($data['id']) ? $data['id'] : '';
        $this->chat_title = isset($data['title']) ? $data['title'] : '';
        return $this;
    }

    public function isNewChatMemberOrLeaveMember()
    {
        if (isset($this->message['new_chat_member'])) {
            //
            // memanggil function 'render' dari Command.php
            // dan akan di teruskan ke Routes.php -> 'new_member'
            //
            $this->render('new_member');
        } elseif (isset($this->message['left_chat_member'])) {
            //
            // memanggil function 'render' dari Command.php
            // dan akan di teruskan ke Routes.php -> 'leave_member'
            //
            $this->render('leave_member');
        }
        return $this;
    }

    //
    // mengambil user dari Database -> user
    // jika tidak ada akan di insert ke Database
    //
    public function getUserBot()
    {
        if (is_array($this->message) and count($this->message) !== 0) {
            $data = [
                'telegram_id' => $this->from_id,
            ];
            $this->bot_user = $this->db()
                ->from('user')
                ->where($data)
                ->get();
            $data = [
                'telegram_id' => $this->from_id,
                'date_joined' => date('j F y'),
            ];
            if (!$this->bot_user) {
                $this->db()
                    ->insert('user')
                    ->addValue($data)
                    ->addData();
                $this->repply('Hei <a href="tg://user?id=' . trim($this->from_id . '">' . $this->from_first_name . ' ' . $this->from_last_name) . '</a> akunmu telah Terdaftar 💦💦')
                    ->html()
                    ->send();
            }
        }
        return $this;
    }

    public function totalUser()
    {
        if ($this->from_id != ADMIN_ID) {
            return $this->repply('🔐 Upps fitur ini hanya <a href="tg://user?id=' . ADMIN_ID . '">Admin</a> yang dapat mengakses!!')
                ->html()
                ->send();
        }
        $get = $this->db()
            ->from('user')
            ->get();
        return $this->repply('🔥 Total pengguna: <b>' . count($get) . '</b>')
            ->html()
            ->send();
    }

    public function enable()
    {
        if ($this->from_id != ADMIN_ID) {
            return $this->repply('🔐 Upps fitur ini hanya <a href="tg://user?id=' . ADMIN_ID . '">Admin</a> yang dapat mengakses!!')
                ->html()
                ->send();
        }
        if (in_array(isset($this->texts[1]) ? $this->texts[1] : '', $this->allow_command) or in_array(isset($this->texts[1]) ? $this->texts[1] : '', $this->disallow_command)) {
            $get = $this->db()
                ->from('fitures')
                ->where(['command' => $this->texts[1]])
                ->get();
            if ($get[0]['disable'] == 'false') {
                return $this->repply('⛔ Tidak perlu fitur ini tidak sedang dinonaktifkan 🔓')
                    ->send();
            }
            $this->db()
                ->from('fitures')
                ->set(['disable' => 'false'])
                ->where(['command' => $this->texts[1]])
                ->update();
            return $this->repply('✔️ OK, Fitur ' . $this->texts[1] . ' kembali di aktifkan 🔓.')
                ->send();
        } else {
            return $this->repply(join("\n", ['➡️ USAGE : */enable* <command>', '➡️ EX : */enable* /klikdok']))
                ->markdown()
                ->send();
        }
    }

    public function disable()
    {
        if ($this->from_id != ADMIN_ID) {
            return $this->repply('🔐 Upps fitur ini hanya <a href="tg://user?id=' . ADMIN_ID . '">Admin</a> yang dapat mengakses!!')
                ->html()
                ->send();
        }
        if (in_array(isset($this->texts[1]) ? $this->texts[1] : '', $this->allow_command)) {
            $get = $this->db()
                ->from('fitures')
                ->where(['command' => $this->texts[1]])
                ->get();
            if ($get[0]['disable'] == 'true') {
                return $this->repply('⛔ Tidak perlu fitur ini sedang di nonaktifkan 🔐')
                    ->send();
            }
            $this->db()
                ->from('fitures')
                ->set(['disable' => 'true'])
                ->where(['command' => $this->texts[1]])
                ->update();
            return $this->repply('✔️ OK, Fitur ' . $this->texts[1] . ' telah di nonaktifkan 🔐.')
                ->send();
        } elseif (in_array(isset($this->texts[1]) ? $this->texts[1] : '', $this->disallow_command)) {
            return $this->repply('⛔ Sorry fitur ' . $this->texts[1] . ' tidak dapat di nonaktifkan.')
                ->send();
        } else {
            return $this->repply(join("\n", ['➡️ USAGE : */disable* <command>', '➡️ EX : */disable* /klikdok']))
                ->markdown()
                ->send();
        }
    }

    public function hits()
    {
        $fitures = $this->db()
            ->from('fitures')
            ->get();
        $message = "";
        foreach ($fitures as $data) {
            $status = $data['disable'] == 'true' ? 'di nonaktifkan ' : 'aktif ';
            $symbol = $data['disable'] == 'true' ? '🔐' : '🔓';
            $message .= "➡️ Command : <b>" . $data['command'] . "</b>" . PHP_EOL;
            $message .= "➡️ Hits : <b>" . $data['hits'] . "</b>" . PHP_EOL;
            $message .= "➡️ Status : <b>" . $status . $symbol . "</b>" . PHP_EOL . PHP_EOL;
        }
        return $this->repply($message)
            ->html()
            ->send();
    }

    public function app()
    {
        // menghapus multi spasi dan multi new lines dari pesan telegram
        $this->texts = preg_replace('/\s+|\n+/', ' ', isset($this->message['text']) ? $this->message['text'] : '');
        // memisah spasi
        $this->texts = explode(' ', $this->texts);
        if (is_array($this->message) and count($this->message) !== 0) {
            if (in_array($this->texts[0], $this->allow_command) or in_array($this->texts[0], $this->disallow_command)) {
                $this->db()->query("UPDATE fitures SET hits=hits +1 WHERE (command='" . $this->texts[0] . "')");
            }
            $cek = array_search($this->texts[0], array_column($this->db_fitures, 'command'));
            $disabled = isset($this->db_fitures[$cek]) ? $this->db_fitures[$cek] : false;
            if (isset($disabled['disable']) && $disabled['disable'] == 'true') {
                return $this->repply('🚫 Upps sorry fitur ini telah di 🔐 nonaktifkan oleh <a href="tg://user?id=' . ADMIN_ID . '">Admin</a> anda tidak dapat menggunakannya lagi.')
                    ->html()
                    ->send();
            }
            return $this->render(
                in_array($this->texts[0], ['new_chat_member', 'leave_member'])
                ? ''
                : $this->texts[0]
            );
        }
    }
}
