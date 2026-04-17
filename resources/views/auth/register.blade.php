<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AuStory</title>

    @vite(['resources/css/app.css'])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body class="bg-[#f5f5f7] min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-5xl bg-white rounded-[28px] border border-purple-200 overflow-hidden">
    <div class="grid md:grid-cols-2">

        <!-- LEFT -->
        <div class="hidden md:block bg-gradient-to-br from-purple-500 to-indigo-500 p-10 text-white">
            <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mb-6 shadow">
                <span class="text-purple-600 font-bold text-xl">AS</span>
            </div>

            <h1 class="text-4xl font-bold mb-3">AuStory</h1>
            <p class="text-sm leading-7">
                Masuk ke dunia anime favoritmu. Simpan list tontonan,
                karakter favorit, dan jelajahi vibe anime yang seru.
            </p>
        </div>

        <!-- RIGHT -->
        <div class="bg-[#fcfcfd] p-8">
            <h2 class="text-3xl font-bold">Register</h2>
            <p class="text-sm text-gray-500 mb-6">Welcome back, senpai</p>

            <form class="space-y-4">

                <!-- ROLE -->
                <div class="text-center">
                    <p class="text-sm mb-2">Daftar Sebagai</p>

                    <div class="inline-flex bg-purple-500 p-1 rounded-full text-white">
                        <button type="button" id="btnUser"
                            onclick="setRole('user')"
                            class="px-4 py-1.5 rounded-full bg-purple-700 text-sm">
                            User
                        </button>

                        <button type="button" id="btnPublisher"
                            onclick="setRole('publisher')"
                            class="px-4 py-1.5 rounded-full text-sm">
                            Publisher
                        </button>
                    </div>

                    <input type="hidden" name="role" id="role" value="user">
                </div>

                <!-- USERNAME -->
                <div>
                    <label class="text-sm font-semibold">Username</label>
                    <input type="text"
                        class="w-full h-11 border rounded-xl px-4 mt-1 focus:ring-2 focus:ring-purple-500 outline-none">
                </div>

                <!-- PUBLISHER FIELD (PINDAH KE SINI) -->
                <div id="publisherField" class="hidden">
                    <label class="text-sm font-semibold">Nama Publisher</label>
                    <input type="text"
                        id="publisherInput"
                        placeholder="nama publisher"
                        class="w-full h-11 border rounded-xl px-4 mt-1 focus:ring-2 focus:ring-purple-500 outline-none">
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="text-sm font-semibold">Email</label>
                    <input type="email"
                        placeholder="contoh@gmail.com"
                        class="w-full h-11 border rounded-xl px-4 mt-1 focus:ring-2 focus:ring-purple-500 outline-none">
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="text-sm font-semibold">Password</label>
                    <input type="password"
                        placeholder="Masukkan Password"
                        class="w-full h-11 border rounded-xl px-4 mt-1 focus:ring-2 focus:ring-purple-500 outline-none">
                </div>

                <!-- BUTTON -->
                <button class="w-full h-11 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl font-semibold mt-2">
                    Daftar Sekarang
                </button>

                <p class="text-sm text-center text-gray-500">
                    Sudah punya akun ?
                    <a href="{{ route('login') }}" class="text-purple-500 font-medium hover:underline">
        Login di sini
    </a>
                </p> 

            </form>
        </div>

    </div>
</div>

<script>
function setRole(role) {
    const roleInput = document.getElementById('role');
    const publisherField = document.getElementById('publisherField');
    const publisherInput = document.getElementById('publisherInput');

    const btnUser = document.getElementById('btnUser');
    const btnPublisher = document.getElementById('btnPublisher');

    roleInput.value = role;

    // reset warna
    btnUser.classList.remove('bg-purple-700');
    btnPublisher.classList.remove('bg-purple-700');

    if (role === 'publisher') {
        publisherField.classList.remove('hidden');
        publisherInput.setAttribute('required', 'required');
        btnPublisher.classList.add('bg-purple-700');
    } else {
        publisherField.classList.add('hidden');
        publisherInput.removeAttribute('required');
        publisherInput.value = '';
        btnUser.classList.add('bg-purple-700');
    }
}

// default user
setRole('user');
</script>

</body>
</html>