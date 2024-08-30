<?php
$path = "";
$page = "setting";

use app\controller\UserController;
use app\utils\Helper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




include '../../vendor/autoload.php';


$threatCount = 0;

$user = [];
$show_text = [];

session_start();
if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 9) {

    $user = $_SESSION["user"];
    $controller = new UserController();


    if (isset($_POST["btnUserAccountUpdate"])) {
        $new_password = $_POST["new_password"];
        $fullname = $_POST["fullname"];
        $password = $_POST["password"];

        if ($controller->getPasswordComparism($user["user_password"], $password)) {
            $payload = array(
                '_password' => $new_password,
                '_fullname' => $fullname,
                '_slug' => $_SESSION["user"]["user_slug"]
            );
            $response = json_decode($controller->modifyUserPassword($payload), true);
            echo "<script> alert('" . $response["message"] . "'); </script>";
            if ($response["status_code"] == 200) header("refresh:0; url=./logout.php");
        } else {
            echo "<script> alert('The current password is incorrect. Try inputting the correct old password!'); </script>";
        }
    }

    if (isset($_POST["btnUploadPicture"])) {
        $_id = (int) $_SESSION["user"]["user_id"];
        if ($_FILES["picture"]["size"] > 10 && $_id) {
            $response = json_decode($controller->modifyUserPicture($_FILES["picture"], $_id), true);
            if ($response["status_code"] == 200) {
                $_SESSION["user"]["user_picture"] = $response["data"][0];
                $user["user_picture"] = $response["data"][0];
            }
            echo "<script> alert('" . $response["message"] . "'); </script>";
        }
    }
} else {
    header("location: ../login.php");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account - TMS</title>
    <link rel="stylesheet" href="../../static/styles/dashboard.css">
</head>

<body>
    <div class="container">
        <?php include_once('./include/header.php') ?>
        <main>
            <header>
                <div class="bars">
                    <svg id="openMenu" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
                    </svg>
                    <h1>Profile Setting</h1>
                </div>
                <div class="side_menu">
                    <a href="./create-project.php">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span>Start a project</span>
                    </a>
                    <a href="./setting.php">
                        <div class="image_holder">
                            <?php
                            $pic = $user["user_picture"];
                            if ($pic !== NULL && strlen(trim($pic)) > 9) {
                                echo " <img src='" . Helper::loadImage($pic) . "' alt='' /> ";
                            } else echo Helper::getInitialNames($user["user_fullname"]);
                            ?>
                        </div>
                    </a>
                </div>

            </header>
            <div class="content" id="setting">
                <section>
                    <div class="manage_picture">
                        <div class="image_holder">
                            <?php
                            $pic = $user["user_picture"];
                            if ($pic !== NULL && strlen(trim($pic)) > 9) {
                                echo " <img src='" . Helper::loadImage($pic) . "' alt='' /> ";
                            } else echo Helper::getInitialNames($user["user_fullname"]);
                            ?>
                        </div>
                        <div class="control">
                            <form action="" enctype="multipart/form-data" method="post">
                                <label for="picture" id="picture_label">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                </label>
                                <input style="display: none;" type="file" name="picture" id="picture">
                                <button style="display: none;" id="uploadPicture" type="submit" name="btnUploadPicture">Upload Picture</button>
                            </form>
                            <a href="">Clear</a>
                        </div>
                    </div>

                    <ul>
                        <li>
                            <b>Full name</b>
                            <span><?php echo $user["user_fullname"]; ?></span>
                        </li>
                        <li>
                            <b>Username</b>
                            <span><?php echo $user["user_username"]; ?></span>
                        </li>
                        <li>
                            <b>Email</b>
                            <span><?php echo $user["user_email"]; ?></span>
                        </li>
                    </ul>
                </section>
                <section id="update_form">
                    <h3>Update Profile</h3>
                    <form action="" method="post">
                        <div class="formControl">
                            <label for="fullname">Fullname</label>
                            <input type="text" name="fullname" disabled value="<?php echo isset($show_text["fullname"]) ? $show_text["fullname"] : $user["user_fullname"]; ?>" required minlength="5" id="fullname" placeholder="Enter Fullname">
                        </div>
                        <span>Change password</span>
                        <div class="formControl">
                            <label for="password">Current Password</label>
                            <input type="text" required minlength="6" name="password" id="password" placeholder="Enter current password">
                        </div>
                        <div class="formControl">
                            <label for="new_password">New Password</label>
                            <input type="password" required minlength="6" name="new_password" id="new_password" placeholder="Enter new password">
                        </div>
                        <div class="formControl">
                            <button type="submit" name="btnUserAccountUpdate">Update profile</button>
                        </div>
                    </form>
                </section>
            </div>

            <footer>
                &copy; <?php echo date("Y") ?> -- Task Management System (TMS)
            </footer>
        </main>
    </div>

</body>


<script>
    const closeMenu = document.querySelector("#closeMenu");
    const openMenu = document.querySelector("#openMenu");

    openMenu.addEventListener("click", function() {
        document.querySelector("aside").classList.toggle("showMenu");
    });
    closeMenu.addEventListener("click", function() {
        document.querySelector("aside").classList.toggle("showMenu");
    });


    const picture = document.querySelector("#picture");

    picture.addEventListener('change', function() {
        document.querySelector("#uploadPicture").style.display = 'block'
        document.querySelector("#picture_label").style.display = 'none'

    });
</script>

</html>