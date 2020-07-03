<?php
@session_start();
if(!$_SESSION['id']){
    header("Location:index.php");
}
 else {
    echo $_SESSION['id'];
}
?>
<a href="logout.php"> Logout </a>



