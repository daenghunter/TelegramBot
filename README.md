Simple bot chat for telegram
----------------------------------------------

DEMO : [BOT](https://t.me/dzidbot)

#### Gimana cara pakeknya ?
Oke sekarang masuk ke fie ```config/Settings.php```
Ubah bagian ```$TOKEN``` sesuai bot token telegram anda,
Token bot bisa di dapatkan dari [Bot Father](http://t.me/BotFather)

```
$TOKEN = ''; // isi token bot telegram
```

Ubah bagian ```$ADMIN``` sesuai id telegram akun anda
bisa di dapatkan dari [User Bot Info](https://t.me/userinfobot)

```
$ADMIN = ''; // isi id telegram anda
```

Sekarang ubah bagian ```$DB``` sesuai akun database anda, inget jangan sampe salah.

```
$DB = [
    'db_server'   => 'localhost', // server database
    'db_name'     => '', // nama database
    'db_username' => '', // username database
    'db_password' => '', // password database
];
```

Oke file ```settings.php``` sudah di rubah, sekarang import ```TeleBot.sql``` ke server database anda,
jika sudah lalu arahain domainnya di cpanel ke folder public: ```domain.com/public``` inget jangan taro di folder root untuk ke amanan :v,
jika semua sudah tinggal upload scriptnya ke cpanel lalu ubah semua file premission ke 775

Oke semua sudah siap tinggal coba chat dengan bot nya hheee, maaf jika ada yg error karena saya sendiri masih belejar :)
kalo bot tidak merespon mungkin anda belum set webhooknya, jika iya silahkan set webhooknya:
```https://api.telegram.org/bot<token_bot>/setWebhook?url=https://domain.com/```<br>
<br>contoh: ```https://api.telegram.org/bot181627325916:AAE-CwIemCTRdccEElGX
AnulLsA--wzbGlM/setWebhook?url=https://domain.com```
<br>```domain.com``` ganti dgn domain anda sendiri yang isinya script bot telegram tadi
Ingat server sudah harus SSL jika belum, ada situs penyedia SSL gratis yaitu [Cloudflare](https://cloudflare.com)

Jika kurang jelas / ada pertanyaan silahkan chat ke [Admin](https://t.me/DulLah)

## Selamat mencoba :)
