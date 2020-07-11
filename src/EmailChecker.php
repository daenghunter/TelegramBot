<?php

namespace src;

class EmailChecker
{
    private $allowed = array(
        'gmail.com',
        'yahoo.com',
        'hotmail.com',
        'msn.com',
        'live.com',
        'outlook.com',
        'outlook.co.id'
    );

    public function __construct($app)
    {
        $this->app = $app;
    }
 
    public function email()
    {
        if (count($this->app->texts) < 2) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps pesan tidak lengkap ```\n", "➡️ USAGE : */email_checker* <email>", "➡️ EX : */email_checker* test@yahoo.com")))
                ->markdown()
                ->send();
        } elseif (!filter_var($this->app->texts[1], FILTER_VALIDATE_EMAIL)) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps email tidak valid ```\n", "➡️ USAGE : */email_checker* <email>", "➡️ EX : */email_checker* test@yahoo.com")))
                ->markdown()
                ->send();
        } elseif (!in_array(explode("@", $this->app->texts[1])[1], $this->allowed)) {
            return $this->app
                ->repply('❌ Hannya bisa mengecek email : *('.join(', ', $this->allowed).')*')
                ->markdown()
                ->send();
        } else {
            $this->app
                ->repply('🔍 Tunggu sedang dalam pengecekan...')
                ->send();
            return $this->cekDomain();
        }
    }

    private function cekDomain()
    {
         // explode email, get domain
        $domain = explode('@', $this->app->texts[1]);
        switch ($domain[1]) {
            case 'gmail.com':
                $out = $this->gmail();
                break;
            case 'yahoo.com':
                $out = $this->yahoo();
                break;
            case 'hotmail.com':
                $out = $this->microsoft();
                break;
            case 'outlook.com':
                $out = $this->microsoft();
                break;
            case 'outlook.co.id':
                $out = $this->microsoft();
                break;
            case 'live.com':
                $out = $this->microsoft();
                break;
            case 'msn.com':
                $out = $this->microsoft();
                break;
            default:
                break;
        }
        return $this->app
            ->repply(join("\n", $out))
            ->send();
    }

    private function gmail()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://mail.google.com/mail/gxlu?email='.$this->app->texts[1]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
        $response = curl_exec($ch);
        curl_close($ch);
        $preg = preg_match('/^Set-Cookie:\s*([^;]*)/mi', $response, $cookies);
        $status = ($preg) ? 'Terdaftar' : 'Tidak terdaftar';
        return array(
            "✉️ Email : {$this->app->texts[1]}",
            "➡️ Status : {$status}"
        );
    }

    private function yahoo()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://login.yahoo.com/config/login?');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com/');
        curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
        $response = curl_exec($ch);
        curl_close($ch);
        preg_match('/name=\"acrumb\" value=\"(.*?)\"/', $response, $acrumb);
        preg_match('/name=\"sessionIndex\" value=\"(.*?)\"/', $response, $sessionIndex);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $cookies);
        $data = [];
        $data['acrumb'] = isset($acrumb[1]) ? $acrumb[1] : '';
        $data['sessionIndex'] = isset($sessionIndex[1]) ? $sessionIndex[1] : '';
        $data['username'] = $this->app->texts[1];
        $params = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://login.yahoo.com/config/login?');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIE, isset($cookies[1][1]) ? $cookies[1][1] : '');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_REFERER, 'https://login.yahoo.com/config/login/');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Requested-With: XMLHttpRequest',
            'Cookie: '.isset($cookies[1][1]) ? $cookies[1][1] : ''
        ]);
        curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
        $response = curl_exec($ch);
        curl_close($ch);
        $jsonParse = json_decode($response);
        $status = isset($jsonParse->render->error) ? 'Tidak terdaftar' : 'Terdaftar';
        return array(
            "✉️ Email : {$this->app->texts[1]}",
            "➡️ Status : {$status}"
        );
    }

    private function microsoft()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://login.live.com/?username='.$this->app->texts[1]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, 'https://login.live.com/');
        curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
        $response = curl_exec($ch);
        curl_close($ch);
        preg_match('/\"IfExistsResult\":(.*?)},/', $response, $result);
        $status = isset($result[1]) && strpos($result[1], 'Credentials') ? 'Terdaftar' : 'Tidak terdaftar';
        return array(
            "✉️ Email : {$this->app->texts[1]}",
            "➡️ Status : {$status}"
        );
    }
}