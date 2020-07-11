<?php

namespace src;

class Sms
{
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function freeSms()
    {
        if (count($this->app->texts) < 3) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps pesan tidak lengkap ```\n", '➡️ USAGE : */sms* <nomor> <pesan>', '➡️ EX : */sms* 0822 hello worlds')))
                ->markdown()
                ->send();
        } elseif (!is_numeric($this->app->texts[1])) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps nomor tidak valid ```\n", '➡️ USAGE : */sms* <nomor> <pesan>', '➡️ EX : */sms* 0822 hello worlds')))
                ->markdown()
                ->send();
        }
        $this->no = $this->app->texts[1];
        $this->pesan = $this->app->texts;
        unset($this->pesan[1]);
        unset($this->pesan[0]);
        $this->pesan = join(' ', $this->pesan);
        if (strlen($this->pesan) < 15) {
            return $this->app
                ->repply('❌ Pesan minimal 15 karakter')
                ->send();
        } else {
            return $this->send();
        }
    }

    private function send()
    {
        $data = [];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://alpha.payuterus.biz');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com/');
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 7.0; SM-A310F Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36 OPR/42.7.2246.114996');
        $response = curl_exec($ch);
        curl_close($ch);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $cookie);
        preg_match('/name=\"key\" value=\"(.*?)\">/', $response, $key);
        preg_match('/<span>(.*?) = /', $response, $bypass);
        $jml = explode(" + ", isset($bypass[1]) ? $bypass[1] : '');
        $jml = isset($jml[0]) && isset($jml[1]) ? $jml[0]+$jml[1] : '0';
        $data['captcha'] = $jml;
        $data['key']     = isset($key[1]) ? $key[1] : '';
        $data['nohp']    = $this->no;
        $data['pesan']   = urlencode($this->pesan);
        $params = http_build_query($data);
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://alpha.payuterus.biz/send.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, 'https://alpha.payuterus.biz/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_COOKIE, isset($cookie[1][0]) && isset($cookie[1][1]) ? $cookie[1][0]."; ".$cookie[1][1] : '');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 7.0; SM-A310F Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36 OPR/42.7.2246.114996');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
        curl_close($ch);
        if (strpos($response, 'SMS Gratis Telah Dikirim') !== false){
            $result = '☑️ Pesan telah terkirim, mungkin agak delay.';
        } elseif (strpos($response, 'MAAF....!') !== false){
            $result = '❌ Tunggu 10 menit sebelum mengirim pesan yang sama.';
        } else {
            $result = '❌ Pesan gagal di kirim silahkan coba lagi.';
        }
        return $this->app
            ->repply($result)
            ->send();
    }
}