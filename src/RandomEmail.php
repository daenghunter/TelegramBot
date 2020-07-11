<?php

namespace src;

class RandomEmail
{
    public function __construct($app)
    {
        $this->app = $app;
    }
 
    public function email()
    {
        if (count($this->app->texts) < 2) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps pesan tidak lengkap ```\n", "➡️ USAGE : */random_email* <domain>", "➡️ EX : /*random_email* gmail.com")))
                ->markdown()
                ->send();
        } else {
            return $this->random();
        }
    }

    private function random()
    {
        $domain = preg_replace('/\s+|\t+|\n+|@/', '', $this->app->texts[1]);
        $name = array();
        $fp = fopen('../word/name.txt', 'r');
        while (!feof($fp)) {
            $name[] = trim(fgets($fp));
        }
        fclose($fp);
        for ($i = 0; $i < 100; $i++) {
            $first_name = preg_replace('/\s+|\t+|\n+/', '', $name[array_rand($name)]);
            $max = array(10,20,30,40,50,60,70,80,90,100,200,300,400,500,600,700,800,900,1000,2000,3000,4000,5000,6000,7000,8000,9000,10000);
            $number = rand(0, $max[array_rand($max)]);
            $mail = strtolower($first_name);
            $mail .= $number;
            $mail .= '@'.strtolower($domain);
            $result[] = trim($mail);
        }
        return $this->app
            ->repply(join("\n", $result))
            ->send();
    }
}