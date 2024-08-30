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


            $task_res = json_decode($controller->getProjectTasksById($project_id), true);
            if (count($task_res["message"]) > 0) {
                var_dump($task_res);
            }
        }
    } else header("refresh:0; url=./projects.php");
} else {
    header("location: ../login.php");
}



function sortByStatus(array $_tasks, int $status)
{
    if (count($_tasks) == 0) return [];

    $result = [];

    return $result;
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
                    <a href="./edit-project.php?slug=<?php echo isset($project["project_slug"]) ? $project["project_slug"] : ""; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"></path>
                        </svg>
                        <span>Edit Project</span>
                    </a>
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
                                            <a href="./manage-task.php?slug=abc&task=jkiksk">
                                                <p class="line-clamp-2"><?php echo $task["task_title"]; ?></p>

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
                            ?>

                            <div id="add_task">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span>New Task</span>
                            </div>
                        </div>
                        <div class="tab-item">
                            <div class="tab-item-header">
                                <h3>In Progress</h3>
                                <span style="background-color: #F90594;"><?php echo count($tasks_unstarted); ?></span>
                            </div>

                            <?php
                            if (count($tasks_unstarted) > 0) {
                            ?>
                                <ul>
                                    <?php
                                    foreach ($tasks_unstarted as $key => $task) {
                                    ?>
                                        <li>
                                            <a href="./manage-task.php?slug=abc&task=jkiksk">
                                                <p class="line-clamp-2"><?php echo $task["task_title"]; ?></p>
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

                            <ul>
                                <li>
                                    <a href="./manage-task.php?slug=abc&task=jkiksk">
                                        <p class="line-clamp-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi sed cupiditate ex doloribus.</p>

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
                                            <span> 2 Days</span>
                                        </div>
                                        <div class="li_task_status">
                                            <span class="task_in">Feedback</span>
                                        </div>
                                        <label style="background-color: #9382FF;"></label>
                                    </a>
                                </li>
                                <li>
                                    <a href="./manage-task.php?slug=abc&task=jkiksk">
                                        <p class="line-clamp-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi sed cupiditate ex doloribus.</p>

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
                                            <span> 2 Days</span>
                                        </div>
                                        <label style="background-color: #9382FF;"></label>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-item">
                            <div class="tab-item-header">
                                <h3>Completed</h3>
                                <span style="background-color: #C5C594;"><?php echo count($tasks_unstarted); ?></span>
                            </div>
                            <ul>
                                <li>
                                    <a href="./manage-task.php?slug=abc&task=jkiksk">
                                        <p class="line-clamp-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi sed cupiditate ex doloribus.</p>

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
                                            <span> 2 Days </span>
                                        </div>
                                        <div class="li_task_status">
                                            <span class="task_complete">Awaiting Approval</span>
                                        </div>
                                        <label style="background-color: #9382FF;"></label>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-item">
                            <div class="tab-item-header">
                                <h3>Finished</h3>
                                <span style="background-color: #409594;"><?php echo count($tasks_unstarted); ?></span>
                            </div>
                            <ul>
                                <li>
                                    <a href="./manage-task.php?slug=abc&task=jkiksk">
                                        <p class="line-clamp-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi sed cupiditate ex doloribus.</p>

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
                                            <span> 2 Days</span>
                                        </div>
                                        <div class="li_task_status">
                                            <span class="task_done">Done</span>
                                        </div>
                                        <label style="background-color: #9382FF;"></label>
                                    </a>
                                </li>
                                <li>
                                    <a href="./manage-task.php?slug=abc&task=jkiksk">
                                        <p class="line-clamp-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi sed cupiditate ex doloribus.</p>

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
                                            <span> 2 Days </span>
                                        </div>
                                        <label style="background-color: #9382FF;"></label>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>

            <footer>
                &copy; <?php echo date("Y") ?> -- Task Management System (TMS)
            </footer>
        </main>
    </div>


    <div id="modal" style="display: none;">
        <div class="modal-add-task">
            <div class="modal-header">
                <h3>Add Task</h3>
                <svg id="modal_close" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="formControl">
                    <label for="deadline">Task Title</label>
                    <input type="text" name="t_title" id="t_title" placeholder="Enter task title" required>
                </div>
                <div class="formControl">
                    <label for="deadline">Assign to:</label>
                    <select name="t_members" id="t_members" required>
                        <?php
                        foreach ($members as $key => $member) {
                        ?>
                            <option value="<?php echo $member["id"]; ?>"> <?php echo ucwords($member["username"] . " - " . $member["title"]); ?> </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="formControl">
                    <label for="deadline">Deadline</label>
                    <input type="date" required name="deadline" id="deadline">
                </div>
                <div class="formControl">
                    <label for="deadline">Task Description</label>
                    <textarea name="t_desc" id="t_desc" cols="30" rows="7" required></textarea>
                </div>
                <div class="formControl">
                    <label for="attachment">Attachment</label>
                    <input type="file" multiple required name="attachment" id="attachment">
                </div>
                <div class="formControl">
                    <button type="submit" name="btnAddTask">Add Task</button>
                </div>
            </form>
        </div>
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


    const add_task = document.querySelector("#add_task");
    const modal_close = document.querySelector("#modal_close");


    add_task.addEventListener('click', () => document.querySelector("#modal").style.display = "block");
    modal_close.addEventListener('click', () => document.querySelector("#modal").style.display = "none");




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