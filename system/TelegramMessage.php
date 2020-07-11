<?php

declare(strict_types=1);

namespace system;
use system\Apps;

class TelegramMessage extends Apps
{
    // parse mode
    public $mode    = null;
    // pesan telegram array
    public $message = null;
    // pesan telegram
    public $text    = null;
    // telegram api
    public $api     = 'https://api.telegram.org/bot' . TOKEN . '/';

    public function markdown()
    {
        $this->mode = 'markdown';
        return $this;
    }

    public function html()
    {
        $this->mode = 'html';
        return $this;
    }

    public function repply(string $msg = '')
    {
        $this->text = $msg;
        return $this;
    }

    public function escapeHtml(string $value = null)
    {
        if ($value == null) {
            $this->text = htmlspecialchars($this->text);
            return $this;
        } else {
            $value = htmlspecialchars($value);
            return $value;
        }
    }

    public function escapeMarkdown(string $value = null)
    {
        if ($value == null) {
            $this->text = preg_replace('/_/', "\\_", $this->text);
            $this->text = preg_replace('/\*/', "\\*", $this->text);
            $this->text = preg_replace('/\[/', "\\[", $this->text);
            $this->text = preg_replace('/`/', "\\`", $this->text);
            return $this;
        } else {
            $value = preg_replace('/_/', "\\_", $value);
            $value = preg_replace('/\*/', "\\*", $value);
            $value = preg_replace('/\[/', "\\[", $value);
            $value = preg_replace('/`/', "\\`", $value);
            return $value;
        }
    }

    public function getUpdates()
    {
        $response = @file_get_contents($this->api . 'getUpdates');
        $parser = json_decode($response, true);
        if (isset($parser['result'])) {
            foreach ($parser['result'] as $data) {
                $this->message = $data['message'];
            }
        }
        
        return $this;
    }

    public function getWebhook()
    {
        $response = @file_get_contents('php://input');
        $parser = json_decode($response, true);
        if (isset($parser['message'])) {
            $this->message = $parser['message'];
        }
        return $this;
    }

    public function send()
    {
        $data = http_build_query([
            'chat_id'             => $this->chat_id,
            'text'                => $this->text,
            'reply_to_message_id' => $this->message['message_id'],
            'parse_mode'          => $this->mode,
        ]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api . 'sendmessage');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $response = DEBUG ? $response : '';
        return $response;
    }
}
