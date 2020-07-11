<?php

namespace src;

class IgDl
{
    public function __construct($app)
    {
        $this->app = $app;
    }
 
    public function video()
    {
        if (count($this->app->texts) < 2) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps pesan tidak lengkap ```\n", '➡️ USAGE : */igdl* <post\\_url>', '➡️ EX : */igdl* https://www.instagram.com/p/xxxxxx')))
                ->markdown()
                ->send();
        } elseif (!filter_var($this->app->texts[1], FILTER_VALIDATE_URL)) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps link tidak valid ```\n", '➡️ USAGE : */igdl* <post\\_url>', '➡️ EX : */igdl* https://www.instagram.com/p/xxxxxx')))
                ->markdown()
                ->send();
        } else {
            $this->app
                ->repply('🔍 Tunggu sedang mancari videonya...')
                ->send();
            return $this->download();
        }
    }

    private function download()
    {
        $repply = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->app->texts[1]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Origin: https://www.instagram.com/',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'User-Agent: '.AGENT,
            'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        preg_match('/<\meta property=\"og:video\" content=\"(.*?)\" \/\>/', $response, $urls);
        preg_match('/<\meta content=\"(.*?)\" name=\"description\" \/\>/', $response, $description);
        if (count($description) == 2){
            $repply .= "📝 Description : *".$this->app->escapeMarkdown($description[1])."*\n";
        }
        if (count($urls) == 2){
            $urls    = str_replace(';', '&', $urls[1]);
            $repply .= "📎 Video URL : *".$urls."*\n";
        } else {
            return $this->app
                ->repply('❌ Maaf video tidak di temukan')
                ->send();
        }
        return $this->app
            ->repply($repply)
            ->markdown()
            ->send();
    }
}