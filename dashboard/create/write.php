<?php if (!isset($_SESSION["id"])){ ?>
   <script>
    window.location.href = "../../index.php";
   </script>
<?php } ?>

<!-- Main Content -->
<div class="col-md-9">

    <div class="write-card">
        <h4 class="write-title">Buat Blog Baru</h4>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Thumbnail</label>
                <input type="file" name="thumbnail" class="form-control" placeholder="Masukkan thumbnail..." required>
            </div>
            <!-- Title -->
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="title" class="form-control" placeholder="Masukkan judul..." required>
            </div>

            <!-- Content -->
            <div class="mb-3">
                <label class="form-label">Konten</label>
                <textarea name="content" id="editor" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label ">Status</label>
                <select name="status" class="form-select" required>
                    <option hidden disabled selected> Pilih Status </option>
                    <option value="draft">Draft</option>
                    <option value="publish">Publish</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" id="category" required class="form-select">
                    <option hidden disabled selected>Pilih Category</option>
                    <?php
                    $stmt_category = $conn->prepare("SELECT * FROM category");
                    $stmt_category->execute();
                    $result_category = $stmt_category->get_result();

                    while ($row_category = $result_category->fetch_assoc()) {
                        echo "<option value='" . $row_category['category_id'] . "'>" . $row_category['nama'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Button -->
            <div class="text-end">
                <button type="submit" name="create" class="btn btn-primary btn-publish">
                    Create
                </button>
            </div>

        </form>
    </div>
</div>


<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>

<?php


if (isset($_POST['create'])) {


    $stmt_id = $conn->prepare("SELECT id_profile FROM profile WHERE UUID = ?");
    $stmt_id->bind_param("s", $_SESSION['id']);
    $stmt_id->execute();
    $result_id = $stmt_id->get_result();

    $title = htmlspecialchars( isset($_POST['title']) ? $_POST['title'] : '');
    $content = htmlspecialchars( isset($_POST['content']) ? $_POST['content'] : '');
    $status = htmlspecialchars( isset($_POST['status']) ? $_POST['status'] : '');
    $category = htmlspecialchars( isset($_POST['category']) ? $_POST['category'] : '');


    $ekstensi_diperbolehkan = array('jpg', 'jpeg', 'png', 'webp');
    $nama_file = $_FILES['thumbnail']['name'];
    $x = explode('.', $nama_file);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['thumbnail']['size'];
    $file_tmp = $_FILES['thumbnail']['tmp_name'];
    $file_diunggah = !empty($nama_file);


    if (empty($status) || empty($category) || empty($title) || empty($content) || !$file_diunggah) {
        echo '<script>
                    Swal.fire({
                        title: "Gagal!",
                        text: "Ada field yang kosong!",
                        icon: "error"
                    }).then(() => {
                            window.location.href = "./?page=create&action=write";
                        });
                </script>';
        exit;
        
    }
    $no = "NO";

    $tgl_upload = date("Y-m-d:H:i:s");

    if ($result_id->num_rows === 1) {
        $row = mysqli_fetch_assoc($result_id);


        if ($file_diunggah) {
            if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                if ($ukuran < 2097152) {
                    $hashed_file_name = "tb-" . substr(date("dmyhis"), 0, 35) . '.' . $ekstensi;

                    $stmt_insert = $conn->prepare('INSERT INTO article (UUID,thumbnail,title,content,status,is_takedown,created_at,id_profile,category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
                    $stmt_insert->bind_param('sssssssss', $uuid, $hashed_file_name, $title, $content, $status, $no, $tgl_upload, $row['id_profile'], $category);

                    if ($stmt_insert->execute()) {
                        move_uploaded_file($file_tmp, '../assets/image/thumbnail/' . $hashed_file_name);
                        echo '<script>
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Blog berhasil dibuat!",
                        icon: "success"
                    }).then(() => {
                        window.location.href = "./?page=read&action=library";
                    });
                </script>';
                        exit;
                    } else {
                        echo '<script>
                    Swal.fire({
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat menyimpan data!",
                        icon: "error"
                    }).then(() => {
                            window.location.href = "./?page=create&action=write";
                        });
                </script>';
                        exit;
                    }
                } else {
                    echo '<script>
                Swal.fire({
                    title: "Gagal!",
                    text: "Ukuran tidak lebih dari 2mb!",
                    icon: "error"
                }).then(() => {
                        window.location.href = "./?page=create&action=write";
                    });
            </script>';
                    exit;
                }
            } else {
                echo '<script>
            Swal.fire({
                title: "Gagal!",
                text: "Format harus jpg, jpeg, atau png!",
                icon: "error"
            }).then(() => {
                    window.location.href = "./?page=create&action=write";
                });
        </script>';
                exit;
            }
        } else {
            echo '<script>
        Swal.fire({
            title: "Gagal!",
            text: "Mohon masukkan file yang benar!",
            icon: "error"
        }).then(() => {
                window.location.href = "./?page=create&action=write";
            });
    </script>';
            exit;
        }
    }
}

?>