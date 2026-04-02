<?php if (!isset($_SESSION["id"])) { ?>
    <script>
        window.location.href = "../../index.php";
    </script>
<?php } ?>


<?php
if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($conn, $_SESSION['id']);
    $blog_id = mysqli_real_escape_string($conn, $_GET['id']);

    // validasi UUID
    if (!preg_match('/^[0-9a-fA-F-]{36}$/', $blog_id)) {
        echo '<script>
            Swal.fire({
                title: "ID Artikel Tidak Valid",
                text: "ID artikel yang Anda masukkan tidak valid.",
                icon: "error"
            }).then(() => {
                window.location.href = "../dashboard/?page=home";
            });
        </script>';
        exit;
    }

    // ambil data artikel dan penulis
    $stmt = $conn->prepare("
        SELECT 
            a.thumbnail,
            a.title,
            a.content,
            a.category_id,
            p.nama,
            a.views,
            a.created_at,
            a.status,
            a.is_takedown,
            p.photo,
            p.username,
            p.UUID as author_uuid
        FROM article a
        JOIN profile p ON a.id_profile = p.id_profile
        WHERE a.UUID = ?
    ");
    $stmt->bind_param("s", $blog_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    //cek akses
    $stmt_admin = $conn->prepare("
        SELECT 
           is_admin
        FROM profile 
        WHERE UUID = ?
    ");
    $stmt_admin->bind_param("s", $id);
    $stmt_admin->execute();

    $result_admin = $stmt_admin->get_result();
    $row_admin = $result_admin->fetch_assoc();


    // cek akses
    if (
        $row['status'] === 'draft' &&
        $_SESSION['id'] !== $row['author_uuid'] && $row_admin['is_admin'] === 'NO'
    ) {
        echo '<script>
            Swal.fire({
                title: "Artikel Tidak Ditemukan",
                text: "Artikel yang Anda cari tidak ditemukan.",
                icon: "error"
            }).then(() => {
                window.location.href = "../dashboard/?page=home";
            });
        </script>';
        exit;
    }
} else {
    header("Location: ../dashboard/?page=home");
    exit;
}

$user_id = mysqli_real_escape_string($conn, $_SESSION['id']);

$article_id = mysqli_real_escape_string($conn, $_GET['id']);

$stmt_view = $conn->prepare("SELECT id_profile FROM profile WHERE UUID = ?");
$stmt_view->bind_param("s", $user_id);
$stmt_view->execute();
$result_view = $stmt_view->get_result();
$row_view = $result_view->fetch_assoc();


$stmt_view_article = $conn->prepare("SELECT article_id FROM article WHERE UUID = ?");
$stmt_view_article->bind_param("s", $article_id);
$stmt_view_article->execute();
$result_view_article = $stmt_view_article->get_result();
$row_view_article = $result_view_article->fetch_assoc();

$stmt_view_insert = $conn->prepare("
    INSERT IGNORE INTO article_view (article_id, id_profile, viewed_at)
    VALUES (?, ?, NOW())
");
$stmt_view_insert->bind_param("ss", $row_view_article['article_id'], $row_view['id_profile']);
$stmt_view_insert->execute();


if ($stmt_view_insert->affected_rows > 0) {

    $stmt2 = $conn->prepare("
        UPDATE article 
        SET views = views + 1 
        WHERE article_id = ?
    ");
    $stmt2->bind_param("s", $row_view_article['article_id']);
    $stmt2->execute();
}

//ambil total like
$stmt_total_like = $conn->prepare("SELECT COUNT(*) as total FROM article_like WHERE article_id=?");
$stmt_total_like->bind_param("s", $row_view_article['article_id']);
$stmt_total_like->execute();
$total_like = $stmt_total_like->get_result()->fetch_assoc()['total'];

// cek user sudah like
$stmt = $conn->prepare("SELECT 1 FROM article_like WHERE article_id=? AND id_profile=?");
$stmt->bind_param("ss", $row_view_article['article_id'], $row_view['id_profile']);
$stmt->execute();
$isLiked = $stmt->get_result()->num_rows > 0;

// admin takedown
if (isset($_POST['takedown']) && $row_admin['is_admin'] === "YES") {

    $stmt_article_takedown = $conn->prepare("UPDATE article SET is_takedown = 'YES', status = 'draft' WHERE article_id = ?");
    $stmt_article_takedown->bind_param("s", $row_view_article['article_id']);
    if ($stmt_article_takedown->execute()) {
        echo '<script>
            Swal.fire({
                title: "Artikel Berhasil Ditakedown",
                text: "Artikel telah berhasil ditakedown.",
                icon: "success"
            }).then(() => {
                window.location.href = "";
            });
        </script>';
        exit;
    }
}

//admin untakedown
if (isset($_POST['untakedown']) && $row_admin['is_admin'] === "YES") {

    $stmt_article_takedown = $conn->prepare("UPDATE article SET is_takedown = 'NO' WHERE article_id = ?");
    $stmt_article_takedown->bind_param("s", $row_view_article['article_id']);
    if ($stmt_article_takedown->execute()) {
        echo '<script>
            Swal.fire({
                title: "Artikel Berhasil Di-Untakedown",
                text: "Artikel telah berhasil di-untakedown.",
                icon: "success"
            }).then(() => {
                window.location.href = "";
            });
        </script>';
        exit;
    }
}

?>

<div class="col-lg-8 col-xl-7">
    <?php
    if ($row_admin['is_admin'] === "YES" && $row['is_takedown'] === 'YES') { ?>
        <div class="alert alert-danger" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" style="width: 25px;">
                <path d="M431.2 476.5L163.5 208.8C141.1 240.2 128 278.6 128 320C128 426 214 512 320 512C361.5 512 399.9 498.9 431.2 476.5zM476.5 431.2C498.9 399.8 512 361.4 512 320C512 214 426 128 320 128C278.5 128 240.1 141.1 208.8 163.5L476.5 431.2zM64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576C178.6 576 64 461.4 64 320z" />
            </svg>
            Anda <strong>Mengtakedown</strong> Artikel ini telah.
        </div>
    <?php } else { ?>
        <?php
        if ($row['status'] === 'draft') { ?>
            <div class="alert alert-warning" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" style="width: 25px;">
                    <path d="M192 64C156.7 64 128 92.7 128 128L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 234.5C512 217.5 505.3 201.2 493.3 189.2L386.7 82.7C374.7 70.7 358.5 64 341.5 64L192 64zM453.5 240L360 240C346.7 240 336 229.3 336 216L336 122.5L453.5 240z" />
                </svg>
                Artikel ini masih dalam status <strong>Draft</strong>. Hanya Author dan Admin yang dapat melihatnya.
            </div>
        <?php }
        ?>

        <?php
        if ($row['is_takedown'] === 'YES') { ?>
            <div class="alert alert-danger" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" style="width: 25px;">
                    <path d="M431.2 476.5L163.5 208.8C141.1 240.2 128 278.6 128 320C128 426 214 512 320 512C361.5 512 399.9 498.9 431.2 476.5zM476.5 431.2C498.9 399.8 512 361.4 512 320C512 214 426 128 320 128C278.5 128 240.1 141.1 208.8 163.5L476.5 431.2zM64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576C178.6 576 64 461.4 64 320z" />
                </svg>
                Artikel ini telah <strong>Ditakedown</strong> oleh admin.
            </div>
        <?php }
        ?>
    <?php }
    ?>
    <!-- Title -->
    <h1 class="fw-bold mb-3">
        <?php echo htmlspecialchars($row['title']); ?>
    </h1>
    <hr>


    <!-- Author -->
    <div class="d-flex align-items-center mb-4">
        <img src="./../assets/image/profile/<?php echo htmlspecialchars($row['photo']); ?>" class="rounded-circle me-2" width="40" height="40">
        <div>
            <div class="fw-semibold"><a style="text-decoration: none; color: inherit;" href="?page=read&action=profile&username=<?= $row['username'] ?>" target="_self" rel="noopener noreferrer"><?= htmlspecialchars($row['username']) ?></a></div>
            <small class="text-muted"><?php echo htmlspecialchars(formatTanggalRead($row['created_at'])); ?> </small>
        </div>
    </div>
    <!-- Cover Image -->
    <div style="width:600px; aspect-ratio:16/10; overflow:hidden; border-radius:8px;">
        <img src="../assets/image/thumbnail/<?= $row['thumbnail'] ?>"
            style="width:100%; height:100%; object-fit:contain;">
    </div>
    <hr>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <button id="likeBtn" class="btn btn-sm <?= $isLiked ? 'btn-primary' : 'btn-outline-primary' ?>">
                👍 Like
            </button>
            <span id="likeCount"><?= $total_like ?></span>
        </div>
        <?php
        if ($row_admin['is_admin'] === "YES") { ?>
            <form method="POST">
                <?php
                if ($row['is_takedown'] === 'YES') { ?>

                    <button class="btn btn-sm btn-outline-danger" name="untakedown">Untakedown</button>

                <?php } else { ?>
                    <button class="btn btn-sm btn-outline-danger" name="takedown">Takedown</button>
                <?php }
                ?>
            </form>
        <?php }
        ?>
        <small class="text-muted"><?php echo $row['views']; ?> views</small>
    </div>
    <hr>
    <!-- Content -->
    <div class="article-content">

        <?php echo $Parsedown->text($row['content']); ?>
    </div>

    <!-- Divider -->
    <hr class="my-5">

    <!-- Actions -->
</div>

<script>
    $('#likeBtn').click(function() {
        $.ajax({
            url: '../config/like.php',
            method: 'POST',
            data: {
                article_id: '<?= $blog_id ?>'
            },
            success: function(res) {
                let data = JSON.parse(res);

                $('#likeCount').text(data.total);

                if (data.status === 'liked') {
                    $('#likeBtn').removeClass('btn-outline-primary').addClass('btn-primary');
                } else {
                    $('#likeBtn').removeClass('btn-primary').addClass('btn-outline-primary');
                }
            }
        });
    });
</script>