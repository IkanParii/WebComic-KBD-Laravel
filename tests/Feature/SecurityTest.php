<?php

use App\Models\User;
use App\Models\Cerita;
use App\Models\Genre;

/**
 * Catatan: Semua $this-> diganti jadi test()-> biar VS Code (Intelephense) 
 * nggak error garis merah lagi, tapi testing tetap jalan 100% normal.
 */

// ============================================================================
// 1. PENGUJIAN MIDDLEWARE & ROLE ACCESS (RBAC)
// ============================================================================

test('guest ditolak masuk halaman home', fn() => test()->get('/home')->assertRedirect('/login'));
test('guest ditolak masuk dashboard user', fn() => test()->get('/user/dashboard')->assertRedirect('/login'));
test('guest ditolak masuk area publisher', fn() => test()->get('/publisher/daftar-cerita')->assertRedirect('/login'));
test('guest ditolak masuk area admin', fn() => test()->get('/admin/dashboard')->assertRedirect('/login'));

test('user biasa ditolak masuk area publisher', function () {
    $user = User::factory()->create(['role' => 'user']);
    test()->actingAs($user)->get('/publisher/daftar-cerita')
          ->assertRedirect('/home')->assertSessionHas('error');
});

test('user biasa ditolak masuk area admin', function () {
    $user = User::factory()->create(['role' => 'user']);
    test()->actingAs($user)->get('/admin/dashboard')
          ->assertRedirect('/home')->assertSessionHas('error');
});

test('publisher ditolak masuk area admin', function () {
    $publisher = User::factory()->create(['role' => 'publisher']);
    test()->actingAs($publisher)->get('/admin/dashboard')
          ->assertRedirect('/home')->assertSessionHas('error');
});

test('user belum verifikasi email tidak bisa akses home', function () {
    $user = User::factory()->create(['role' => 'user', 'email_verified_at' => null]);
    test()->actingAs($user)->get('/home')->assertRedirect(); 
});

// ============================================================================
// 2. KEAMANAN ADMIN (SELF-PROTECTION & PEER-PROTECTION)
// ============================================================================

