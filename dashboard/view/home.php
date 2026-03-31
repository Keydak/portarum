<?php if (!isset($_SESSION["id"])) { ?>
    <script>
        window.location.href = "../index.php";
    </script>
<?php } ?>


<?php
$stmt_algoritma = $conn->prepare("SELECT 
    a.title,
    a.thumbnail,
    a.created_at,
    a.views,
    a.likes,
    a.content,
    a.UUID,
    p.username,
    p.nama,
    (
        (a.views + 1) / (TIMESTAMPDIFF(HOUR, a.created_at, NOW()) + 2)
    ) AS score  
FROM article a JOIN profile p ON a.id_profile = p.id_profile
WHERE a.status = 'publish' 
AND a.is_takedown = 'NO'
ORDER BY score DESC
LIMIT 5;");
$stmt_algoritma->execute();
$result_algoritma = $stmt_algoritma->get_result();
$row_algoritma = $result_algoritma->fetch_assoc();

$stmt_random = $conn->prepare("SELECT 
    a.*,
    p.nama
FROM article a JOIN profile p ON a.id_profile = p.id_profile
WHERE a.status='publish'
AND a.is_takedown='NO'
AND a.article_id >= (
    SELECT FLOOR(RAND() * (SELECT MAX(article_id) FROM article))
)
LIMIT 6");
$stmt_random->execute();
$result_random = $stmt_random->get_result();
$row_random = $result_random->fetch_assoc();


?>

<!-- LEFT: Berita -->
<div class="col-lg-8 border-end">

    <div style="max-width:800px;">
        <h5 class="fw-bold mb-3">Berita Terpopuler</h5>

        <!-- Item -->
        <?php foreach ($result_algoritma as $row_algoritma) {
            $content = strip_tags($row_algoritma['content']);
            $words = str_word_count($content);

            $reading_time = ceil($words / 50);

        ?>
            <div class="card bg-transparent border-0 border-bottom rounded-0 mb-3 pb-3">


                <div class="row g-0 align-items-center" onclick="window.open('?page=read&action=readarticle&id=<?= $row_algoritma['UUID'] ?>','_blank')"
                    style="cursor:pointer;">

                    <!-- LEFT -->
                    <div class="col-8">
                        <div class="card-body px-0 py-2">

                            <!-- AUTHOR -->
                            <div class="d-flex align-items-center mb-1">
                                <img src="../assets/image/profile/default.png"
                                    class="rounded-circle me-2"
                                    width="24" height="24">

                                <small class="text-muted">
                                    <a style="color: black; " href="?page=read&action=profile&username=<?= $row_algoritma['username'] ?>" target="_blank" onclick="event.stopPropagation();" rel="noopener noreferrer"><?= $row_algoritma['nama'] ?></a>
                                </small>
                            </div>

                            <!-- TITLE -->
                            <h4 class="card-title mb-1" style="color: black;">
                                <?= $row_algoritma['title'] ?>
                            </h4>

                            <!-- META -->
                            <small class="text-muted">
                                <?= formatTanggalRead($row_algoritma['created_at']) ?> • <?= $reading_time ?> menit baca
                            </small>

                            <!-- ACTION -->
                            <div class="mt-2 d-flex align-items-center gap-3">

                                <small class="text-muted">
                                    👍 <?= $row_algoritma['likes'] ?? 0 ?>
                                </small>

                                <small class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                    </svg> <?= $row_algoritma['views'] ?? 0 ?>
                                </small>

                            </div>

                        </div>
                    </div>

                    <!-- RIGHT IMAGE -->
                    <div class="col-4 d-flex justify-content-end">
                        <div style="width:200px; height:150px; overflow:hidden; border-radius:6px;">
                            <img src="../assets/image/thumbnail/<?= $row_algoritma['thumbnail'] ?>"
                                style="width:100%; height:100%; object-fit: cover;">
                        </div>
                    </div>

                </div>

            </div>
        <?php } ?>

    </div>
</div>

<!-- RIGHT: Sidebar Sticky -->
<div class="col-lg-4 d-none d-lg-block">
    <div class="sidebar-sticky" style="top:80px;">

        <div class="card bg-transparent border-0 shadow-none">
            <div class="card-body">

                <div class="border-bottom pb-2">
                    <h6 class="fw-bold mb-3">Random</h6>

                    <!-- ITEM -->
                    <?php foreach ($result_random as $row_random) { ?>
                        <div class="d-flex mb-3 align-items-start rekom-item">

                            <!-- IMAGE -->
                            <div style="width:80px; height:60px; overflow:hidden; border-radius:6px;">
                                <img src="../assets/image/thumbnail/<?= $row_random['thumbnail'] ?>"
                                    style="width:100%; height:100%; object-fit:cover;">
                            </div>

                            <!-- CONTENT -->
                            <div class="ms-2">

                                <!-- TITLE -->
                                <small class="fw-semibold d-block mb-1">
                                    <?= $row_random['title'] ?>
                                </small>

                                <!-- META -->
                                <small class="text-muted">
                                    <?= $row_random['nama'] ?> • 👁️ <?= $row_random['views'] ?? 0 ?>
                                </small>

                            </div>
                        </div>
                    <?php } ?>

                </div>

            </div>
        </div>

    </div>
</div>