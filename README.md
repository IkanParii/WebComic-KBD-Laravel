# WebKBD

### Cara Collaboration

1. Buka terminal pada VSCode, tekan <b>ctrl + `</b> pada keyboard.

2. Tambahkan file yang akan di push

```bash
git add .
```

3. Tuliskan pesan Commits

```bash
git commit -m "isi pesan"
```

nb: diusahakan menggunakan semantik commit <a href='https://www.codepolitan.com/blog/panduan-mendalam-tentang-github-semantic-commit/'>Klik disini untuk melihat</a>.<br>

4. Buat branch baru

```bash
git branch -M "nama-branch"
```

nb: untuk nama brach kalian bebas, tetapi kalau bisa menggunakan nama kalian sendiri agar memudahkan.

5. Lakukan push ke github

```bash
git push -u origin nama-branch
```

nb: nama branch disini, nama yg anda bikin tadi.

6. Tunggulah hingga proses selesai, kemudian buka github untuk memastikan apakah file sudah terupdate atau belum.

nb: seringlah melakukan git pull pada saat membuka vscode pertama kali, agar code dalam vscode selalu terupdate sesuai dengan github.

```
git pull
```

## Pengerjaan Laporan

### Laporan KBD

1. Role 
- user: hanya dapat membaca cerita (komik)
- ⁠admin: mengubah (mengedit, menghapus, menambahkan) user, publiser, komik.
- ⁠publiser: mengubah (menghapus dan menambahkan) komik.

2. Tech Tect: larafel dan tailwind css
- Database: mysql

3. Desain: 
- Dashboard admin (edit, user, publiser, komik)
- ⁠landing page
- ⁠page publiser ( write + edit (hasil cerita yang sudah dipublish)) dalam bentuk slide bar yang di scroll.

4. Database
- Table role
- ⁠Table user
- ⁠Table publiser
- ⁠Table Komik

5. Diagram ERD
