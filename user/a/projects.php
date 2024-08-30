<?php
$path = "";
$page = "projects";

use app\controller\ProjectController;
use app\utils\Helper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




include '../../vendor/autoload.php';


$projects = [];
$complete_project_index = [];
$user = [];

session_start();
if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 9) {

    $controller = new ProjectController();
    $user = $_SESSION["user"];


    $res = json_decode($controller->getCreatorProjects((int) $user['user_id']), true);
    $projects = $res["message"];
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
                    <h1>Project list</h1>
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
            <div class="content">

                <section id="page_num">
                    <p> <strong>Count:</strong> <?php echo number_format(count($projects)); ?></p>

                    <a href="./create-project.php">
                        Start a new project
                    </a>
                </section>


                <?php
                if (count($projects) > 0) {
                ?>
                    <section id="list">
                        <h3>List of Ongoing Projects</h3>
                        <ul>

                            <?php
                            foreach ($projects as $key => $project) {
                                if ($project["project_status"] > 0) continue;
                            ?>
                                <li>
                                    <a href="./project.php?slug=<?php echo $project["project_slug"]; ?>">
                                        <p class="line-clamp-2"><?php echo $project["project_name"]; ?></p>
                                        <div class="ul_task">
                                            <div class="li_task">
                                                <span>8 Task(s)</span>
                                                <span>0%</span>
                                            </div>
                                            <div class="li_task">
                                                <div class="users">
                                                    <ul>
                                                        <li>
                                                            <div class="image_holder">AC</div>
                                                        </li>
                                                        <li>
                                                            <div class="image_holder">
                                                                <img src="../../static/images/no-image.png" alt="">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <span> <?php echo Helper::formatDate($project["created_at"]); ?> >> <?php echo Helper::formatDate($project["project_deadline"]); ?> </span>
                                            </div>
                                        </div>

                                        <label style="background-color: <?php echo $project["project_color"]; ?>;"></label>
                                    </a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>

                    </section>
                <?php
                }

                if (count($complete_project_index) > 0) {
                ?>
                    <section id="list">
                        <h3>List of Completed Projects</h3>
                        <ul>
                            <li>
                                <a href="./project.php?slug=">
                                    <p class="line-clamp-2">Design the banner for the twitter lead campaign</p>
                                    <div class="li_task">
                                        <span>2 Task(s)</span>
                                        <span>100%</span>
                                    </div>
                                    <div class="li_task">
                                        <div class="users">
                                            <ul>
                                                <li>
                                                    <div class="image_holder">
                                                        <img src="../../static/images/no-image.png" alt="">
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <span> 01 Aug / 04 Aug </span>
                                    </div>
                                    <label></label>
                                </a>
                            </li>
                        </ul>
                    </section>
                <?php
                }
                ?>


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


    // document.addEventListener("DOMContentLoaded", () => {
    //     const innerLines = document.querySelectorAll('.inner-line');

    //     innerLines.forEach(innerLine => {
    //         const percentage = innerLine.getAttribute('percentage');
    //         if (percentage) {
    //             innerLine.style.setProperty('--percentage-width', percentage);
    //         }
    //     });
    // });
</script>

</html>