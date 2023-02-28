<?php
include './koneksi.php';

// cek jika user memiliki session
if (isset($_SESSION['user']['id'])) {
    // redirect user ke halaman dashboard.php jika memiliki session
    header('location: ./dashboard.php');
}

// jika form di-submit
if (isset($_POST['submit'])) {
    $email = $_POST['email']; // ambil data form 'email'
    $password = $_POST['password']; //ambil data form 'password'

    // cek jika 'email' tidak valid dan tidak kosong
    if (strlen($email) == 0) {
        // set session flashdata email
        $_SESSION['flashdata']['email'] = "Email wajib di-isi!";
    }
    // cek jika 'email' kosong
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) != 0) {
        // set session flashdata email
        $_SESSION['flashdata']['email'] = "Email tidak valid!";
    }
    // cek jika 'password' kosong
    if (strlen($password) == 0) {
        // set session flashdata password
        $_SESSION['flashdata']['password'] = "Password wajib di-isi!";
    }
    // cek jika panjang 'password' kurang dari 8 dan tidak kosong
    if (strlen($password) < 8 && strlen($password) != 0) {
        // set session flashdata password
        $_SESSION['flashdata']['password'] = "Password minimal berisi 8 karakter!";
    }

    // lanjutkan jika tidak ada session flashdata
    if (!isset($_SESSION['flashdata'])) {
        // ambil user sesuai email dari database dengan query
        $query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email';");
        $user = mysqli_fetch_array($query_user);

        if ($user == NULL) {  // jika user bernilai NULL (tidak ada)
?>
            <script>
                // beri alert jika akun tidak terdaftar
                alert('Akun Tidak Terdaftar!');
                // reload halaman
                window.location = window.location;
            </script>
            <?php
        } else { // jika user tidak bernilai NULL
            // memvalidasi password sesuai dengan data password yang di database
            if (password_verify($password, $user['password'])) { // jika password sesuai
                // set data session user dengan data dari database 
                $_SESSION['user'] = $user;
                // redirect ke halaman dashboard.php
                header('location: ./dashboard.php');
            } else { // jika password tidak sesuai
            ?>
                <script>
                    // beri alert jika password salah
                    alert('Password Salah!');
                    // reload halaman
                    window.location = window.location;
                </script>
<?php
            }
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
    <title>Aplikasi Ceunah</title>
    <!-- import css -->
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <!-- menengahkan elemen -->
    <div class="justify-content-center align-items-center">
        <!-- membuat container (card) -->
        <div class="container">
            <!-- membuat form dengan method post dan action ke file ./index.php -->
            <form class="form-control" action="./index.php" method="post">
                <!-- membuat judul form -->
                <h1 class="text-center mb-5">LOGIN</h1>

                <!-- input email -->
                <input class="mb-1" type="text" name="email" id="email" placeholder="Masukan Email.." />
                <!-- tampilkan session flashdata validasi email -->
                <?= isset($_SESSION['flashdata']['email']) ? '<small class="mb-3" style="color: red; margin-left: 10%;">' . $_SESSION['flashdata']['email'] . '</small>' : '' ?>
                
                <!-- input password -->
                <input type="password" name="password" id="password" placeholder="Masukan Password.." />
                <!-- tampilkan session flashdata validasi password -->
                <?= isset($_SESSION['flashdata']['password']) ? '<small style="color: red; margin-left: 10%;">' . $_SESSION['flashdata']['password'] . '</small>' : '' ?>

                <!-- button untuk submit -->
                <button class="mt-4 mb-4" type="submit" name="submit">LOGIN</button>
                <hr />
                <!-- membuat link ke halaman ./register.php -->
                <span class="text-center mt-4">Belum punya akun? <a href="./register.php">Daftar Disini.</a></span>
            </form>
        </div>
    </div>
</body>

</html>

<?php
// hapus session flashdata setelah halaman ditampilkan
if (isset($_SESSION['flashdata'])) unset($_SESSION['flashdata']);
?>