<?php
$path = "";
$page = "projects";

use app\controller\ProjectController;
use app\utils\Helper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include '../../vendor/autoload.php';

$user = [];
$project = [];
$members = [];
$created_task = 0;
$pending_task = 0;
$completed_task = 0;

$slug = "";
session_start();
if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 9) {

    $user = $_SESSION["user"];

    $controller = new ProjectController();

    if (isset($_GET["slug"])) {

        $slug = $_GET["slug"];


        if (isset($_GET["delete"]) && $_GET["delete"] == 'true') {
            $res = json_decode($controller->deleteProject($slug), true);
            if ($res["status_code"] == 200) {
                header('refresh:0; url=./projects.php');
            }
            echo "<script> alert('" . $res["message"] . "'); </script>";
        }

        if (isset($_GET["mark"]) && $_GET["mark"] == 'true') {
            $res = json_decode($controller->markAsCompleteProject($slug), true);
            echo "<script> alert('" . $res["message"] . "'); </script>";
        }





        $res = json_decode($controller->getProjectBySlug($slug), true);

        if (count($res["message"]) > 0 && isset($res["message"]["project_id"])) {
            $project = $res["message"];
            $res_members = json_decode($controller->getProjectMembers($project["project_id"]), true);
            $members = $res_members["message"];

            if (isset($project["tasks"]) && count($project["tasks"]) > 0) {

                foreach ($project["tasks"] as $key => $_task) {
                    if ($_task["task_status"] == 3) {
                        $completed_task += 1;
                    } else {
                        $pending_task += 1;
                    }
                    $created_task += 1;
                }
            }
        }
    } else header("refresh:0; url=./projects.php");
} else {
    header("location: ../login.php");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Information - TMS</title>
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
                    <h1>Project Information</h1>
                </div>
                <div class="side_menu">

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

                <section id="project-details">

                    <ul>
                        <li>
                            <strong>Project Title</strong>
                            <p><?php echo isset($project["project_name"]) ? $project["project_name"] : ""; ?></p>
                        </li>
                        <li>
                            <strong>Deadline</strong>
                            <p><?php echo isset($project["project_deadline"]) ? $project["project_deadline"] : ""; ?></p>
                            <p></p>
                        </li>
                        <li>
                            <strong>Date Created</strong>
                            <p><?php echo isset($project["created_at"]) ? $project["created_at"] : ""; ?></p>
                        </li>
                        <li>
                            <strong>Members &nbsp; &nbsp; <?php echo number_format(count($members)); ?> </strong>
                            <?php
                            if (count($members) > 0) {
                                echo "<ul>";
                                foreach ($members as $key => $member) {
                                    $placeholder = $member['user_fullname'] . " <small>(" . ($member["pu_status"] == 1 ? "Active" : ($member["pu_status"] == 4 ? "Delisted" : "Active")) . ")</small>";
                                    echo " <li> $placeholder </li> ";
                                }
                                echo "</ul>";
                            }
                            ?>
                        </li>
                        <li>
                            <strong>Created Task</strong>
                            <p><?php echo $created_task . " Task(s)"; ?></p>
                        </li>
                        <li>
                            <strong>Pending Task</strong>
                            <p><?php echo $pending_task . " Task(s)"; ?></p>
                        </li>
                        <li>
                            <strong>Completed Task</strong>
                            <p><?php echo $completed_task . " Task(s)"; ?></p>
                        </li>
                        <li>
                            <strong>Color Label</strong>
                            <p><?php echo isset($project["project_color"]) ? $project["project_color"] : ""; ?></p>
                        </li>
                        <li>
                            <strong>Creator</strong>
                            <p><?php echo isset($project["user_fullname"]) ? $project["user_fullname"] . " <small>(" . $project["user_username"] . ")</small>" : ""; ?></p>
                        </li>
                    </ul>

                    <?php

                    if ($user["user_id"] == $project["creator_id"] && $project["project_status"] == 0) {
                    ?>
                        <div class="" style="display: flex; gap:20px; align-items: center; flex-wrap: wrap;">
                            <button id="delete_project" data-href="./project-detail.php?slug=<?php echo $slug; ?>&delete=true">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg>

                                <span>Delete Project</span>
                            </button>

                            <a href="./project-detail.php?slug=<?php echo $slug; ?>&mark=true">
                                <span>Mark Project as Complete</span>
                            </a>
                        </div>
                    <?php

                    }
                    ?>

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

    const btnDeleteProject = document.querySelector("#delete_project");


    btnDeleteProject.addEventListener("click", function() {
        if (confirm("Are you sure, you want to delete this project?")) {
            window.location.href = this.getAttribute('data-href');
        }
    });
</script>

</html>