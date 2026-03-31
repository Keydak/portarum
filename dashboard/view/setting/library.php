<?php if (!isset($_SESSION["id"])){ ?>
   <script>
    window.location.href = "../../index.php";
   </script>
<?php } ?>

<?php
$stmt_tables = $conn->prepare("SELECT thumbnail,title,content,is_takedown,status,a.created_at,c.nama,a.UUID FROM article a JOIN profile p JOIN category c ON a.id_profile = p.id_profile AND a.category_id = c.category_id WHERE p.UUID = ?");
  $stmt_tables->bind_param("s", $_SESSION["id"]);
  $stmt_tables->execute();
  $result_tables = $stmt_tables->get_result();

?>
<!-- Main Content -->
<div class="col-md-9">

    <!-- Overview -->
    <div class="card p-3 shadow-sm mb-4">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card-box">
                    <h3>4</h3>
                    <p>Articles</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box">
                    <h3>8</h3>
                    <p>Categories</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box">
                    <h3>4</h3>
                    <p>Bookmarks</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Articles -->
    <div class="card p-3 shadow-sm">
        <h5>Articles</h5>
        <table id="myTable" class="table borderless table-hover align-middle ">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Thumbnail</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($result_tables as $row) {?>
                <tr>
                    <td><?= $row["title"] ?></td>
                    <td><?= $row["nama"] ?></td>
                    <td><img src="./../assets/image/thumbnail/<?= $row["thumbnail"] ?>" alt="Thumbnail" width="100"></td>
                    <td><?= $row["status"] ?> <p style="color: red;"><?= $row['is_takedown'] == 'YES' ? '(Takedown)' : '' ?></p></td>
                    <td><?= formatTanggal($row["created_at"]) ?></td>
                    <td>
                        <a href="?page=read&action=view&id=<?= $row["UUID"] ?>" class="btn btn-sm btn-primary">View</a>
                        <a href="?page=update&action=edit_blog&id=<?= $row["UUID"] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?page=setting&action=library&delete_id=<?= $row["UUID"] ?>" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                
          <?php } 
            ?>
            </tbody>
        </table>

    </div>

</div>