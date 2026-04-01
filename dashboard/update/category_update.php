<?php if (!isset($_SESSION["id"]) || $row['is_admin'] == "NO") { ?>
    <script>
        window.location.href = "../index.php";
    </script>
<?php } ?>

<?php
if (isset($_GET['id'])) {

    $category_id = mysqli_real_escape_string($conn, $_GET['id']);

    $stmt_category = $conn->prepare("SELECT * FROM category WHERE category_id = ?");
    $stmt_category->bind_param("i", $category_id);
    $stmt_category->execute();
    $result_category = $stmt_category->get_result();
    $row_category = $result_category->fetch_assoc();
} else {
    echo '<script>
        Swal.fire({
        title: "Category ID Tidak Valid!",
        text: "Category ID harus berupa ID yang valid.",
        icon: "error"
        }).then(() => {
          window.location.href = "../dashboard/?page=create&action=category";
        });
    </script>';
    exit;
}
?>

<!-- Main Content -->
<div class="col-md-9">
    <div class="write-card">
        <h4 class="write-title">Tambah Kategori Baru</h4>

        <form method="POST">

            <!-- Title -->
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <input type="text" name="Kategori" class="form-control" placeholder="Masukkan Kategori..." value="<?= htmlspecialchars($row_category['nama']) ?>" required>
            </div>


            <!-- Button -->
            <div class="text-end">
                <button type="submit" name="update" class="btn btn-primary btn-publish">
                    Update
                </button>
            </div>

        </form>
    </div>
</div>



<?php

if (isset($_POST['update'])) {


    $category = htmlspecialchars(isset($_POST['Kategori']) ? $_POST['Kategori'] : '');



    if (empty($category)) {
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

    $tgl_upload = date("Y-m-d:H:i:s");

    $stmt_insert = $conn->prepare('UPDATE category SET nama = ?, created_at = ? WHERE category_id = ?');
    $stmt_insert->bind_param('ssi', $category, $tgl_upload, $category_id);

    if ($stmt_insert->execute()) {
        echo '<script>
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Kategori berhasil diperbarui!",
                        icon: "success"
                    }).then(() => {
                        window.location.href = "./?page=create&action=category";
                    });
                </script>';
        exit;
    }
}
?>