<?php
include "./koneksi_s_login.php";

$user_id = mysqli_real_escape_string($conn, $_SESSION['id']);
$article_id = mysqli_real_escape_string($conn, $_POST['article_id']);

$stmt_user = $conn->prepare("SELECT id_profile FROM profile WHERE UUID = ?");
$stmt_user->bind_param("s", ($user_id));
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$row_user = $result_user->fetch_assoc();

$stmt_article = $conn->prepare("SELECT article_id FROM article WHERE UUID = ?");
$stmt_article->bind_param("s", ($article_id));
$stmt_article->execute();
$result_article = $stmt_article->get_result();
$row_article = $result_article->fetch_assoc();


$stmt = $conn->prepare("SELECT * FROM article_like WHERE article_id=? AND id_profile=?");
$stmt->bind_param("ss", $row_article['article_id'], $row_user['id_profile']);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {

    // UNLIKE
    $stmt = $conn->prepare("DELETE FROM article_like WHERE article_id=? AND id_profile=?");
    $stmt->bind_param("ss", $row_article['article_id'], $row_user['id_profile']);
    $stmt->execute();

    $stmt2 = $conn->prepare("
        UPDATE article 
        SET likes = likes - 1 
        WHERE article_id = ?
    ");
    $stmt2->bind_param("s", $row_article['article_id']);
    $stmt2->execute();

    $status = "unliked";
} else {

    // LIKE
    $stmt = $conn->prepare("INSERT INTO article_like (article_id, id_profile, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $row_article['article_id'], $row_user['id_profile']);
    $stmt->execute();

    $stmt2 = $conn->prepare("
        UPDATE article 
        SET likes = likes + 1 
        WHERE article_id = ?
    ");
    $stmt2->bind_param("s", $row_article['article_id']);
    $stmt2->execute();

    $status = "liked";
}

// hitung total like
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM article_like WHERE article_id=?");
$stmt->bind_param("s", $row_article['article_id']);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];

echo json_encode([
    'status' => $status,
    'total' => $total
]);
