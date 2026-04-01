<?php if (!isset($_SESSION["id"])) { ?>
    <script>
        window.location.href = "../../index.php";
    </script>
<?php } ?>

<?php
$uuid = mysqli_real_escape_string($conn, $_SESSION["id"]);

$stmt_admin = $conn->prepare("
   SELECT 
      is_admin
    FROM profile
    WHERE UUID = ?
");
$stmt_admin->bind_param("s", $uuid);
$stmt_admin->execute();

$result_admin = $stmt_admin->get_result();
$row_admin = $result_admin->fetch_assoc();

if (isset($_GET['username'])) {

    $username = mysqli_real_escape_string($conn, $_GET['username']);


    // ambil stats profile
    $stmt_stats = $conn->prepare("
    SELECT 
        p.nama,
        p.username,
        p.photo,
        p.bio,

        COUNT(a.article_id) AS article_count,

        SUM(a.likes) AS total_likes,
        SUM(a.views) AS total_views

    FROM profile p

    LEFT JOIN article a 
        ON p.id_profile = a.id_profile 
        AND a.status = 'publish'

    WHERE p.username = ?

    GROUP BY p.id_profile, p.nama, p.username, p.photo, p.bio
");
    $stmt_stats->bind_param("s", $username);
    $stmt_stats->execute();

    $result_stats = $stmt_stats->get_result();
    $row = $result_stats->fetch_assoc();

    //pg - search -category
    $limit = 5;
    $page = isset($_GET['pg']) ? (int)$_GET['pg'] : 1;

    $search = isset($_GET['q']) ? trim($_GET['q']) : '';
    $search_param = "%$search%";

    $category = isset($_GET['category']) ? $_GET['category'] : 'all';

    $offset = ($page - 1) * $limit;

    if ($row_admin['is_admin'] == "YES") {
        $stmt_pg = $conn->prepare("
    SELECT 
        a.*, 
        p.nama, 
        p.username, 
        p.photo,
        c.nama AS category_name
    FROM article a 
    JOIN profile p 
        ON a.id_profile = p.id_profile 
    JOIN category c ON a.category_id = c.category_id
    WHERE p.username = ? 
    AND (a.title LIKE ?)
    AND (? = 'all' OR a.category_id = ?)
    ORDER BY a.created_at DESC 
    LIMIT ? OFFSET ?
");

        $stmt_pg->bind_param("ssssii", $username, $search_param, $category, $category, $limit, $offset);
        $stmt_pg->execute();
        $result_pg = $stmt_pg->get_result();
    } else {
        $stmt_pg = $conn->prepare("
    SELECT 
        a.*, 
        p.nama, 
        p.username, 
        p.photo,
        c.nama AS category_name
    FROM article a 
    JOIN profile p 
        ON a.id_profile = p.id_profile 
    JOIN category c ON a.category_id = c.category_id
    WHERE p.username = ? 
    AND a.status = 'publish'
    AND (a.title LIKE ?)
    AND (? = 'all' OR a.category_id = ?)
    ORDER BY a.created_at DESC 
    LIMIT ? OFFSET ?
");

        $stmt_pg->bind_param("ssssii", $username, $search_param, $category, $category, $limit, $offset);
        $stmt_pg->execute();
        $result_pg = $stmt_pg->get_result();
    }

    $total_query_pg = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM article a 
    JOIN profile p 
        ON a.id_profile = p.id_profile 
    WHERE p.username = ? 
    AND a.status = 'publish'
    AND (a.title LIKE ?)
");

    $total_query_pg->bind_param("ss", $username, $search_param);
    $total_query_pg->execute();
    $result_total_pg = $total_query_pg->get_result();

    $total_data = $result_total_pg->fetch_assoc()['total'];

    $total_pages = ceil($total_data / $limit);

    $stmt_category = $conn->prepare("SELECT * FROM category");
    $stmt_category->execute();
    $result_category = $stmt_category->get_result();
} else {
    header("Location: ../dashboard/?page=home");
    exit;
}

$range = 2;

$start = max(1, $page - $range);
$end = min($total_pages, $page + $range);
?>

<div class="container my-4">

    <!-- PROFILE HEADER -->
    <div class="card p-4 shadow-sm mb-4">
        <div class="d-flex align-items-center">

            <!-- Avatar -->
            <img src="../assets/image/profile/<?php echo htmlspecialchars($row['photo']); ?>"
                class="rounded-circle me-3"
                width="80" height="auto" style="object-fit: fill;">

            <!-- Info -->
            <div class="flex-grow-1">
                <h4 class="mb-1"><?php echo htmlspecialchars($row['nama']); ?></h4>
                <small class="text-muted">@<?php echo htmlspecialchars($row['username']); ?></small>

                <div class="mt-2 d-flex gap-4">
                    <div><strong><?php echo $row['article_count'] ?? 0; ?> </strong> Articles  <small style="color: green;"><?=  $row_admin['is_admin'] == "YES" ? " (Published)" : "" ?></small></div>
                    <div><strong><?php echo $row['total_likes'] ?? 0; ?></strong> Likes</div>
                    <div><strong><?php echo $row['total_views'] ?? 0; ?></strong> Views</div>
                </div>
            </div>

            <!-- Action -->


        </div>

        <!-- Bio -->
        <p class="mt-3 mb-0 text-muted">
            <?php echo htmlspecialchars($row['bio']); ?>
        </p>
    </div>


    <!-- ARTICLES -->
    <div class="card p-3 shadow-sm">

        <div class="d-flex align-items-center mb-3">

            <h5 class="mb-0">Articles</h5>

            <!-- FORM DI KANAN -->
            <form method="GET" class="d-flex gap-2 align-items-center ms-auto" style="width: 700px;">

                <input type="hidden" name="page" value="read">
                <input type="hidden" name="action" value="profile">
                <input type="hidden" name="username" value="<?= $username ?>">
                <input type="hidden" name="pg" value="1">

                <!-- CATEGORY -->
                <select class="form-select" id="category-search" name="category" style="width: 600px;">
                    <option value="all">All Category</option>
                    <?php while ($row_category = $result_category->fetch_assoc()) { ?>
                        <option value="<?= $row_category['category_id'] ?>" <?= ($category == $row_category['category_id']) ? 'selected' : '' ?>>
                            <?= $row_category['nama'] ?>
                        </option>
                    <?php } ?>
                </select>

                <!-- INPUT -->
                <input type="text" name="q" class="form-control flex-grow-1"
                    placeholder="Cari artikel..."
                    value="<?= htmlspecialchars($search) ?>">

                <!-- BUTTON -->
                <button class="btn btn-primary px-3">
                    Search
                </button>

            </form>

        </div>
        <!-- ITEM -->

        <?php if (mysqli_num_rows($result_pg) == 0) { ?>
            <div class="text-center py-5">
                <h5 class="mb-3">Tidak ada Article</h5>
                <p class="text-muted">Belum ada artikel yang dipublikasikan oleh pengguna ini.</p>
            </div>
        <?php } else { ?>
            <?php foreach ($result_pg as $row_article) {
                $content = strip_tags($row_article['content']);
                $words = str_word_count($content);

                $reading_time = ceil($words / 50); ?>
                <div class="row g-0 align-items-center mb-3 border-bottom pb-3"
                    onclick="window.open('?page=read&action=readarticle&id=<?= $row_article['UUID'] ?>' ,'_self')"
                    style="cursor:pointer;">

                    <!-- LEFT -->
                    <div class="col-8">
                        <div class="card-body px-0 py-2">

                            <!-- TITLE -->
                            <h5 class="mb-1"><?php echo htmlspecialchars($row_article['title']); ?></h5>

                            <!-- META -->
                            <small class="text-muted">
                                Published • <?php echo timeAgo($row_article['created_at']); ?> • <?= $reading_time ?> menit baca • <?= $row_article['category_name'] ?>
                            </small>

                            <!-- STATS -->
                            <div class="mt-2 d-flex gap-3">
                                <small>👍 <?php echo $row_article['likes'] ?? 0; ?></small>
                                <small>👁 <?php echo $row_article['views'] ?? 0; ?></small>
                                <?php
                                if ($row_admin['is_admin'] == "YES") { ?>
                                    <small> <?php echo $row_article['status']; ?><small style="color: red;"> <?=  $row_article['is_takedown'] == "YES" ? " (Takedown)" : ""; ?> </small></small>
                                <?php }
                                ?>

                            </div>

                        </div>
                    </div>

                    <!-- RIGHT IMAGE -->
                    <div class="col-4 d-flex justify-content-end">
                        <div style="width:200px; height:150px; overflow:hidden; border-radius:6px;">
                            <img src="../assets/image/thumbnail/<?php echo htmlspecialchars($row_article['thumbnail']); ?>"
                                style="width:100%; height:100%; object-fit:cover;">
                        </div>
                    </div>

                </div>

        <?php }
        } ?>
        <!-- PAGINATION -->

        <nav>
            <ul class="pagination justify-content-center">

                <!-- PREVIOUS -->
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=read&action=profile&username=<?= $username ?>&pg=<?= $page - 1 ?>&q=<?= urldecode($search) ?>">Previous</a>
                </li>

                <!-- FIRST -->
                <?php if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="?page=read&action=profile&username=<?= $username ?>&pg=1&q=<?= urldecode($search) ?>">1</a></li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- MIDDLE -->
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=read&action=profile&username=<?= $username ?>&pg=<?= $i ?>&q=<?= urldecode($search) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- LAST -->
                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=read&action=profile&username=<?= $username ?>&pg=<?= $total_pages ?>&q=<?= urldecode($search) ?>"><?= $total_pages ?></a>
                    </li>
                <?php endif; ?>

                <!-- NEXT -->
                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=read&action=profile&username=<?= $username ?>&pg=<?= $page + 1 ?>&q=<?= urldecode($search) ?>">Next</a>
                </li>

            </ul>
        </nav>


    </div>

</div>