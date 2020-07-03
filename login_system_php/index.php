<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="" method="post" enctype="">
            <input type="text" name="user_name"><br>
            <input type="password" name="passwors"><br>
            <input type="submit" name="login" value="login">
        </form>
        <?php
         session_start();

        if (isset($_POST['login']) && $_POST['login'] == 'login') {
           include './db.php';
             $user_name = $_POST['user_name'];
           
            $passwors = $_POST['passwors'];
            $select = mysql_query("SELECT * FROM `panel` WHERE user_name = '$user_name' && passwors = '$passwors'");
            if ($select) {
                $fetch = mysql_fetch_array($select);
                //$user = $fetch['id'];
                $_SESSION['id'] = $fetch['id'];
                header("Location:admin_panel.php");
            } else {
                header("Location:index.php");
            }
        }
        ?>
    </body>
</html>
