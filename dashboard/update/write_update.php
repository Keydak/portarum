<?php if (!isset($_SESSION["id"])) { ?>
    <script>
        window.location.href = "../../index.php";
    </script>
<?php } ?>

<?php
if (isset($_GET['id'])) {

    $blog_id = mysqli_real_escape_string($conn, $_GET['id']);

    $stmt_idor = $conn->prepare("SELECT thumbnail,title,content,status,category_id FROM article JOIN profile ON article.id_profile = profile.id_profile WHERE profile.UUID = ? AND article.UUID = ?");
    $stmt_idor->bind_param("ss", $_SESSION["id"], $blog_id);
    $stmt_idor->execute();
    $result_idor = $stmt_idor->get_result();
    $row = $result_idor->fetch_assoc();

    if (!preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/', $blog_id) || $result_idor->num_rows === 0) {

        echo '<script>
        Swal.fire({
        title: "Blog ID Tidak Valid!",
        text: "Blog ID harus berupa ID yang valid.",
        icon: "error"
        }).then(() => {
          window.location.href = "../dashboard/?page=setting&action=library";
        });
    </script>';
        exit;
    }
} else {
    echo '<script>
        Swal.fire({
        title: "Blog ID Tidak Valid!",
        text: "Blog ID harus berupa ID yang valid.",
        icon: "error"
        }).then(() => {
          window.location.href = "../dashboard/?page=setting&action=library";
        });
    </script>';
    exit;
}
?>

<!-- Main Content -->
<div class="col-md-9">

    <div class="write-card">
        <h4 class="write-title">Update Blog</h4>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Thumbnail</label>
                <input type="file" name="thumbnail" class="form-control" placeholder="Masukkan thumbnail...">
            </div>

            <!-- Preview thumbnail -->
            <div class="mb-2">
                <div class="border rounded p-2 d-flex align-items-center" style="width: 150px;">
                    <img id="preview" src="./../assets/image/thumbnail/<?= $row['thumbnail'] ?>" alt="Thumbnail"
                        style="max-width: 100%; max-height: 100%; object-fit: cover;">
                </div>
                <small class="text-muted">Thumbnail sekarang</small>
            </div>
            <!-- Title -->
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="title" class="form-control" placeholder="Masukkan judul..." required value="<?= $row['title'] ?>">
            </div>

            <!-- Content -->
            <div class="mb-3">
                <label class="form-label">Konten</label>
                <textarea name="content" id="editor" class="form-control"><?= $row['content'] ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label ">Status</label>
                <select name="status" class="form-select" required>
                    <option hidden disabled selected> Pilih Status </option>
                    <option value="draft" <?= $row['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="publish" <?= $row['status'] === 'publish' ? 'selected' : '' ?>>Publish</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" id="category" required class="form-select">
                    <?php
                    $stmt_category = $conn->prepare("SELECT * FROM category");
                    $stmt_category->execute();
                    $result_category = $stmt_category->get_result();

                    foreach ($result_category as $row_category) { ?>
                        <option value="<?= $row_category['category_id'] ?>" <?= $row['category_id'] === $row_category['category_id'] ? 'selected' : '' ?>><?= $row_category['nama'] ?></option>
                    <?php }
                    ?>
                </select>
            </div>

            <!-- Button -->
            <div class="text-end">
                <button type="submit" name="Update" class="btn btn-primary btn-publish">
                    Update
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

if (isset($_POST['Update'])) {


    $title = htmlspecialchars(isset($_POST['title']) ? $_POST['title'] : '');
    $content = htmlspecialchars(isset($_POST['content']) ? $_POST['content'] : '');
    $status = htmlspecialchars(isset($_POST['status']) ? $_POST['status'] : '');
    $category = htmlspecialchars(isset($_POST['category']) ? $_POST['category'] : '');

    $ekstensi_diperbolehkan = array('jpg', 'jpeg', 'png', 'webp');
    $nama_file = $_FILES['thumbnail']['name'];
    $x = explode('.', $nama_file);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['thumbnail']['size'];
    $file_tmp = $_FILES['thumbnail']['tmp_name'];
    $file_diunggah = !empty($nama_file);


    $stmt_gambar_lama = $conn->prepare("SELECT thumbnail FROM article JOIN profile ON article.id_profile = profile.id_profile WHERE profile.UUID = ? AND article.UUID = ?");
    $stmt_gambar_lama->bind_param("ss", $_SESSION['id'], $blog_id);
    $stmt_gambar_lama->execute();
    $result = $stmt_gambar_lama->get_result();
    $row = $result->fetch_assoc();
    $gambar_lama = $row['thumbnail'];

    $tgl_upload = date("Y-m-d:H:i:s");


    if (empty($status) || empty($category) || empty($title) || empty($content)) {
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

    if ($file_diunggah) {
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 2097152) {
                $hashed_file_name = "tb-" . substr(date("dmyhis"), 0, 35) . '.' . $ekstensi;

                $stmt_update = $conn->prepare('UPDATE article SET
                thumbnail = ?,
                title = ?,
                content = ?,
                status = ?,
                created_at = ?,
                category_id = ?
                WHERE UUID = ?');
                $stmt_update->bind_param('sssssss', $hashed_file_name, $title, $content, $status, $tgl_upload, $category, $blog_id);

                if ($stmt_update->execute()) {
                    unlink("../assets/image/thumbnail/" . $gambar_lama);
                    move_uploaded_file($file_tmp, '../assets/image/thumbnail/' . $hashed_file_name);
                    echo '<script>
    Swal.fire({
        title: "Berhasil!",
        text: "Berhasil Update Data!",
        icon: "success"
    }).then(() => {
        window.location.href = "./?page=setting&action=library";
    });
</script>';
                    exit;
                } else {
                    echo '<script>
                    Swal.fire({
                        title: "Gagal!",
                        text: "Gagal Update Data!",
                        icon: "error"
                    }).then(() => {
                window.location.href="./?page=update&action=edit_blog&id=' . $blog_id . '";                        
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
            window.location.href="./?page=update&action=edit_blog&id=' . $blog_id . '";                        
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
        window.location.href="./?page=update&action=edit_blog&id=' . $blog_id . '";                        
        });
        </script>';
            exit;
        }
    } else {
        $stmt_update = $conn->prepare('UPDATE article SET
                title = ?,
                content = ?,
                status = ?,
                created_at = ?,
                category_id = ?
                WHERE UUID = ?');
        $stmt_update->bind_param('ssssss', $title, $content, $status, $tgl_upload, $category, $blog_id);

        if ($stmt_update->execute()) {
            echo '<script>
    Swal.fire({
        title: "Berhasil!",
        text: "Berhasil Update Data!",
        icon: "success"
    }).then(() => {
        window.location.href = "./?page=setting&action=library";
    });
</script>';
            exit;
        }
    }
}
?>