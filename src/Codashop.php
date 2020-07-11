<?php

namespace src;

class Codashop
{
    public function __construct($app)
    {
        $this->app = $app;
    }
 
    public function otp()
    {
        if (count($this->app->texts) < 2) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps pesan tidak lengkap ```\n", '➡️ USAGE : */codashop* <nomor>', '➡️ EX : */codashop* 0822')))
                ->markdown()
                ->send();
        } elseif (!is_numeric($this->app->texts[1])) {
            return $this->app
                ->repply(join("\n", array("❌ ``` Upps nomor tidak valid ```\n", '➡️ USAGE : */codashop* <nomor>', '➡️ EX : */codashop* 0822')))
                ->markdown()
                ->send();
        } else {
            return $this->send();
        }
    }

    private function send()
    {
        $random   = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,10);
        $email    = $random.'___@gmail.com';
        $base_url = 'https://www.codashop.com/id/';
        $prices = array(
            [
                'id'    => '192',
                'price' => '15000',
            ],
            [
                'id'    => '181',
                'price' => '150000',
            ],
            [
                'id'    => '197',
                'price' => '50000',
            ],
            [
                'id'    => '182',
                'price' => '500000',
            ]
        );
        $rand   = $prices[array_rand($prices)];
        $id     = $rand['id'];
        $price  = $rand['price'];
        $params = http_build_query([
            'voucherPricePoint.id'            => $id,
            'voucherPricePoint.price'         => $price,
            'voucherPricePoint.variablePrice' => '0',
            'userVariablePrice'               => '0',
            'email'                           => $email,
            'msisdn'                          => $this->app->texts[1],
            'voucherTypeName'                 => 'Inventary',
            'order.data.profile'              => 'eyJuYW1lIjoiIiwiaWRfbm8iOiIifQ=='
        ]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url.'initPayment.action');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_USERAGENT, AGENT);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        $status = isset($response->redirect_url) ? 'ok' : 'failed';
        return $this->app
            ->repply($status == 'ok' ? '☑️ Terkirim' : '❌ Gagal')
            ->send();
    }
}