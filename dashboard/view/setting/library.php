<?php if (!isset($_SESSION["id"])) { ?>
    <script>
        window.location.href = "../../index.php";
    </script>
<?php } ?>

<?php
$stmt_tables = $conn->prepare("SELECT thumbnail,title,content,is_takedown,status,a.created_at,c.nama,a.UUID,a.views,a.likes FROM article a JOIN profile p JOIN category c ON a.id_profile = p.id_profile AND a.category_id = c.category_id WHERE p.UUID = ?");
$stmt_tables->bind_param("s", $_SESSION["id"]);
$stmt_tables->execute();
$result_tables = $stmt_tables->get_result();


$stmt_tables_views = $conn->prepare("SELECT SUM(a.views) as total_views,COUNT(*) as total_article,SUM(a.likes) as total_likes FROM article a JOIN profile p ON a.id_profile = p.id_profile WHERE p.UUID = ?");
$stmt_tables_views->bind_param("s", $_SESSION["id"]);
$stmt_tables_views->execute();
$result_tables_views = $stmt_tables_views->get_result();
$row_views = $result_tables_views->fetch_assoc();

if (isset($_GET['delete_id'])) {

    $uuid = mysqli_real_escape_string($conn, $_GET['delete_id']);

    if (!preg_match('/^[0-9a-fA-F-]{36}$/', $uuid)) {
        echo '<script>
            Swal.fire({
                title: "Article Tidak Ditemukan",
                text: "Artikel yang Anda cari tidak ditemukan.",
                icon: "error"
            }).then(() => {
                window.location.href = "../dashboard/?page=setting&action=library";
            });
        </script>';
        exit;
    }

    $stmt = $conn->prepare("SELECT id_profile FROM profile WHERE UUID = ?");
    $stmt->bind_param("s", $_SESSION['id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        die("User tidak valid");
    }

    $stmt = $conn->prepare("
        SELECT article_id 
        FROM article 
        WHERE UUID = ? AND id_profile = ?
    ");
    $stmt->bind_param("si", $uuid, $user['id_profile']);
    $stmt->execute();
    $article = $stmt->get_result()->fetch_assoc();

    if (!$article) {
        echo '<script>
            Swal.fire({
                title: "Article Tidak Ditemukan",
                text: "Artikel yang Anda cari tidak ditemukan.",
                icon: "error"
            }).then(() => {
                window.location.href = "../dashboard/?page=setting&action=library";
            });
        </script>';
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM article WHERE article_id = ?");
    $stmt->bind_param("i", $article['article_id']);
    $stmt->execute();

    echo '<script>
        Swal.fire({
            title: "Berhasil!",
            text: "Artikel berhasil dihapus.",
            icon: "success"
        }).then(() => {
            window.location.href = "../dashboard/?page=setting&action=library";
        });
    </script>';
}

?>

<div class="col-md-9">

    <div class="card p-3 shadow-sm mb-4">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card-box">
                    <h3><?= $row_views['total_article'] ?: 0 ?></h3>
                    <p>Articles</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box">
                    <h3><?= $row_views['total_likes'] ?: 0 ?></h3>
                    <p>Total Likes</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box">
                    <h3><?= $row_views['total_views'] ?: 0 ?></h3>
                    <p>Total Views</p>
                </div>
            </div>
        </div>
    </div>


    <div class="card p-3 shadow-sm">
        <h5>Articles</h5>
        <div class="table-responsive">


            <table id="myTable" class="table borderless table-hover align-middle ">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Thumbnail</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Actions</th>
                        <th>Views / Likes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($result_tables as $row) { ?>
                        <tr>
                            <td style="width: 50%;"><?= $row["title"] ?></td>
                            <td><?= $row["nama"] ?></td>
                            <td><img src="./../assets/image/thumbnail/<?= $row["thumbnail"] ?>" alt="Thumbnail" width="100"></td>
                            <td>
                                <div style="display: flex; gap: 6px; align-items: center;">
                                    <?php if ($row['is_takedown'] == 'YES'): ?>
                                        <span style="color: red;">(Takedown)</span>
                                    <?php endif; ?>

                                    <?php if ($row['status'] == 'draft'): ?>
                                        <span style="color: #ffcc00;">(Draft)</span>
                                    <?php endif; ?>
                                </div>
                                <p style="color: #28a745;"><?= $row['status'] == 'publish' ? '(Published)' : '' ?> </p>


                            </td>
                            <td><?= formatTanggal($row["created_at"]) ?></td>
                            <td>
                                <a href="?page=read&action=readarticle&id=<?= $row["UUID"] ?>" class="btn btn-sm btn-primary">View</a>
                                <a href="?page=update&action=edit_blog&id=<?= $row["UUID"] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="?page=setting&action=library&delete_id=<?= $row["UUID"] ?>" id="delete-article" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?= $row["views"] ?> Views</span>
                                <span class="badge bg-secondary"><?= $row["likes"] ?> Likes</span>
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
        document.querySelectorAll("#delete-article").forEach(button => {
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