<?php

use App\Models\Cerita;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

test('login menolak request tanpa captcha manual', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('captcha_answer');
    $this->assertGuest();
});

test('register menolak role admin dari publik', function () {
    $response = $this->from('/register')->withSession(['manual_captcha.register.answer' => '7'])->post('/register', [
        'name' => 'Attacker',
        'email' => 'attacker@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'admin',
        'captcha_answer' => '7',
    ]);

    $response->assertRedirect('/register');
    $response->assertSessionHasErrors('role');
    $this->assertDatabaseMissing('users', ['email' => 'attacker@example.com']);
});

test('login publisher wajib otp dan belum boleh akses area publisher sebelum verifikasi', function () {
    Mail::fake();

    $publisher = User::factory()->create([
        'role' => 'publisher',
        'email_verified_at' => now(),
        'email' => 'pub@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->withSession(['manual_captcha.login.answer' => '5'])->post('/login', [
        'email' => $publisher->email,
        'password' => 'password123',
        'captcha_answer' => '5',
    ]);

    $response->assertRedirect(route('publisher.otp.form'));
    $this->assertGuest();

    $publisher->refresh();
    expect($publisher->publisher_otp_code)->not->toBeNull();
    expect($publisher->publisher_otp_expires_at)->not->toBeNull();

    $this->get('/publisher/daftar-cerita')->assertRedirect('/login');
});

test('otp publisher salah ditolak dan tidak login', function () {
    $publisher = User::factory()->create([
        'role' => 'publisher',
        'email_verified_at' => now(),
        'publisher_otp_code' => '123456',
        'publisher_otp_expires_at' => now()->addMinutes(10),
    ]);

    $this->withSession([
        'publisher_otp_user_id' => $publisher->id,
        'publisher_otp_remember' => false,
    ])->from(route('publisher.otp.form'))->post(route('publisher.otp.verify'), [
        'otp' => '654321',
    ])->assertRedirect(route('publisher.otp.form'))
      ->assertSessionHasErrors('otp');

    $this->assertGuest();
});

test('otp publisher benar meloloskan login dan menghapus otp', function () {
    $publisher = User::factory()->create([
        'role' => 'publisher',
        'email_verified_at' => now(),
        'publisher_otp_code' => '123456',
        'publisher_otp_expires_at' => now()->addMinutes(10),
    ]);

    $this->withSession([
        'publisher_otp_user_id' => $publisher->id,
        'publisher_otp_remember' => true,
    ])->post(route('publisher.otp.verify'), [
        'otp' => '123456',
    ])->assertRedirect('/home');

    $this->assertAuthenticatedAs($publisher);
    $this->assertDatabaseHas('users', [
        'id' => $publisher->id,
        'publisher_otp_code' => null,
        'publisher_otp_expires_at' => null,
    ]);
});

test('publisher login tanpa session otp_verified diblokir middleware publisher otp', function () {
    $publisher = User::factory()->create([
        'role' => 'publisher',
        'email_verified_at' => now(),
    ]);

    $this->actingAs($publisher)
        ->withSession(['publisher_otp_verified' => false])
        ->get('/publisher/daftar-cerita')
        ->assertRedirect(route('publisher.otp.form'));
});

test('publisher tidak bisa hapus cerita milik publisher lain (IDOR)', function () {
    $publisherA = User::factory()->create(['role' => 'publisher', 'email_verified_at' => now()]);
    $publisherB = User::factory()->create(['role' => 'publisher', 'email_verified_at' => now()]);

    $genre = Genre::factory()->create();
    $ceritaB = Cerita::factory()->create(['user_id' => $publisherB->id]);
    $ceritaB->genres()->attach($genre->id);

    $this->actingAs($publisherA)
        ->withSession(['publisher_otp_verified' => true])
        ->delete("/publisher/hapus-cerita/{$ceritaB->id}")
        ->assertStatus(404);

    $this->assertDatabaseHas('ceritas', ['id' => $ceritaB->id]);
});

test('user biasa tidak bisa akses area publisher', function () {
    $user = User::factory()->create([
        'role' => 'user',
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user)
        ->get('/publisher/daftar-cerita')
        ->assertRedirect('/home');
});

test('admin tidak bisa menghapus dirinya sendiri', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);

    $this->actingAs($admin)
        ->delete("/admin/user/{$admin->id}")
        ->assertSessionHas('error');

    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('publisher tidak bisa override user_id saat membuat cerita', function () {
    $publisherA = User::factory()->create(['role' => 'publisher', 'email_verified_at' => now()]);
    $publisherB = User::factory()->create(['role' => 'publisher', 'email_verified_at' => now()]);
    $genre = Genre::factory()->create();

    $this->actingAs($publisherA)
        ->withSession(['publisher_otp_verified' => true])
        ->post('/publisher/tambah-cerita', [
            'judul' => 'Cerita Aman',
            'tanggal_rilis' => now()->toDateString(),
            'deskripsi_singkat' => 'desc',
            'isi_cerita' => 'isi',
            'genres' => [$genre->id],
            'user_id' => $publisherB->id,
        ])->assertRedirect(route('publisher.index'));

    $this->assertDatabaseHas('ceritas', [
        'judul' => 'Cerita Aman',
        'user_id' => $publisherA->id,
    ]);

    $this->assertDatabaseMissing('ceritas', [
        'judul' => 'Cerita Aman',
        'user_id' => $publisherB->id,
    ]);
});
