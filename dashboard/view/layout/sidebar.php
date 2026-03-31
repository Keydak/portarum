<?php if (!isset($_SESSION["id"])){ ?>
   <script>
    window.location.href = "../../index.php";
   </script>
<?php } ?>

    <div class="col-md-3 mb-4">
        <div class="sidebar p-3 shadow-sm">
            <h6 class="">Quick Links</h6>

            <ul class="list-group list-group-flush">

                <li class="list-group-item">
                    <a href="?page=create&action=write">Write</a>
                </li>
                <li class="list-group-item">
                    <a href="?page=setting&action=library">Library</a>
                </li>
                <li class="list-group-item">
                    <a href="?page=setting&action=profile">Profile</a>
                </li>

                <li class="list-group-item">
                    <a href="?page=home">Dashboard</a>
                </li>

                <li class="list-group-item">
                    Logout
                </li>

            </ul>
        </div>
    </div>
