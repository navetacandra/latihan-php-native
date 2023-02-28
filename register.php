<?php
include './koneksi.php';

// cek jika user memiliki session
if (isset($_SESSION['user']['id'])) {
    // redirect user ke halaman dashboard.php jika memiliki session
    header('location: ./dashboard.php');
}

// jika form di-submit
if (isset($_POST['submit'])) {
    $name = $_POST['name']; // ambil data form 'name'
    $email = $_POST['email']; // ambil data form 'email'
    $password = $_POST['password']; // ambil data form 'password'
    // enkripsi password
    $encrypted = password_hash($password, PASSWORD_DEFAULT);

    // cek jika 'name' kosong
    if (strlen($name) == 0) {
        // set session flashdata name
        $_SESSION['flashdata']['name'] = "Nama wajib di-isi!";
    }
    // cek jika 'email' kosong
    if (strlen($email) == 0) {
        // set session flashdata email
        $_SESSION['flashdata']['email'] = "Email wajib di-isi!";
    }
    // cek jika 'email' tidak sesuai dan tidak kosong
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) != 0) {
        // set session flashdata email
        $_SESSION['flashdata']['email'] = "Email tidak valid!";
    }
    // cek jika 'password' kosong
    if (strlen($password) == 0) {
        // set session flashdata password
        $_SESSION['flashdata']['password'] = "Password wajib di-isi!";
    }
    // cek jika panjang 'password' kuran dari 8 dan tidak kosong
    if (strlen($password) < 8 && strlen($password) != 0) {
        // set session flashdata password
        $_SESSION['flashdata']['password'] = "Password minimal berisi 8 karakter!";
    }

    // lanjutkan jika tidak ada session flashdata
    if (!isset($_SESSION['flashdata'])) {
        // ambil user sesuai email dari database dengan query
        $query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email';");
        $user = mysqli_fetch_array($query_user);

        if ($user == NULL) { // jika user bernilai NULL (tidak ada)
            // query insert data user ke database
            $query = mysqli_query($koneksi, "INSERT INTO users (username, email, password) VALUES ('$name', '$email', '$encrypted');");
            if ($query) { // redirect ke halaman index.php jika berhasil insert ke database
                header('location: ./index.php');
            } else { // jika gagal query insert
?>
                <script>
                    // beri alert jika akun gagal dibuat
                    alert('Gagal Membuat Akun!');
                    // reload halaman
                    window.location = window.location;
                </script>
            <?php
            }
        } else { // jika user tidak bernilai NULL
            ?>
            <script>
                // beri alert jika email sudah terdaftar
                alert('Email Sudah Terdaftar!');
                // reload halaman
                window.location = window.location;
            </script>
<?php
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- import css -->
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <!-- menengahkan elemen -->
    <div class="justify-content-center align-items-center">
        <!-- membuat container (card) -->
        <div class="container">
            <!-- membuat form dengan method post dengan action ke file ./register.php -->
            <form class="form-control" action="./register.php" method="post">
                <!-- memberi judul di form -->
                <h1 class="text-center mb-5 mt-2">DAFTAR</h1>
                <!-- input untuk nama -->
                <input class="mb-1" type="text" name="name" id="name" placeholder="Masukan Nama.." />
                <!-- tampilkan session flashdata validasi 'name' -->
                <?= isset($_SESSION['flashdata']['name']) ? '<small class="mb-3" style="color: red; margin-left: 10%;">' . $_SESSION['flashdata']['name'] . '</small>' : '' ?>
                
                <!-- input untuk email -->
                <input class="mb-1" type="text" name="email" id="email" placeholder="Masukan Email.." />
                <!-- tampilkan session flashdata validasi 'email' -->
                <?= isset($_SESSION['flashdata']['email']) ? '<small class="mb-3" style="color: red; margin-left: 10%;">' . $_SESSION['flashdata']['email'] . '</small>' : '' ?>
                
                <!-- input untuk password -->
                <input type="password" name="password" id="password" placeholder="Masukan Password.." />
                <!-- tampilkan session flashdata validasi 'password' -->
                <?= isset($_SESSION['flashdata']['password']) ? '<small style="color: red; margin-left: 10%;">' . $_SESSION['flashdata']['password'] . '</small>' : '' ?>

                <!-- button untuk submit -->
                <button class="mt-4 mb-4" type="submit" name="submit">DAFTAR</button>
                <hr />
                <!-- membuat link ke halaman index.php (halaman login) -->
                <span class="text-center mt-25">Sudah punya akun? <a href="./index.php">Masuk Disini.</a></span>
            </form>
        </div>
    </div>
</body>

</html>

<?php
// hapus session flashdata setelah menampilkan halaman
if (isset($_SESSION['flashdata'])) unset($_SESSION['flashdata']);
?>