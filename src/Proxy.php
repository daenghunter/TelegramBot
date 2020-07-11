<?php

namespace src;

class Proxy
{
    public function __construct($app)
    {
        $this->app = $app;
        $this->app
            ->repply('🔍 Tunggu sedang mencari proxy')
            ->send();
    }
 
    public function prox()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://free-proxy-list.net/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
        $response = curl_exec($ch);
        curl_close($ch);
        $dom = new \DOMDocument();
        @$dom->loadHTML($response);
        $textarea = $dom->getElementsByTagName('textarea');
        $result = $textarea->item(0)->nodeValue;
        $result = preg_replace('/\n+/', '\n', $result);
        $result = preg_replace('/\s+/', '', $result);
        $result = explode('\n', $result);
        $proxy  = [];
        foreach ($result as $prox) {
            $sp = explode(':', $prox);
            if (count($sp) == 2) {
                if (count($proxy) == 100) {
                    break;
                } else {
                    $proxy[] = $prox;
                }
            }
        }
        if (count($proxy) == 0) {
            return $this->app
                ->repply('❌ Upps sorry proxy tidak di temukan :(')
                ->send();
        } else {
            return $this->app
                ->repply(join("\n", $proxy))
                ->send();
        }
    }
}