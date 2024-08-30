<?php
$path = "";
$page = "projects";


if (isset($_GET["slug"]) && strlen(trim($_GET["slug"])) > 5) {
    $slug = $_GET["slug"];
}

use app\controller\ProjectController;
use app\utils\Helper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




include '../../vendor/autoload.php';
session_start();


$user = [];

$slug = "";
$task_slug = "";
$task = [];
$members = [];

$attachments = [];


if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 9) {

    $user = $_SESSION["user"];
    $controller = new ProjectController();


    if (isset($_GET["slug"]) && strlen(trim($_GET["slug"])) > 5 && isset($_GET["task"]) && strlen(trim($_GET["task"])) > 5) {
        $slug = $_GET["slug"];
        $task_slug = $_GET["task"];


        // btnUpdateTask

        if (isset($_POST["btnUpdateTask"])) {
            $title = $_POST["t_title"];
            $member_id = $_POST["t_members"];
            $deadline = $_POST["deadline"];
            $desc = $_POST["t_desc"];

            $payload = array(
                '_title' => $title,
                '_desc' => $desc,
                '_assigned_to' => $member_id,
                '_deadline' => $deadline,
                "_slug" => $task_slug
            );


            $task_resp = json_decode($controller->modifyTask($payload), true);
            echo "<script> alert('" . $task_resp["message"] . "'); </script>";
        }







        $res = json_decode($controller->getTaskBySlug($task_slug), true);


        var_dump($res);
        if (count($res["message"]) > 2) {
            $task = $res["message"];
            // $task_id = (int) $res["message"]["task_id"];

            $project_id = (int) $res["message"]["task_project_id"];

            $res_member = json_decode($controller->getProjectMembers($project_id), true);

            $members = array_map(function ($item) {
                return array(
                    "title" => 'Member',
                    "id" => $item["pu_member"],
                    "username" => $item["user_username"]
                );
            }, $res_member["message"]);

            array_unshift($members, array(
                "title" => 'Assigned To',
                "id" => $task["user_id"],
                "username" => $task["user_username"]
            ));
        }
    } else {
        if (isset($_GET["slug"]) && strlen(trim($_GET["slug"])) > 5) header("refresh:0; url=./project.php?slug=" . $_GET["slug"]);
        else header("refresh:0; url=./projects.php");
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
                    <h1>Edit Task</h1>
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

                <?php
                if (count($task) > 0) {
                ?>
                    <section id="task">
                        <form action="" method="post">
                            <div class="formControl">
                                <label for="t_title">Task Title</label>
                                <input type="text" name="t_title" id="t_title" value="<?php echo $task["task_title"]; ?>" placeholder="Enter task title" required>
                            </div>
                            <div class="formControl">
                                <label for="t_members">Assign to:</label>
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
                                <input type="date" required name="deadline" value="<?php echo $task["deadline"]; ?>" id="deadline">
                            </div>
                            <div class="formControl">
                                <label for="t_desc">Task Description</label>
                                <textarea name="t_desc" id="t_desc" cols="30" rows="7"><?php echo $task["task_desc"]; ?></textarea>
                            </div>
                            <div class="formControl">
                                <button type="submit" name="btnUpdateTask">Update Task</button>
                            </div>
                        </form>
                    </section>
                <?php
                } else echo "<p>Undefined task</p>";
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

    var storage = window.localStorage;


    openMenu.addEventListener("click", function() {
        document.querySelector("aside").classList.toggle("showMenu");
    });
    closeMenu.addEventListener("click", function() {
        document.querySelector("aside").classList.toggle("showMenu");
    });


    const tab_item = document.querySelectorAll(".tab_item");

    for (let i = 0; i < tab_item.length; i++) {
        tab_item[i].addEventListener('click', function(e) {
            initializeTab(e.target.getAttribute('data-id'))
        })

    }

    for (let i = 0; i < tab_item.length; i++) {
        const activeTab = storage.getItem("activeTab");
        if (activeTab == i) initializeTab(tab_item[i].getAttribute('data-id'));
    }

    const subTask = document.querySelectorAll(".checkbox_item");

    for (let i = 0; i < subTask.length; i++) {
        subTask[i].addEventListener('click', function(e) {
            const slug = e.target.getAttribute("data-slug");
            const status = e.target.getAttribute("data-status");
            // alert(`Status: ${status} and Slug: ${slug}`);
            console.log(this.querySelector("input"));

            const res = sendData(slug, status);
            if (res) {
                if (status == 1) {
                    e.target.setAttribute("data-status", 0);
                    this.querySelector("input").setAttribute("checked", true)
                    this.setAttribute("class", "checked")
                } else {
                    e.target.setAttribute("data-status", 1);
                    this.querySelector("input").removeAttribute("checked", true)
                    this.removeAttribute("class", "checked")
                }
            }
        });

    }

    async function sendData(slug, status) {
        const url = './api/ajax-process.php'; // URL of the PHP script

        try {
            // Send the request with the data
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    slug: slug,
                    status: status
                })
            });

            if (response.ok) {
                const data = await response.json();
                console.log(data);

                if (data.message === true) {
                    return true; // Indicate success
                } else {
                    console.error('Server response message is not true.');
                    return false;
                }
            } else {
                console.error('Network response was not ok.');
                return false;
            }
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
            return false;
        }
    }

    function setStorage(index) {
        storage.setItem("activeTab", index);
    }


    function initializeTab(id) {
        switch (id) {

            case 'comment':
                document.querySelector('#subTask_details').classList.remove('active');
                document.querySelector('#comment_details').classList.add('active');
                document.querySelector('#message_details').classList.remove('active');
                document.querySelector('#subTask').classList.remove('active');
                document.querySelector('#comment').classList.add('active');
                document.querySelector('#message_').classList.remove('active');
                setStorage(1);
                break;
            case 'message_':
                document.querySelector('#subTask_details').classList.remove('active');
                document.querySelector('#comment_details').classList.remove('active');
                document.querySelector('#message_details').classList.add('active');
                document.querySelector('#subTask').classList.remove('active');
                document.querySelector('#comment').classList.remove('active');
                document.querySelector('#message_').classList.add('active');
                setStorage(2);
                break;

            default:
                document.querySelector('#subTask_details').classList.add('active');
                document.querySelector('#comment_details').classList.remove('active');
                document.querySelector('#message_details').classList.remove('active');
                document.querySelector('#subTask').classList.add('active');
                document.querySelector('#comment').classList.remove('active');
                document.querySelector('#message_').classList.remove('active');
                setStorage(0);
                break;
        }
    }
</script>

</html>