<?php

namespace src;

class RandomPhoneNumbers
{
    private $phone_code = array(
        '+62811',
        '+62812',
        '+62813',
        '+62814',
        '+62815',
        '+62816',
        '+62817',
        '+62818',
        '+62819',
        '+62821',
        '+62831',
        '+62838',
        '+62852',
        '+62853',
        '+62855',
        '+62856',
        '+62857',
        '+62858',
        '+62859',
        '+62877',
        '+62878',
        '+62879',
        '+62881',
        '+62882',
        '+62896',
        '+62897',
        '+62898',
        '+62899'
    );
 
    public function __construct($app)
    {
        $this->app = $app;
    }

    private function randomDigit()
    {
        $digit = [7,8,9];
        $num = '';
        for ($i = 0; $i < $digit[array_rand($digit)]; $i++) {
            $num .= mt_rand(0,9);
        }
        return $num;
    }

    public function phone()
    {
        $result = array();
        for ($i = 0; $i < 100; $i++) {
            $code = $this->phone_code[array_rand($this->phone_code)];
            $num  = $this->randomDigit();
            $no = trim($code).$num;
            $result[] = $no;
        }
        return $this->app
            ->repply(join("\n", $result))
            ->send();
    }
}