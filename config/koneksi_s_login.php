<?php
ob_start();
session_start();
if (!isset($_SESSION["id"])){ ?>
   <script>
    window.location.href = "../index.php";
   </script>
<?php }
$conn = new mysqli("localhost" , 'root', '' , 'portarum')
?>