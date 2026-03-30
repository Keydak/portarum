<?php

include "./config/koneksi.php";


?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Portarum - Writeup & Tech Blog</title>
  <link rel="stylesheet" href="./assets/css/bootstrap-5-3-2.css">
  <link rel="stylesheet" href="./assets/css/sweetalert.min.css">
  <style>
    body {
      background: #f8f9fa;
    }

    .hero {
      padding: 80px 0;
    }

    .feature-icon {
      font-size: 30px;
    }

    .article-card img {
      height: 150px;
      object-fit: cover;
    }

    footer {
      font-size: 14px;
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">Portarum</a>

      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item ms-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#authModal">
              Login
            </button>
          </li>

        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="container">
      <div class="row align-items-center">

        <div class="col-md-6">
          <h1 class="fw-bold mb-3">
            Portarum — Platform Artikel & Blog untuk Semua
          </h1>

          <p class="text-muted">
            Portarum adalah tempat untuk menulis dan membaca berbagai artikel dari pengguna.
            Mulai dari teknologi, pengalaman pribadi, tutorial, hingga topik menarik lainnya.
            Semua orang bisa berbagi cerita dan insight.
          </p>

          <div class="mt-4">
            <a href="#" class="btn btn-primary me-2">Mulai Menulis</a>
          </div>
        </div>

        <div class="col-md-6 text-center">
          <img src="https://via.placeholder.com/500x300" class="img-fluid rounded">
        </div>

      </div>
    </div>
  </section>

  <!-- MODAL AUTH -->
  <div class="modal fade" id="authModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">

        <!-- HEADER -->
        <div class="modal-header border-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY -->
        <div class="modal-body p-4 pt-0">

          <!-- Tabs -->
          <ul class="nav nav-pills mb-3 justify-content-center">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#loginTab">
                Login
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="pill" data-bs-target="#registerTab">
                Register
              </button>
            </li>
          </ul>

          <div class="tab-content">

            <!-- LOGIN -->
            <div class="tab-pane fade show active" id="loginTab">
              <form method="POST">
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Username" name="username">
                </div>

                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="Password" name="password">
                </div>

                <button class="btn btn-primary w-100" type="submit" name="signin">Login</button>

                <p class="text-center mt-2">
                  Belum punya akun?
                  <a href="#" onclick="document.querySelector('[data-bs-target=\'#registerTab\']').click()">Daftar</a>
                </p>
              </form>
            </div>



            <!-- REGISTER -->
            <div class="tab-pane fade" id="registerTab">
              <form method="post">
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Nama" name="nama">
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Username" name="username">
                </div>

                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="Password" name="password">
                </div>

                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="Ulangi Password" name="confirm_password">
                </div>

                <button class="btn btn-success w-100" type="submit" name="signup">Register</button>
              </form>
            </div>

          </div>

        </div>

      </div>
    </div>
  </div>

  <!-- ABOUT -->
  <section class="container my-5">
    <div class="text-center mb-5">
      <h3 class="fw-bold">Apa itu Portarum?</h3>
      <p class="text-muted">Platform blog terbuka untuk semua topik</p>
    </div>

    <div class="row text-center">

      <div class="col-md-4">
        <h5>✍️ Tulis Apa Saja</h5>
        <p class="text-muted">
          Buat artikel tentang topik apa pun—teknologi, pengalaman, opini, atau tutorial.
        </p>
      </div>

      <div class="col-md-4">
        <h5>🌍 Berbagi dengan Dunia</h5>
        <p class="text-muted">
          Publikasikan tulisanmu dan biarkan orang lain membaca serta belajar darinya.
        </p>
      </div>

      <div class="col-md-4">
        <h5>📚 Eksplorasi Konten</h5>
        <p class="text-muted">
          Temukan berbagai artikel menarik dari penulis lain dengan topik yang beragam.
        </p>
      </div>

    </div>
  </section>

  <!-- ARTIKEL -->
  <section class="container my-5">
    <h4 class="fw-bold mb-4">Artikel Terbaru</h4>

    <div class="row g-4">

      <!-- FEATURED (besar) -->
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
          <img src="https://via.placeholder.com/600x300" class="card-img-top" style="height:250px; object-fit:cover;">
          <div class="card-body">
            <h5 class="fw-bold">
              Cara Membangun Personal Branding Lewat Blog
            </h5>
            <p class="text-muted small">
              Tips membangun identitas digital melalui tulisan dan konten berkualitas.
            </p>
            <small class="text-muted">Lifestyle • 5 menit baca</small>
          </div>
        </div>
      </div>

      <!-- RIGHT LIST -->
      <div class="col-lg-6">

        <div class="d-flex mb-4">
          <div style="width:120px; height:80px; overflow:hidden; border-radius:6px;">
            <img src="https://via.placeholder.com/300x150"
              style="width:100%; height:100%; object-fit:cover;">
          </div>
          <div class="ms-3">
            <h6 class="mb-1">Belajar Laravel untuk Pemula</h6>
            <small class="text-muted">Programming • 8 menit baca</small>
          </div>
        </div>

        <div class="d-flex mb-4">
          <div style="width:120px; height:80px; overflow:hidden; border-radius:6px;">
            <img src="https://via.placeholder.com/300x150"
              style="width:100%; height:100%; object-fit:cover;">
          </div>
          <div class="ms-3">
            <h6 class="mb-1">Tips Menulis Artikel yang Menarik</h6>
            <small class="text-muted">Writing • 4 menit baca</small>
          </div>
        </div>

        <div class="d-flex">
          <div style="width:120px; height:80px; overflow:hidden; border-radius:6px;">
            <img src="https://via.placeholder.com/300x150"
              style="width:100%; height:100%; object-fit:cover;">
          </div>
          <div class="ms-3">
            <h6 class="mb-1">Cara Konsisten Menulis Setiap Hari</h6>
            <small class="text-muted">Productivity • 6 menit baca</small>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- CTA -->
  <section class="bg-white py-5 mt-5 border-top">
    <div class="container text-center">
      <h4 class="fw-bold mb-3">Mulai Bangun Portofolio Kamu</h4>
      <p class="text-muted">
        Tulis pengalaman bug hunting, share tutorial, dan bantu komunitas berkembang.
      </p>
      <a href="#" class="btn btn-primary">Mulai Sekarang</a>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-white border-top mt-5 pt-4 pb-3">
    <div class="container">
      <div class="row">

        <div class="col-md-4 mb-3">
          <h5 class="fw-bold">Portarum</h5>
          <p class="text-muted">
            Platform artikel dan blog tempat pengguna berbagi berbagai cerita, ide, dan pengetahuan.
          </p>
        </div>

        <div class="col-md-2 mb-3">
          <h6 class="fw-bold">Kategori</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="text-decoration-none text-muted">Bug Hunting</a></li>
            <li><a href="#" class="text-decoration-none text-muted">Programming</a></li>
            <li><a href="#" class="text-decoration-none text-muted">Tech</a></li>
          </ul>
        </div>

        <div class="col-md-3 mb-3">
          <h6 class="fw-bold">Informasi</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="text-decoration-none text-muted">Tentang</a></li>
            <li><a href="#" class="text-decoration-none text-muted">Kontak</a></li>
            <li><a href="#" class="text-decoration-none text-muted">Privasi</a></li>
          </ul>
        </div>

        <div class="col-md-3 mb-3">
          <h6 class="fw-bold">Newsletter</h6>
          <div class="input-group">
            <input type="email" class="form-control" placeholder="Email">
            <button class="btn btn-primary">Subscribe</button>
          </div>
        </div>

      </div>

      <div class="text-center border-top pt-3 mt-3">
        <small class="text-muted">
          © 2025 Portarum. All rights reserved.
        </small>
      </div>
    </div>
  </footer>

  <script src="./assets/js/jquery.min.js"></script>
  <script src="./assets/js/sweetalert.min.js"></script>
  <script src="./assets/js/bootstrap-5-3-2.js"></script>




</body>

</html>

<?php

if (isset($_POST["signin"])) {
  $username = mysqli_real_escape_string($conn, strtolower($_POST["username"]));
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $stmt_login = $conn->prepare("SELECT username,password,UUID FROM profile WHERE username = ?");
  $stmt_login->bind_param("s", $username);
  $stmt_login->execute();
  $result_login = $stmt_login->get_result();

  if ($result_login->num_rows === 1) {
    $row = mysqli_fetch_assoc($result_login);
    if (password_verify($password, $row['password'])) {
        $_SESSION["id"] = $row['UUID'];
       echo '<script>
        Swal.fire({
        title: "Login Berhasil!",
        text: "Selamat datang di Portarum!",
        icon: "success"
        }).then(() => {
          window.location.href = "./dashboard/?page=home";
        });
    </script>';
    } else {
      echo '<script>
                Swal.fire({
                title: "Login Gagal!",
                text: "Username atau Password salah!",
                icon: "error"
                });
            </script>';
    }
  } else {
    echo '<script>
        Swal.fire({
        title: "Login Gagal!",
        text: "Username atau Password salah!",
        icon: "error"
        });
    </script>';
  }
} elseif (isset($_POST['signup'])) {
  $username = mysqli_real_escape_string($conn, ($_POST["username"]));
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);

  $stmt_register = $conn->prepare("SELECT username FROM profile WHERE username = ?");
  $stmt_register->bind_param("s", $username);
  $stmt_register->execute();
  $result_register = $stmt_register->get_result();

  if ($result_register->num_rows === 1) {
    echo '<script>
        Swal.fire({
        title: "Pendaftaran Gagal!",
        text: "Username sudah digunakan!",
        icon: "error"
        });
    </script>';
  } else {
    if ($password !== $confirm_password) {
      echo '<script>
          Swal.fire({
          title: "Pendaftaran Gagal!",
          text: "Password dan Konfirmasi Password tidak cocok!",
          icon: "error"
          });
      </script>';
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt_insert = $conn->prepare("INSERT INTO profile (UUID, username, password,nama) VALUES (?, ?, ?, ?)");
      $stmt_insert->bind_param("ssss", $uuid, $username, $hashed_password, $nama);
      $stmt_insert->execute();
      $_SESSION["id"] = $uuid;
      echo '<script>
        Swal.fire({
        title: "Pendaftaran Berhasil!",
        text: "Silakan login dengan akun Anda!",
        icon: "success"
        }).then(() => {
          window.location.href = "./dashboard/index.php";
        });
    </script>';
    }
  }
}

?>