<?php if (!isset($_SESSION["id"])) { ?>
    <script>
        window.location.href = "../../index.php";
    </script>
<?php } ?>

<?php
$user_id = mysqli_real_escape_string($conn, $_SESSION['id']);

$stmt_profile = $conn->prepare("SELECT nama, username,password, photo,bio FROM profile WHERE UUID = ?");
$stmt_profile->bind_param("s", $user_id);
$stmt_profile->execute();
$result_profile = $stmt_profile->get_result();
$row_profile = $result_profile->fetch_assoc();
?>
<div class="col-md-9">

    <div class="card border-0 shadow-sm rounded-3 p-4">

        <h5 class="fw-bold mb-4">Profile Settings</h5>

        <form method="POST" enctype="multipart/form-data">

            <!-- Avatar -->
            <div class="d-flex align-items-center mb-4">
                <img src="../assets/image/profile/<?php echo htmlspecialchars($row_profile['photo']); ?>"
                    class="rounded-circle me-3"
                    width="150" height="auto">

                <div>
                    <input type="file" class="form-control form-control-sm mb-2" name="photo">
                    <small class="text-muted">Upload foto profil baru</small>
                </div>
            </div>

            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row_profile['nama']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">bio</label>
                <textarea name="bio" class="form-control" id="bio" rows="3"><?php echo htmlspecialchars($row_profile['bio']); ?></textarea>
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" disabled value="<?php echo htmlspecialchars($row_profile['username']); ?>">
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" type="submit" name="Update">Save</button>
            </div>
            <form method="POST">

                <hr class="my-4">
                <!-- Password -->
                <h6 class="fw-bold mb-3">Ubah Password</h6>

                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Password Lama" name="old_password">
                </div>

                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Password Baru" name="new_password">
                </div>

                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Konfirmasi Password" name="confirm_password">
                </div>

                <!-- Button -->
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit" name="Update_password">Ganti Password</button>
                </div>


            </form>


    </div>

</div>

<?php


if (isset($_POST["Update_password"])) {
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($old_password !== NULL && $new_password !== NULL && $confirm_password !== NULL) {
        if (password_verify($old_password, $row_profile['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt_update_password = $conn->prepare('UPDATE profile SET
            password = ?
            WHERE UUID = ?');
            $stmt_update_password->bind_param('ss', $hashed_password, $_SESSION['id']);
            if ($stmt_update_password->execute()) {
                echo '<script>
            Swal.fire({
                title: "Berhasil!",
                text: "Berhasil Update Password!",
                icon: "success"
            }).then(() => {
                window.location.href = "?page=setting&action=profile";
            });
        </script>';
                exit;
            }
        } else {
            echo '<script>
                Swal.fire({
                    title: "Gagal!",
                    text: "Password lama salah!",
                    icon: "error"
                });
            </script>';
            exit;
        }
    } else {
        echo 'a';
    }
}


if (isset($_POST['Update'])) {


    $nama = htmlspecialchars(isset($_POST['name']) ? $_POST['name'] : '');
    $bio = htmlspecialchars(isset($_POST['bio']) ? $_POST['bio'] : '');


    $ekstensi_diperbolehkan = array('jpg', 'jpeg', 'png', 'webp');
    $nama_file = $_FILES['photo']['name'];
    $x = explode('.', $nama_file);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['photo']['size'];
    $file_tmp = $_FILES['photo']['tmp_name'];
    $file_diunggah = !empty($nama_file);


    $stmt_gambar_lama = $conn->prepare("SELECT photo FROM profile WHERE UUID = ?");
    $stmt_gambar_lama->bind_param("s", $_SESSION['id']);
    $stmt_gambar_lama->execute();
    $result = $stmt_gambar_lama->get_result();
    $row = $result->fetch_assoc();
    $gambar_lama = $row['photo'];

    $tgl_upload = date("Y-m-d:H:i:s");


    if (empty($nama)) {
        echo '<script>
                    Swal.fire({
                        title: "Gagal!",
                        text: "Ada field yang kosong!",
                        icon: "error"
                    }).then(() => {
                            window.location.href = "./?page=setting&action=profile";
                        });
                </script>';
        exit;
    }

    if ($file_diunggah) {
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 2097152) {
                $hashed_file_name = "pf-" . substr(date("dmyhis"), 0, 35) . '.' . $ekstensi;

                $stmt_update = $conn->prepare('UPDATE profile SET
                    photo = ?,
                    nama = ?,
                    bio = ?
                    WHERE UUID = ?');
                $stmt_update->bind_param('ssss', $hashed_file_name, $nama, $bio, $_SESSION['id']);

                if ($stmt_update->execute()) {
                    if ($gambar_lama == "default.jpg") {
                        move_uploaded_file($file_tmp, '../assets/image/profile/' . $hashed_file_name);
                    } else {
                        unlink("../assets/image/profile/" . $gambar_lama);
                        move_uploaded_file($file_tmp, '../assets/image/profile/' . $hashed_file_name);
                    }
                    echo '<script>
        Swal.fire({
            title: "Berhasil!",
            text: "Berhasil Update Data!",
            icon: "success"
        }).then(() => {
            window.location.href = "./?page=setting&action=library";
        });
    </script>';
                    exit;
                } else {
                    echo '<script>
                        Swal.fire({
                            title: "Gagal!",
                            text: "Gagal Update Data!",
                            icon: "error"
                        }).then(() => {
                    window.location.href="./?page=setting&action=profile";                        
                    });
                    </script>';
                    exit;
                }
            } else {
                echo '<script>
                    Swal.fire({
                        title: "Gagal!",
                        text: "Ukuran tidak lebih dari 2mb!",
                        icon: "error"
                    }).then(() => {
                window.location.href="./?page=setting&action=profile";                        
                });
                </script>';
                exit;
            }
        } else {
            echo '<script>
                Swal.fire({
                    title: "Gagal!",
                    text: "Format harus jpg, jpeg, atau png!",
                    icon: "error"
                }).then(() => {
            window.location.href="./?page=setting&action=profile";                        
            });
            </script>';
            exit;
        }
    } else {
        $stmt_update = $conn->prepare('UPDATE profile SET
                    nama = ?,
                    bio = ?
                    WHERE UUID = ?');
        $stmt_update->bind_param('sss', $nama, $bio, $_SESSION['id']);

        if ($stmt_update->execute()) {
            echo '<script>
        Swal.fire({
            title: "Berhasil!",
            text: "Berhasil Update Data!",
            icon: "success"
        }).then(() => {
            window.location.href = "./?page=setting&action=profile";
        });
    </script>';
            exit;
        }
    }
}
?>