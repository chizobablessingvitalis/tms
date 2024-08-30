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
$tasks_unstarted = [];
$tasks_completed = [];
$tasks_inprogress = [];
$tasks_finished = [];

$slug = "";

session_start();
if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 9) {

    $controller = new ProjectController();
    $user = $_SESSION["user"];

    if (isset($_GET["slug"]) && strlen(trim($_GET["slug"])) > 5) {
        $slug = $_GET["slug"];
        $res = json_decode($controller->getProjectBySlug($slug), true);
        if ($res["status_code"] == 200 && $res["message"]["project_id"]) {
            $project_id = (int) $res["message"]["project_id"];

            $res_member = json_decode($controller->getProjectMembers($project_id), true);

            $members = array_map(function ($item) {
                return array(
                    "title" => 'Member',
                    "id" => $item["pu_member"],
                    "username" => $item["user_username"]
                );
            }, $res_member["message"]);

            array_unshift($members, array(
                "title" => 'Creator',
                "id" => $res["message"]["creator_id"],
                "username" => $res["message"]["user_username"]
            ));
            $project = $res["message"];


            if (isset($_POST["btnAddTask"])) {
                $title = $_POST["t_title"];
                $member_id = $_POST["t_members"];
                $deadline = $_POST["deadline"];
                $desc = $_POST["t_desc"];
                $files = $_FILES["attachment"];

                $payload = array(
                    '_project_id' => $project_id,
                    '_title' => $title,
                    '_desc' => $desc,
                    '_assigned_to' => $member_id,
                    '_created_by' => $user["user_id"],
                    '_deadline' => $deadline
                );

                $task_resp = json_decode($controller->addTaskToProject($payload, $files), true);
                echo "<script> alert('" . $task_resp["message"] . "'); </script>";
            }


            $task_res = json_decode($controller->getProjectTasksByProjectId($project_id, (int) $user["user_id"]), true);

            if (count($task_res["message"]) > 0) {
                foreach ($task_res["message"] as $key => $task) {
                    switch ((int) $task["task_status"]) {
                        case 1: // inProgress
                            array_push($tasks_inprogress, $task);
                            break;
                        case 2: // completed
                            array_push($tasks_completed, $task);
                            break;
                        case 3: // finished
                            array_push($tasks_finished, $task);
                            break;
                        default: # Unstarted
                            array_push($tasks_unstarted, $task);
                            break;
                    }
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
                    <h1><?php echo isset($project["project_name"]) ? $project["project_name"] : "Undefined title"; ?></h1>
                </div>
                <div class="side_menu">
                    <a href="./create-project.php" style="display: none;">
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
                <div id="submenu">
                    <a href="./project-detail.php?slug=<?php echo isset($project["project_slug"]) ? $project["project_slug"] : ""; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <span>Project Information</span>
                    </a>
                </div>
                <section id="progress">

                    <div id="tabs">
                        <div class="tab-item">
                            <div class="tab-item-header">
                                <h3>Unstarted</h3>
                                <span><?php echo count($tasks_unstarted); ?></span>
                            </div>
                            <?php
                            if (count($tasks_unstarted) > 0) {
                            ?>
                                <ul>
                                    <?php
                                    foreach ($tasks_unstarted as $key => $task) {
                                    ?>
                                        <li>
                                            <a href="./manage-task.php?slug=<?php echo $slug; ?>&task=<?php echo $task["task_slug"]; ?>">
                                                <p class="line-clamp-2"><?php echo $task["task_title"]; ?></p>

                                                <div class="li_task">
                                                    <div class="users">
                                                        <ul>
                                                            <li>
                                                                <div class="image_holder">
                                                                    <?php
                                                                    $pic = $task["user_picture"];
                                                                    if ($pic !== NULL && strlen(trim($pic)) > 9) {
                                                                        echo " <img src='" . Helper::loadImage($pic) . "' alt='' /> ";
                                                                    } else echo Helper::getInitialNames($task["user_fullname"]);
                                                                    ?>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <span> 2 Days </span>
                                                </div>
                                                <label style="background-color: #9382FF;"></label>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            <?php
                            }
                            if ($project["project_status"] == 0) {
                            ?>
                                <div id="add_task">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    <span>New Task</span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="tab-item">
                            <div class="tab-item-header">
                                <h3>In Progress</h3>
                                <span style="background-color: #F90594;"><?php echo count($tasks_inprogress); ?></span>
                            </div>

                            <?php
                            if (count($tasks_inprogress) > 0) {
                            ?>
                                <ul>
                                    <?php
                                    foreach ($tasks_inprogress as $key => $task) {
                                    ?>
                                        <li>
                                            <a href="./manage-task.php?slug=<?php echo $slug; ?>&task=<?php echo $task["task_slug"]; ?>">
                                                <p class="line-clamp-2"><?php echo $task["task_title"]; ?></p>
                                                <div class="li_task">
                                                    <div class="users">
                                                        <ul>
                                                            <li>
                                                                <div class="image_holder">
                                                                    <?php
                                                                    $pic = $task["user_picture"];
                                                                    if ($pic !== NULL && strlen(trim($pic)) > 9) {
                                                                        echo " <img src='" . Helper::loadImage($pic) . "' alt='' /> ";
                                                                    } else echo Helper::getInitialNames($task["user_fullname"]);
                                                                    ?>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <span> 2 Days</span>
                                                </div>
                                                <div class="li_task_status">
                                                    <span class="task_in">Feedback</span>
                                                </div>
                                                <label style="background-color: #9382FF;"></label>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            <?php
                            }
                            ?>
                        </div>

                        <div class="tab-item">
                            <div class="tab-item-header">
                                <h3>Completed</h3>
                                <span style="background-color: #C5C594;"><?php echo count($tasks_completed); ?></span>
                            </div>
                            <?php
                            if (count($tasks_completed) > 0) {
                            ?>
                                <ul>
                                    <?php
                                    foreach ($tasks_completed as $key => $task) {
                                    ?>
                                        <li>
                                            <a href="./manage-task.php?slug=<?php echo $slug; ?>&task=<?php echo $task["task_slug"]; ?>">
                                                <p class="line-clamp-2"><?php echo $task["task_title"]; ?></p>
                                                <div class="li_task">
                                                    <div class="users">
                                                        <ul>
                                                            <li>
                                                                <div class="image_holder">
                                                                    <?php
                                                                    $pic = $task["user_picture"];
                                                                    if ($pic !== NULL && strlen(trim($pic)) > 9) {
                                                                        echo " <img src='" . Helper::loadImage($pic) . "' alt='' /> ";
                                                                    } else echo Helper::getInitialNames($task["user_fullname"]);
                                                                    ?>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <span> 2 Days</span>
                                                </div>
                                                <div class="li_task_status">
                                                    <span class="task_complete">Awaiting Approval</span>
                                                </div>
                                                <label style="background-color: #9382FF;"></label>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="tab-item">
                            <div class="tab-item-header">
                                <h3>Finished</h3>
                                <span style="background-color: #409594;"><?php echo count($tasks_unstarted); ?></span>
                            </div>
                            <?php
                            if (count($tasks_completed) > 0) {
                            ?>
                                <ul>
                                    <?php
                                    foreach ($tasks_completed as $key => $task) {
                                    ?>
                                        <li>
                                            <a href="./manage-task.php?slug=<?php echo $slug; ?>&task=<?php echo $task["task_slug"]; ?>">
                                                <p class="line-clamp-2"><?php echo $task["task_title"]; ?></p>
                                                <div class="li_task">
                                                    <div class="users">
                                                        <ul>
                                                            <li>
                                                                <div class="image_holder">
                                                                    <?php
                                                                    $pic = $task["user_picture"];
                                                                    if ($pic !== NULL && strlen(trim($pic)) > 9) {
                                                                        echo " <img src='" . Helper::loadImage($pic) . "' alt='' /> ";
                                                                    } else echo Helper::getInitialNames($task["user_fullname"]);
                                                                    ?>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <span> 2 Days</span>
                                                </div>
                                                <div class="li_task_status">
                                                    <span class="task_done">Done</span>
                                                </div>
                                                <label style="background-color: #9382FF;"></label>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
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

</script>

</html>