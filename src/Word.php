<?php

namespace src;

class Word
{
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function randomWord()
    {
        $words = array();
        $fp = fopen('../word/bijak.txt', 'r');
        while (!feof($fp)) {
            $words[] = trim(fgets($fp));
        }
        fclose($fp);
        $result = $this->app->escapeMarkdown($words[array_rand($words)]);
        $result = sprintf("_%s_", $result);
        return $this->app
            ->repply($result)
            ->markdown()
            ->send();
    }
}