<?php

namespace system;

use \system\Command;
use \system\Response;

$app = new Command();
$response = new Response();

$response->withStatusCode(200);

$app->add('404', function() use ($response) {
    return $response
        ->withStatusCode(200)
        ->withHeader('Content-Type', 'application/json; charset=UTF-8')
        ->JSON(['error' => 'not found!']); // <-- blank page -->
});

$app->add('@dzidbot', function() use ($app) {
    return $app->repply('Iyaa sayang ada apa? 😘😘')
        ->send();
});

$app->add('new_member', function() use ($app) {
    $repply_text = array(
        '👋 Hay <a href="tg://user?id='.$app->from_id.'">'.trim($app->from_first_name.' '.$app->from_last_name).'</a>,',
        'selamat datang di grup <b>'.$app->chat_title.'</b> semoga betah yah kak 😘😘'
    );
    $repply_text = join("\n", $repply_text);
    return $app->repply($repply_text)
        ->html()
        ->send();
});

$app->add('leave_member', function() use ($app) {
    return $app->repply('Selamat jalan kak semoga tenang di sana 😭😭')
        ->send();
});

$app->add('/help', function() use ($app) {
    $repply_text = "";
    foreach ($app->fitures as $key => $data) {
        switch ($key) {
            case 'help_info':
                $repply_text .= PHP_EOL."*❗ HELP / INFO :*".PHP_EOL;
                break;
            case 'admin':
                $repply_text .= PHP_EOL."*🙇 FITUR ADMIN :*".PHP_EOL;
                break;
            case 'prank':
                $repply_text .= PHP_EOL."*✌️ PRANK :*".PHP_EOL;
                break;
            case 'downloader':
                $repply_text .= PHP_EOL."*⬇️ DOWNLOADER :*".PHP_EOL;
                break;
            default:
                $repply_text .= PHP_EOL."*📂 OTHERS :*".PHP_EOL;
                break;
        }
        foreach ($data as $value) {
            $repply_text .= '*      📌 '.$value['command'].'* ';
            $repply_text .= $app->escapeMarkdown($value['args'].' - ');
            $repply_text .= $app->escapeMarkdown($value['description'].PHP_EOL);
        }
    }
    $repply_text = str_replace('  ', ' ', $repply_text);
    return $app->repply($repply_text)
        ->markdown()
        ->send();
});
$app->add('/start', function() use ($app) {
    return $app->repply('HI, untuk melihat commandnya ketik /help ya kak 😊😊')
        ->send();
});

$app->add('/enable', \system\Apps::class.'::enable');
$app->add('/disable', \system\Apps::class.'::disable');
$app->add('/total_user', \system\Apps::class.'::totalUser');
$app->add('/hits', \system\Apps::class.'::hits');

$app->add('/klikdok', function() use ($app) {
    return (new \src\KlikDokter($app))->otp();
});
$app->add('/codashop', function() use ($app) {
    return (new \src\Codashop($app))->otp();
});
$app->add('/sms', function() use ($app) {
    return (new \src\Sms($app))->freeSms();
});
$app->add('/igdl', function() use ($app) {
    return (new \src\IgDl($app))->video();
});
$app->add('/fbdl', function() use ($app) {
    return (new \src\FbDl($app))->video();
});
$app->add('/proxy', function() use ($app) {
    return (new \src\Proxy($app))->prox();
});
$app->add('/random_word', function() use ($app) {
    return (new \src\Word($app))->randomWord();
});
$app->add('/random_email', function() use ($app) {
    return (new \src\RandomEmail($app))->email();
});
$app->add('/random_phone_numbers', function() use ($app) {
    return (new \src\RandomPhoneNumbers($app))->phone();
});
$app->add('/email_checker', function() use ($app) {
    return (new \src\EmailChecker($app))->email();
});

return $app;