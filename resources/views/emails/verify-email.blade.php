<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f5f5f7; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; }
        .logo { font-size: 24px; font-weight: bold; color: #7B4DFF; margin-bottom: 20px; }
        .title { color: #1f1f1f; font-size: 20px; margin-bottom: 15px; }
        .text { color: #555555; font-size: 15px; line-height: 1.5; margin-bottom: 25px; }
        .btn { display: inline-block; background-color: #7B4DFF; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; font-size: 15px; }
        .footer { margin-top: 30px; font-size: 12px; color: #999999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">AuVerse</div>
        <h2 class="title">Halo, {{ $user->name }}! 👋</h2>
        <p class="text">
            Selamat datang di AuVerse! Tinggal satu langkah lagi nih buat mulai baca dan bikin karya seru. Klik tombol di bawah buat verifikasi email kamu ya.
        </p>
        <a href="{{ $url }}" class="btn" style="color: white;">Verifikasi Email Sekarang</a>
        
        <p class="footer">
            Kalau tombol di atas nggak bisa diklik, *copy-paste* link ini di browser kamu: <br>
            <a href="{{ $url }}" style="color: #7B4DFF;">{{ $url }}</a>
            <br><br>
            Abaikan email ini jika kamu tidak merasa mendaftar di AuVerse.
        </p>
    </div>
</body>
</html>