test('admin tidak bisa hapus diri sendiri', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    test()->actingAs($admin)->delete("/admin/user/{$admin->id}")
          ->assertSessionHas('error', 'Waduh, lo nggak bisa nendang diri lo sendiri brow!');
    test()->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('admin tidak bisa hapus admin lain', function () {
    $admin1 = User::factory()->create(['role' => 'admin']);
    $admin2 = User::factory()->create(['role' => 'admin']);
    test()->actingAs($admin1)->delete("/admin/user/{$admin2->id}")
          ->assertSessionHas('error', 'Sesama Admin dilarang saling sikut!');
    test()->assertDatabaseHas('users', ['id' => $admin2->id]);
});

test('admin tidak bisa edit nama admin lain', function () {
    $admin1 = User::factory()->create(['role' => 'admin']);
    $admin2 = User::factory()->create(['role' => 'admin', 'name' => 'Admin Tetap']);
    test()->actingAs($admin1)->put("/admin/user/{$admin2->id}", ['name' => 'Hacked'])
          ->assertSessionHas('error');
    test()->assertDatabaseHas('users', ['id' => $admin2->id, 'name' => 'Admin Tetap']);
});

// ============================================================================
// 3. IDOR (INSECURE DIRECT OBJECT REFERENCE) - ANTI MALING DATA
// ============================================================================

test('publisher tidak bisa lihat cerita orang lain di dashboard', function () {
    $p1 = User::factory()->create(['role' => 'publisher']);
    $p2 = User::factory()->create(['role' => 'publisher']);
    Cerita::factory()->create(['user_id' => $p2->id, 'judul' => 'Rahasia P2']);
    
    test()->actingAs($p1)->get('/publisher/daftar-cerita')->assertDontSee('Rahasia P2');
});

test('publisher tidak bisa edit cerita milik orang lain', function () {
    $p1 = User::factory()->create(['role' => 'publisher']);
    $p2 = User::factory()->create(['role' => 'publisher']);
    $cerita2 = Cerita::factory()->create(['user_id' => $p2->id]);
    
    test()->actingAs($p1)->get("/publisher/edit-cerita/{$cerita2->id}")->assertStatus(404);
});

test('publisher tidak bisa update cerita milik orang lain', function () {
    $p1 = User::factory()->create(['role' => 'publisher']);
    $p2 = User::factory()->create(['role' => 'publisher']);
    $cerita2 = Cerita::factory()->create(['user_id' => $p2->id, 'judul' => 'Asli']);
    
    test()->actingAs($p1)->put("/publisher/update-cerita/{$cerita2->id}", [
        'judul' => 'Diubah Hacker', 'tanggal_rilis' => now()->toDateString(), 'deskripsi_singkat' => 'hacked', 'isi_cerita' => 'hacked', 'genres' => [Genre::factory()->create()->id]
    ])->assertStatus(404);
    test()->assertDatabaseHas('ceritas', ['judul' => 'Asli']);
});

test('publisher tidak bisa hapus cerita milik orang lain', function () {
    $p1 = User::factory()->create(['role' => 'publisher']);
    $p2 = User::factory()->create(['role' => 'publisher']);
    $cerita2 = Cerita::factory()->create(['user_id' => $p2->id]);
    
    test()->actingAs($p1)->delete("/publisher/hapus-cerita/{$cerita2->id}")->assertStatus(404);
    test()->assertDatabaseHas('ceritas', ['id' => $cerita2->id]);
});

// ============================================================================
// 4. BUSINESS LOGIC & DATA INTEGRITY (PENCEGAH MINUS 10 NILAI)
// ============================================================================

test('publisher TIDAK BISA bikin cerita dengan JUDUL KEMBAR (Unique Rule)', function () {
    $p = User::factory()->create(['role' => 'publisher']);
    $genre = Genre::factory()->create();
    
    // Bikin cerita pertama
    Cerita::factory()->create(['user_id' => $p->id, 'judul' => 'Petualangan Budi']);

    // Coba bikin cerita kedua dengan judul yang sama persis
    $response = test()->actingAs($p)->post('/publisher/tambah-cerita', [
        'judul' => 'Petualangan Budi', // Judul kembar
        'tanggal_rilis' => now()->toDateString(),
        'deskripsi_singkat' => 'Deskripsi',
        'isi_cerita' => 'Isi',
        'genres' => [$genre->id]
    ]);

    // Harus ditolak sama validasi unique
    $response->assertSessionHasErrors('judul');
});

test('publisher bisa update cerita tanpa kena error unique di judulnya sendiri', function () {
    $p = User::factory()->create(['role' => 'publisher']);
    $genre = Genre::factory()->create();
    $cerita = Cerita::factory()->create(['user_id' => $p->id, 'judul' => 'Judul Aman']);

    // Update isinya doang, judulnya tetep sama
    $response = test()->actingAs($p)->put("/publisher/update-cerita/{$cerita->id}", [
        'judul' => 'Judul Aman', 
        'tanggal_rilis' => now()->toDateString(),
        'deskripsi_singkat' => 'Update deskripsi baru',
        'isi_cerita' => 'Update isi',
        'genres' => [$genre->id]
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

test('sistem menolak genre ID bodong atau dimanipulasi', function () {
    $p = User::factory()->create(['role' => 'publisher']);
    
    $response = test()->actingAs($p)->post('/publisher/tambah-cerita', [
        'judul' => 'Judul Normal',
        'tanggal_rilis' => now()->toDateString(),
        'deskripsi_singkat' => 'Deskripsi',
        'isi_cerita' => 'Isi',
        'genres' => [99999] // ID Genre ngarang yang ga ada di database
    ]);
    
    $response->assertSessionHasErrors('genres.0');
});

// ============================================================================
// 5. XSS PROTECTION (SANITIZE INPUT)
// ============================================================================

test('admin update user membersihkan script jahat', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);
    test()->actingAs($admin)->put("/admin/user/{$user->id}", ['name' => '<script>alert("XSS")</script>Budi']);
    test()->assertDatabaseHas('users', ['name' => 'alert("XSS")Budi']);
});

test('publisher tambah cerita membersihkan html tags di judul', function () {
    $p = User::factory()->create(['role' => 'publisher']);
    test()->actingAs($p)->post('/publisher/tambah-cerita', [
        'judul' => '<b>Komik</b><marquee>Jahat</marquee>',
        'tanggal_rilis' => now()->toDateString(),
        'deskripsi_singkat' => 'deskripsi',
        'isi_cerita' => 'isi',
        'genres' => [Genre::factory()->create()->id]
    ]);
    test()->assertDatabaseHas('ceritas', ['judul' => 'KomikJahat']);
});

// ============================================================================
// 6. MASS ASSIGNMENT & PARAMETER TAMPERING
// ============================================================================

test('user tidak bisa mengubah role sendiri lewat update profile', function () {
    $user = User::factory()->create(['role' => 'user']);
    test()->actingAs($user)->patch('/profile', ['name' => 'Baru', 'role' => 'admin']);
    test()->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'user']);
});

test('publisher tidak bisa memanipulasi user_id saat bikin cerita baru', function () {
    $p1 = User::factory()->create(['role' => 'publisher']);
    $p2 = User::factory()->create(['role' => 'publisher']); // Target hack
    
    test()->actingAs($p1)->post('/publisher/tambah-cerita', [
        'judul' => 'Coba Hack', 'tanggal_rilis' => now()->toDateString(), 'deskripsi_singkat' => 'x', 'isi_cerita' => 'x', 'genres' => [Genre::factory()->create()->id],
        'user_id' => $p2->id // Sengaja ngirim form pakai ID orang lain
    ]);
    
    // Harus tetep kesimpen atas nama P1 (karena controller pakai Auth::id())
    test()->assertDatabaseHas('ceritas', ['judul' => 'Coba Hack', 'user_id' => $p1->id]);
    test()->assertDatabaseMissing('ceritas', ['judul' => 'Coba Hack', 'user_id' => $p2->id]);
});

// ============================================================================
// 7. BOUNDARY TESTING (PENCEGAHAN CRASH)
// ============================================================================

test('sistem menolak judul cerita yang terlalu panjang', function () {
    $p = User::factory()->create(['role' => 'publisher']);
    $response = test()->actingAs($p)->post('/publisher/tambah-cerita', [
        'judul' => str_repeat('A', 300),
        'tanggal_rilis' => now()->toDateString(),
        'deskripsi_singkat' => 'x',
        'isi_cerita' => 'x',
        'genres' => [Genre::factory()->create()->id]
    ]);
    $response->assertSessionHasErrors('judul');
});

test('sistem menolak tanggal yang tidak valid', function () {
    $p = User::factory()->create(['role' => 'publisher']);
    $response = test()->actingAs($p)->post('/publisher/tambah-cerita', [
        'judul' => 'Judul', 'tanggal_rilis' => '2024-13-45', 'deskripsi_singkat' => 'x', 'isi_cerita' => 'x', 'genres' => [Genre::factory()->create()->id]
    ]);
    $response->assertSessionHasErrors('tanggal_rilis');
});

// ============================================================================
// 8. SQL INJECTION & ERROR HANDLING
// ============================================================================

test('search aman dari basic sql injection', function () {
    $u = User::factory()->create(['role' => 'user']);
    test()->actingAs($u)->get('/home?search=' . urlencode("' OR 1=1 --"))->assertStatus(200);
});

test('mengakses id cerita yang ngawur tidak bikin crash', function () {
    $u = User::factory()->create(['role' => 'user']);
    test()->actingAs($u)->get('/cerita/palsu-banget')->assertStatus(404);
});

// ============================================================================
// 9. HTTP METHOD TAMPERING
// ============================================================================

test('hapus user tidak bisa lewat method get', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);
    test()->actingAs($admin)->get("/admin/user/delete/{$user->id}")->assertStatus(404); 
    test()->assertDatabaseHas('users', ['id' => $user->id]);
});