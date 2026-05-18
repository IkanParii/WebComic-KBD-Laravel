<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->withSession(['manual_captcha.register.answer' => '7'])->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'PasswordBaru12!',
        'password_confirmation' => 'PasswordBaru12!',
        'role' => 'user',
        'captcha_answer' => '7',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('home', absolute: false));
});
