<?php if (!isset($_SESSION["id"])) { ?>
    <script>
        window.location.href = "../../index.php";
    </script>
<?php } ?>
<?php
$cek_admin = mysqli_real_escape_string($conn, $_SESSION['id']);


$stmt_cek_admin = $conn->prepare("
        SELECT is_admin FROM profile 
        WHERE UUID = ? 
    ");
$stmt_cek_admin->bind_param("s", $cek_admin);
$stmt_cek_admin->execute();

$result_cek_admin = $stmt_cek_admin->get_result();
$row = $result_cek_admin->fetch_assoc();

?>
<div class="col-md-3 mb-4">
    <div class="sidebar p-3 shadow-sm">
        <h6 class="">Quick Links</h6>

        <ul class="list-group list-group-flush">

            <li class="list-group-item" onclick="window.open('?page=create&action=write','_self')" style="cursor: pointer;">
                <a>Write</a>
            </li>
            <li class="list-group-item" onclick="window.open('?page=setting&action=library','_self')" style="cursor: pointer;">
                <a>Dashboard</a>
            </li>
            <li class="list-group-item" onclick="window.open('?page=setting&action=profile','_self')" style="cursor: pointer;">
                <a>Profile</a>
            </li>
            <li class="list-group-item" onclick="window.open('?page=setting&action=activity','_self')" style="cursor: pointer;">
                <a>Activity</a>
            </li>
            <?php if ($row['is_admin'] === 'YES') { ?>
                <li class="list-group-item" onclick="window.open('?page=create&action=category','_self')" style="cursor: pointer;">
                    <a>Category</a>
                </li>
            <?php } ?>

            <li class="list-group-item" onclick="window.open('./logout.php','_self')" style="cursor: pointer;">
                <a style="color: red; text-decoration: none;">Logout</a>
            </li>

        </ul>
    </div>
</div>