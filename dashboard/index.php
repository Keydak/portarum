<?php
include "../config/koneksi_s_login.php";


?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Portarum</title>
  <script src="../assets/js/ckeditor.js"></script>
  <link rel="stylesheet" href="../assets/css/sweetalert.min.css">
  <link rel="stylesheet" href="../assets/css/bootstrap-5-3-2.css">

  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/sweetalert.min.js"></script>
  <script src="../assets/js/bootstrap-5-3-2.js"></script>

  <link rel="stylesheet" href="../assets/css/datatables.min.css">
  <script src="../assets/js/datatables.min.js"></script>

  <link rel="stylesheet" href="../assets/css/select2.min.css">
  <script src="../assets/js/select2.min.js"></script>
  <style>
    body {
      background: #f8f9fa;
    }

    .hero-img {
      height: 300px;
      object-fit: cover;
      border-radius: 10px;
    }

    .card-img-top {
      height: 120px;
      object-fit: cover;
    }

    :root {
      --navbar-height: 70px;
    }

    .sidebar-sticky {
      position: sticky;
      top: var(--navbar-height);
    }

    .select2-container .select2-selection--single {
      height: 38px;
      border: 1px solid #ced4da;
      border-radius: 0.375rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 38px;
    }

    /* hilangkan semua border */
table.dataTable,
table.dataTable th,
table.dataTable td {
    border: none !important;
}

/* header lebih soft */
table.dataTable thead {
    color: #6c757d;
    font-size: 14px;
}

/* row clean + hover halus */
table.dataTable tbody tr {
    border-radius: 8px;
    transition: all 0.2s ease;
}

table.dataTable tbody tr:hover {
    background-color: #f8f9fa;
}

/* kasih jarak antar row biar airy */
table.dataTable tbody td {
    padding: 12px 8px;
}

/* search biar simple */
.dataTables_filter input {
    border: none;
    border-bottom: 1px solid #ddd;
    border-radius: 0;
    outline: none;
}
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">Portarum</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto align-items-center">

          <li class="nav-item">
            <a class="nav-link active" href="?page=home">Home</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="?page=read&action=search">Search</a>
          </li>

          <!-- USER DROPDOWN -->
          <li class="nav-item dropdown">
            <a href="#" class="d-flex align-items-center nav-link dropdown-toggle" data-bs-toggle="dropdown">

              <img src="https://i.pravatar.cc/40"
                class="rounded-circle me-2"
                width="32" height="32">

              <span class="fw-semibold">John Doe</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li>
                <h6 class="dropdown-header">Akun</h6>
              </li>
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="#">Settings</a></li>
              <li><a class="dropdown-item" href="?page=create&action=write">Write</a></li>
              <li><a class="dropdown-item" href="?page=setting&action=library">Library</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-danger" href="./logout.php">Logout</a></li>
            </ul>
          </li>

        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <!-- <div class="container my-4">
    <div class="row align-items-center">
      <div class="col-md-6">
        <small class="text-primary">Headline</small>
        <h2 class="fw-bold mt-2">
          Apa Itu Cancel Culture, Fenomena Sosial yang Kontroversial
        </h2>
        <p class="text-muted">
          Cancel culture adalah praktik sosial saat seseorang diboikot karena perilaku atau ucapan kontroversial.
        </p>
        <small class="text-muted">18 Februari 2025</small><br>
        <a href="#" class="text-primary text-decoration-none">Baca Selengkapnya →</a>
      </div>
      <div class="col-md-6">
        <img src="https://via.placeholder.com/600x300" class="w-100 hero-img">
      </div>
    </div>
  </div> -->



  <!-- Berita Terpopuler -->
  <div class="container my-5">
    <div class="row">
      <?php

      switch (@$_GET['page']) {
        case "home":
          include "./view/home.php";
          break;
        case "create":
          switch (@$_GET['action']) {
            case "write":
              include "./view/layout/sidebar.php";
              include "./create/write.php";
              break;
            default:
              include "./view/home.php";
          }
          break;
           case "update":
          switch (@$_GET['action']) {
            case "edit_blog":
              include "./view/layout/sidebar.php";
              include "./update/write_update.php";
              break;
            default:
              include "./view/home.php";
          }
          break;
        case "read":
          switch (@$_GET['action']) {
            case "search":
              include "./view/read/search.php";
              break;
            case "readarticle":
              include "./view/read/readarticle.php";
              break;
            default:
              include "./view/home.php";
          }
          break;
        case "setting":
          switch (@$_GET['action']) {
            case "library":
              include "./view/layout/sidebar.php";
              include "./view/setting/library.php";
              break;
            case "profile":
              include "./view/layout/sidebar.php";
              include "./view/setting/profile.php";
              break;
            default:
              include "./view/home.php";
          }
          break;
        default:
          include "./view/home.php";
      } ?>
    </div>
  </div>
  <!-- Rekomendasi -->
  <!-- <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold">Rekomendasi Untuk Anda</h5>
      <input type="text" class="form-control w-25" placeholder="Cari disini...">
    </div>

    <div class="row g-3">
      <div class="col-md-3">
        <img src="https://i.imgflip.com/402h0h.png" class="img-fluid rounded">
      </div>
      <div class="col-md-3">
        <img src="https://i.imgflip.com/402h0h.png" class="img-fluid rounded">
      </div>
      <div class="col-md-3">
        <img src="https://i.imgflip.com/402h0h.png" class="img-fluid rounded">
      </div>
      <div class="col-md-3">
        <img src="https://i.imgflip.com/402h0h.png" class="img-fluid rounded">
      </div>
    </div>

  </div> -->


  <!-- Footer -->
  <!-- <footer class="bg-white border-top mt-5 pt-4 pb-3">
    <div class="container">
      <div class="row">

     
        <div class="col-md-4 mb-3">
          <h5 class="fw-bold">Portarum</h5>
          <p class="text-muted">
            Portal berita terkini yang menyajikan informasi terpercaya dan terbaru setiap hari.
          </p>
        </div>

    
        <div class="col-md-2 mb-3">
          <h6 class="fw-bold">Kategori</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="text-decoration-none text-muted">Terbaru</a></li>
            <li><a href="#" class="text-decoration-none text-muted">Teknologi</a></li>
            <li><a href="#" class="text-decoration-none text-muted">Ekonomi</a></li>
            <li><a href="#" class="text-decoration-none text-muted">Olahraga</a></li>
          </ul>
        </div>

     
        <div class="col-md-3 mb-3">
          <h6 class="fw-bold">Informasi</h6>
          <ul class="list-unstyled">
            <li><a href="#" class="text-decoration-none text-muted">Tentang Kami</a></li>
            <li><a href="#" class="text-decoration-none text-muted">Kontak</a></li>
            <li><a href="#" class="text-decoration-none text-muted">Kebijakan Privasi</a></li>
          </ul>
        </div>

    
        <div class="col-md-3 mb-3">
          <h6 class="fw-bold">Newsletter</h6>
          <p class="text-muted">Dapatkan berita terbaru langsung ke email Anda.</p>
          <div class="input-group">
            <input type="email" class="form-control" placeholder="Email Anda">
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
  -->


  <script>
    $(document).ready(function() {
      $('#category').select2({
        placeholder: "Pilih Category",
        width: '100%'
      });
    });



    $(document).ready(function() {
      $('#myTable').DataTable({
        paging: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50],
        searching: true,
        ordering: true,
        info: false
      });
    });
  </script>
</body>

</html>