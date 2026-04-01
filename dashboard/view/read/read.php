<?php if (!isset($_SESSION["id"])) { ?>
    <script>
        window.location.href = "../../index.php";
    </script>
<?php } ?>


<?php
if (isset($_GET['id'])) {

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

    // ambil artikel
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

    // cek akses
    if (
        $row['status'] === 'draft' &&
        $_SESSION['id'] !== $row['author_uuid']
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

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM article_like WHERE article_id=?");
$stmt->bind_param("s", $row_view_article['article_id']);
$stmt->execute();
$total_like = $stmt->get_result()->fetch_assoc()['total'];

// cek user sudah like
$stmt = $conn->prepare("SELECT 1 FROM article_like WHERE article_id=? AND id_profile=?");
$stmt->bind_param("ss", $row_view_article['article_id'], $row_view['id_profile']);
$stmt->execute();
$isLiked = $stmt->get_result()->num_rows > 0;

?>

<div class="col-lg-8 col-xl-7">
    <?php
    if ($row['status'] === 'draft') { ?>
        <div class="alert alert-warning" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            Artikel ini masih dalam status <strong>Draft</strong>. Hanya Anda yang dapat melihatnya.
        </div>
    <?php }
    ?>

    <?php
    if ($row['is_takedown'] === 'YES') { ?>
        <div class="alert alert-danger" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            Artikel ini telah <strong>Ditakedown</strong> oleh admin.
        </div>
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
    <img src="./../assets/image/thumbnail/<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="Thumbnail"
        class="img-fluid rounded mb-4">

    <hr>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <button id="likeBtn" class="btn btn-sm <?= $isLiked ? 'btn-primary' : 'btn-outline-primary' ?>">
                👍 Like
            </button>
            <span id="likeCount"><?= $total_like ?></span>
        </div>
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