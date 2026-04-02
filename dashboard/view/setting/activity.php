<?php if (!isset($_SESSION["id"])) { ?>
    <script>
        window.location.href = "../../index.php";
    </script>
<?php } ?>

<?php


$limit = 5;
$page = isset($_GET['pg']) ? (int)$_GET['pg'] : 1;

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_param = "%$search%";
$activity = $_GET["activity"] ?? 'liked';

$category = isset($_GET['category']) ? $_GET['category'] : 'all';

$offset = ($page - 1) * $limit;


$cek_admin = mysqli_real_escape_string($conn, $_SESSION['id']);


$stmt_cek_admin = $conn->prepare("
        SELECT * FROM profile 
        WHERE UUID = ? 
    ");
$stmt_cek_admin->bind_param("s", $cek_admin);
$stmt_cek_admin->execute();

$result_cek_admin = $stmt_cek_admin->get_result();
$row = $result_cek_admin->fetch_assoc();

// ambil artikel like
$stmt_activity = $conn->prepare("
SELECT 
    p.username, 
    author.username as author_username,
    a.title, 
    al.created_at,
    a.likes as like_count,
    a.thumbnail,
    a.status,
    a.UUID,
    a.is_takedown,
    a.views as view_count,
    c.nama as category_name
FROM article_like al
JOIN article a ON a.article_id = al.article_id
JOIN profile p ON p.id_profile = al.id_profile
JOIN profile author ON author.id_profile = a.id_profile
JOIN category c ON a.category_id = c.category_id
WHERE p.UUID = ?
AND a.title LIKE ?
AND (? = 'all' OR a.category_id = ?)
ORDER BY al.created_at DESC
LIMIT ? OFFSET ?;
    ");
$stmt_activity->bind_param("ssssii", $cek_admin, $search_param, $category, $category, $limit, $offset);
$stmt_activity->execute();

$result_activity = $stmt_activity->get_result();
$row_activity = $result_activity->fetch_assoc();

//ambil artikel takedown
$stmt_activity_takedown = $conn->prepare("
SELECT 
    p.username, 
    p.username as author_username,
    a.title, 
    a.UUID,
    a.created_at,
    a.thumbnail,
    a.is_takedown,
    a.likes as like_count,
    a.views as view_count,
    c.nama as category_name
FROM article a 
JOIN profile p ON a.id_profile = p.id_profile
JOIN category c ON a.category_id = c.category_id
WHERE a.is_takedown = 'YES' 
AND a.title LIKE ? 
AND (? = 'all' OR a.category_id = ?) 
ORDER BY a.created_at DESC LIMIT ? OFFSET ?;
    ");
$stmt_activity_takedown->bind_param("sssii", $search_param, $category, $category, $limit, $offset);
$stmt_activity_takedown->execute();

$result_activity_takedown = $stmt_activity_takedown->get_result();
$row_activity_takedown = $result_activity_takedown->fetch_assoc();



if ($activity == "liked") {
    $total_query_pg = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM article a 
    JOIN profile p 
        ON a.id_profile = p.id_profile
    JOIN article_like al ON a.article_id = al.article_id
    WHERE a.status = 'publish'
    AND (a.title LIKE ?)
    AND (? = 'all' OR a.category_id = ?)
    AND al.id_profile = ?
    
");
    $total_query_pg->bind_param("sssi", $search_param, $category, $category, $row['id_profile']);
} else {
    $total_query_pg = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM article a 
    JOIN profile p 
        ON a.id_profile = p.id_profile
    WHERE a.is_takedown = 'YES'
    AND (a.title LIKE ?)
    AND (? = 'all' OR a.category_id = ?)
");
    $total_query_pg->bind_param("sss", $search_param, $category, $category);
    
}

$total_query_pg->execute();
$result_total_pg = $total_query_pg->get_result();

$total_data = $result_total_pg->fetch_assoc()['total'];

$total_pages = ceil($total_data / $limit);
$range = 2;

$start = max(1, $page - $range);
$end = min($total_pages, $page + $range);
?>

<div class="col-md-9">

    <div class="card border-0 shadow-sm rounded-3 p-4">

        <?php
        if ($row['is_admin'] === "YES") { ?>
            <!-- HEADER -->

            <div class="d-flex align-items-center mb-3">
                <h5 class="mb-0">Admin Activity</h5>

                <!-- SEARCH + CATEGORY (KANAN) -->
                <form method="GET" class="d-flex gap-2 align-items-center ms-auto" style="max-width: 600px; width:100%;">

                    <input type="hidden" name="page" value="setting">
                    <input type="hidden" name="action" value="activity">
                    <?php
                    if ($activity == "liked") { ?>
                        <input type="hidden" name="activity" value="liked">
                    <?php } else { ?>
                        <input type="hidden" name="activity" value="takedown">
                    <?php }     ?>

                    <input type="hidden" name="pg" value="1">


                    <!-- CATEGORY -->

                    <?php if ($activity == "all") { ?>
                        <select name="category" id="category-search" class="form-select" style="width: 160px;">
                            <option value="all" <?= ($category == 'all') ? 'selected' : '' ?>>All Activity</option>
                            <option value="liked" <?= ($category == 'liked') ? 'selected' : '' ?>>Liked</option>
                            <option value="takedown" <?= ($category == 'takedown') ? 'selected' : '' ?>>Takedown</option>

                        </select>
                    <?php  } else { ?>
                        <select name="category" id="category-search" class="form-select" style="width: 160px;">
                            <option value="all" <?= ($category == 'all') ? 'selected' : '' ?>>All Category</option>
                            <?php
                            $result_category = $conn->query("SELECT * FROM category");
                            while ($row_category = $result_category->fetch_assoc()) { ?>
                                <option value="<?= $row_category['category_id'] ?>" <?= ($category == $row_category['category_id']) ? 'selected' : '' ?>>
                                    <?= $row_category['nama'] ?>
                                </option>
                            <?php } ?>
                        </select>

                    <?php   } ?>

                    <!-- SEARCH -->
                    <input type="text" name="q" class="form-control flex-grow-1"
                        placeholder="Search activity..." value="<?= htmlspecialchars($search) ?>">

                    <!-- BUTTON -->
                    <button class="btn btn-primary px-3">
                        Search
                    </button>

                </form>
            </div>


            <!-- TABS -->
            <ul class="nav nav-tabs mb-3">

                <li class="nav-item">
                    <a class="nav-link <?= ($activity === 'liked') ? 'active' : '' ?>" href="?page=setting&action=activity&activity=liked">Liked</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger <?= ($activity === 'takedown') ? 'active' : '' ?>" href="?page=setting&action=activity&activity=takedown">Takedown</a>
                </li>
            </ul>

            <?php if ($activity === 'liked') { ?>

                <?php if ($total_data == 0) { ?>
                    <div class="d-flex justify-content-center my-5">
                        <div class="card shadow-sm border-0 text-center p-4" style="max-width: 400px;">
                            <div class="card-body">
                                <h5 class="fw-bold mb-2">Anda Harus Like Article </h5>
                                <p class="text-muted mb-3">
                                    Anda Belum Like 1-pun Article
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php

                foreach ($result_activity as $row_activity_loop) {

                ?>
                    <div class="d-flex mb-3 pb-3 border-bottom align-items-center"
                        onclick="window.location.href='?page=read&action=readarticle&id=<?= $row_activity_loop['UUID'] ?>'"
                        style="cursor:pointer;">

                        <!-- LEFT -->
                        <div class="flex-grow-1">

                            <h6 class="mb-1"><?= $row_activity_loop['title'] ?></h6>

                            <small class="text-muted">
                                by <?= $row_activity_loop['author_username'] ?> • <?= waktuLalu($row_activity_loop['created_at']) ?> • <?= $row_activity_loop['category_name'] ?>
                            </small>

                            <div class="mt-2 d-flex gap-3">
                                <small>👍 <?= $row_activity_loop['like_count'] ?? 0 ?></small>
                                <small>👁 <?= $row_activity_loop['view_count'] ?? 0 ?></small>
                                <small> <?php echo $row_activity_loop['status']; ?><small style="color: red;"> <?= $row_activity_loop['is_takedown'] == "YES" ? " (Takedown)" : ""; ?> </small></small>
                            </div>

                        </div>

                        <!-- RIGHT IMAGE -->
                        <div style="width:100px; height:70px; overflow:hidden; border-radius:6px;">
                            <img src="../assets/image/thumbnail/<?= $row_activity_loop['thumbnail'] ?>"
                                style="width:100%; height:100%; object-fit:cover;">
                        </div>

                    </div>

                <?php }  ?>


                <?php  } else { ?>

                       <?php if ($total_data == 0) { ?>
                    <div class="d-flex justify-content-center my-5">
                        <div class="card shadow-sm border-0 text-center p-4" style="max-width: 400px;">
                            <div class="card-body">
                                <h5 class="fw-bold mb-2">Anda Tidak Men-takedown Article Orang lain </h5>
                                <p class="text-muted mb-3">
                                    Takedown setidaknya 1 article dan lihat datanya disini
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>

              <?php  foreach ($result_activity_takedown as $row_activity_loop_takedown) {

                ?>
                    <div class="d-flex mb-3 pb-3 border-bottom align-items-center"
                        onclick="window.location.href='?page=read&action=readarticle&id=<?= $row_activity_loop_takedown['UUID'] ?>'"
                        style="cursor:pointer;">

                        <!-- LEFT -->
                        <div class="flex-grow-1">

                            <h6 class="mb-1"><?= htmlentities($row_activity_loop_takedown['title']) ?></h6>

                            <small class="text-muted">
                                by <?= htmlentities($row_activity_loop_takedown['author_username']) ?> • <?= waktuLalu($row_activity_loop_takedown['created_at']) ?> • <?= htmlentities($row_activity_loop_takedown['category_name']) ?>
                            </small>

                            <div class="mt-2 d-flex gap-3">
                                <small>👍 <?= htmlentities($row_activity_loop_takedown['like_count'] ?? 0) ?></small>
                                <small>👁 <?= htmlentities($row_activity_loop_takedown['view_count'] ?? 0) ?></small>
                                <small style="color: red;"> <?= $row_activity_loop_takedown['is_takedown'] == "YES" ? " (Takedown)" : ""; ?> </small>

                            </div>

                        </div>

                        <!-- RIGHT IMAGE -->
                        <div style="width:100px; height:70px; overflow:hidden; border-radius:6px;">
                            <img src="../assets/image/thumbnail/<?= htmlentities($row_activity_loop_takedown['thumbnail']) ?>"
                                style="width:100%; height:100%; object-fit:cover;">
                        </div>

                    </div>

                <?php }  ?>

            <?php  }
            ?>

            <!-- admin -->
            <nav>
                <ul class="pagination justify-content-center">

                    <!-- PREVIOUS -->
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=setting&action=activity&activity=<?= $activity ?>&pg=<?= $page - 1 ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>">Previous</a>
                    </li>

                    <!-- FIRST -->
                    <?php if ($start > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=setting&action=activity&activity=<?= $activity ?>&pg=1&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>">1</a></li>
                        <?php if ($start > 2): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- MIDDLE -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=setting&action=activity&activity=<?= $activity ?>&pg=<?= $i ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>">
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
                            <a class="page-link" href="?page=setting&action=activity&activity=<?= $activity ?>&pg=<?= $total_pages ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>"><?= $total_pages ?></a>
                        </li>
                    <?php endif; ?>

                    <!-- NEXT -->
                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=setting&action=activity&activity=<?= $activity ?>&pg=<?= $page + 1 ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>">Next</a>
                    </li>

                </ul>
            </nav>

        <?php } else { ?>



            <div class="d-flex align-items-center mb-3">
                <h5 class="mb-0">Liked Articles</h5>


                <form method="GET" class="d-flex gap-2 align-items-center ms-auto" style="max-width: 600px; width:100%;">

                    <input type="hidden" name="page" value="setting">
                    <input type="hidden" name="action" value="activity">
                    <input type="hidden" name="pg" value="1">
                    <input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>">


                    <select name="category" id="category-search" class="form-select" style="width: 160px;">
                        <option value="all" <?= ($category == 'all') ? 'selected' : '' ?>>All Category</option>
                        <?php
                        $result_category = $conn->query("SELECT * FROM category");
                        while ($row_category = $result_category->fetch_assoc()) { ?>
                            <option value="<?= $row_category['category_id'] ?>" <?= ($category == $row_category['category_id']) ? 'selected' : '' ?>>
                                <?= $row_category['nama'] ?>
                            </option>
                        <?php } ?>
                    </select>


                    <input type="text" name="q" class="form-control flex-grow-1"
                        placeholder="Search liked articles...">


                    <button class="btn btn-primary px-3">
                        Search
                    </button>

                </form>
            </div>


            <div>
                <?php if ($total_data == 0) { ?>
                    <div class="d-flex justify-content-center my-5">
                        <div class="card shadow-sm border-0 text-center p-4" style="max-width: 400px;">
                            <div class="card-body">
                                <h5 class="fw-bold mb-2">Anda Harus Like Article </h5>
                                <p class="text-muted mb-3">
                                    Anda Belum Like 1-pun Article
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php

                foreach ($result_activity as $row_activity_loop) {

                ?>


                    <div class="d-flex mb-3 pb-3 border-bottom align-items-center"
                        onclick="window.location.href='?page=read&id=1'"
                        style="cursor:pointer;">

                        <!-- LEFT -->
                        <div class="flex-grow-1">

                            <h6 class="mb-1"><?= $row_activity_loop['title'] ?></h6>

                            <small class="text-muted">
                                by <?= $row_activity_loop['author_username'] ?> • <?= waktuLalu($row_activity_loop['created_at']) ?> • <?= $row_activity_loop['category_name'] ?>
                            </small>

                            <div class="mt-2 d-flex gap-3">
                                <small>👍 <?= $row_activity_loop['like_count'] ?? 0 ?></small>
                                <small>👁 <?= $row_activity_loop['view_count'] ?? 0 ?></small>

                            </div>

                        </div>

                        <!-- RIGHT IMAGE -->
                        <div style="width:100px; height:70px; overflow:hidden; border-radius:6px;">
                            <img src="../assets/image/thumbnail/<?= $row_activity_loop['thumbnail'] ?>"
                                style="width:100%; height:100%; object-fit:cover;">
                        </div>

                    </div>

                <?php }  ?>


            </div>
            <!-- user -->
            <nav>
                <ul class="pagination justify-content-center">

                    <!-- PREVIOUS -->
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=setting&action=activity&pg=<?= $page - 1 ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>">Previous</a>
                    </li>

                    <!-- FIRST -->
                    <?php if ($start > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=setting&action=activity&pg=1&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>">1</a></li>
                        <?php if ($start > 2): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- MIDDLE -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=setting&action=activity&pg=<?= $i ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>">
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
                            <a class="page-link" href="?page=setting&action=activity&pg=<?= $total_pages ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>"><?= $total_pages ?></a>
                        </li>
                    <?php endif; ?>

                    <!-- NEXT -->
                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=setting&action=activity&pg=<?= $page + 1 ?>&category=<?= urlencode($category) ?>&q=<?= urlencode($search) ?>">Next</a>
                    </li>

                </ul>
            </nav>



        <?php } ?>


    </div>

</div>