<?php

namespace src;

class FbDl
{
    public function __construct($app)
    {
        $this->app = $app;
    }
 
    public function video()
    {
        if (count($this->app->texts) < 2) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps pesan tidak lengkap ```\n", '➡️ USAGE : */fbdl* <post\\_url>', '➡️ EX : */fbdl* https://www.facebook.com/311949566165487/posts/512523372774771/?app=fbl')))
                ->markdown()
                ->send();
        } elseif (!filter_var($this->app->texts[1], FILTER_VALIDATE_URL)) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps link tidak valid ```\n", '➡️ USAGE : */fbdl* <post\\_url>', '➡️ EX : */fbdl* https://www.facebook.com/311949566165487/posts/512523372774771/?app=fbl')))
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
        $response = curl_exec($ch);
        curl_close($ch);
        preg_match('/<\meta property=\"og:video\" content=\"(.*?)\" \/\>/' ,$response, $urls);
        preg_match('/<\meta name=\"description\" content=\"(.*?)\" \/\>/' ,$response, $description);
        if (count($description) == 2){
            $repply .= "📝 Description : *".$this->app->escapeMarkdown($description[1])."*\n";
        }
        if (count($urls) == 2){
            $urls    = str_replace(';', '&', $urls[1]);
            $urls    = str_replace(parse_url($urls)['host'], 'video.xx.fbcdn.net', $urls);
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