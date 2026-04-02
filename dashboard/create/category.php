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

    <div class="card p-3 shadow-sm mt-3">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" style="width: 15px;">
                                        <path d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z" />
                                    </svg> Edit
                                </a>
                                <?php
                                if ($row["total_article"] == 0) { ?>
                                    <a href="?page=create&action=category&delete_id=<?= $row['category_id'] ?>" class="btn btn-sm btn-outline-danger" id="delete-category">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" style="width: 15px;">
                                            <path d="M232.7 69.9L224 96L128 96C110.3 96 96 110.3 96 128C96 145.7 110.3 160 128 160L512 160C529.7 160 544 145.7 544 128C544 110.3 529.7 96 512 96L416 96L407.3 69.9C402.9 56.8 390.7 48 376.9 48L263.1 48C249.3 48 237.1 56.8 232.7 69.9zM512 208L128 208L149.1 531.1C150.7 556.4 171.7 576 197 576L443 576C468.3 576 489.3 556.4 490.9 531.1L512 208z" />
                                        </svg> Delete
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