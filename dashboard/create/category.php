<?php if (!isset($_SESSION["id"]) || $row['is_admin'] == 'NO') { ?>
    <script>
        window.location.href = "../index.php";
    </script>
<?php } ?>

<?php
$stmt_category = $conn->prepare("
    SELECT c.*, COUNT(a.article_id) as total_article
    FROM category c
    LEFT JOIN article a ON c.category_id = a.category_id
    GROUP BY c.category_id
");
$stmt_category->execute();
$result_category = $stmt_category->get_result();

if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $stmt_delete = $conn->prepare("DELETE FROM category WHERE category_id = ?");
    $stmt_delete->bind_param("i", $delete_id);
    if ($stmt_delete->execute()) {
        echo '<script>
            Swal.fire({
                title: "Berhasil!",
                text: "Kategori berhasil dihapus!",
                icon: "success"
            }).then(() => {
                window.location.href = "./?page=create&action=category";
            });
        </script>';
        exit;
    } else {
        echo '<script>
            Swal.fire({
                title: "Gagal!",
                text: "Kategori gagal dihapus!",
                icon: "error"
            }).then(() => {
                window.location.href = "./?page=create&action=category";
            });
        </script>';
        exit;
    }
}

if (isset($_POST['create'])) {

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

    $stmt_insert = $conn->prepare('INSERT INTO category (nama,created_at) VALUES (?, ?)');
    $stmt_insert->bind_param('ss', $category, $tgl_upload);

    if ($stmt_insert->execute()) {
        echo '<script>
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Kategori berhasil ditambahkan!",
                        icon: "success"
                    }).then(() => {
                        window.location.href = "./?page=create&action=category";
                    });
                </script>';
        exit;
    }
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
                <input type="text" name="Kategori" class="form-control" placeholder="Masukkan Kategori..." required>
            </div>


            <!-- Button -->
            <div class="text-end">
                <button type="submit" name="create" class="btn btn-primary btn-publish">
                    Create
                </button>
            </div>

        </form>
    </div>

    <div class="card p-3 shadow-sm">
        <h5>Kategori</h5>
        <div class="table-responsive">


            <table id="myTable-category" class="table borderless table-hover align-middle ">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>TGL</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result_category as $row) { ?>
                        <tr>
                            <td><?= $row["nama"] ?></td>
                            <td><?= ($row["created_at"]) ?></td>
                            <td>
                                <a href="?page=update&action=edit_category&id=<?= $row['category_id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <?php
                                if ($row["total_article"] == 0) { ?>
                                    <a href="?page=create&action=category&delete_id=<?= $row['category_id'] ?>" class="btn btn-sm btn-outline-danger" id="delete-category">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                <?php   }
                                ?>
                            </td>
                        </tr>

                    <?php }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("#delete-category").forEach(button => {
            button.addEventListener("click", function(event) {
                event.preventDefault();
                let url = this.href;

                Swal.fire({
                    title: "Yakin?",
                    text: "Data akan dihapus secara permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    });
</script>