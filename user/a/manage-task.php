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
$subtasks = [];
$comments = [];
$messages = [];

$attachments = [];


if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 9) {

    $user = $_SESSION["user"];
    $controller = new ProjectController();


    if (isset($_GET["slug"]) && strlen(trim($_GET["slug"])) > 5 && isset($_GET["task"]) && strlen(trim($_GET["task"])) > 5) {
        $slug = $_GET["slug"];
        $task_slug = $_GET["task"];


        if (isset($_GET["action"]) && strlen(trim($_GET["action"])) > 0) {
            $_task_status = (int) $_GET["action"];
            if ($_task_status > 0) {
                $_ts_res = json_decode($controller->modifyTaskStatus($task_slug, $_task_status), true);
                echo "<script> alert('" . $_ts_res["message"] . "'); </script>";
            }
        }


        $res = json_decode($controller->getTaskBySlug($task_slug), true);
        if (count($res["message"]) > 2) {
            $task = $res["message"];
            $task_id = (int) $res["message"]["task_id"];
            $res_attachement = json_decode($controller->getTaskAttachments($task_id), true);

            if (isset($res_attachement["message"]) && count($res["message"]) > 0) {
                $attachments = $res_attachement["message"];
            }



            if (isset($_POST["btnSubTask"])) {
                $input = $_POST["new_sub_task"];
                $payload = array(
                    '_desc' => $input,
                    '_parent_id' => $task_id
                );

                $res1 = json_decode($controller->addSubTaskToTask($payload), true);
                echo "<script> alert('" . $res1["message"] . "'); </script>";

                $_SESSION["activeTab"] = 0;
            }

            if (isset($_POST["btnComment"])) {
                $input = $_POST["add_comment"];
                $payload = array(
                    '_content' => $input,
                    '_user_id' => $user["user_id"],
                    '_task_id' => $task_id
                );

                $res1 = json_decode($controller->makeTaskComment($payload), true);
                echo "<script> alert('" . $res1["message"] . "'); </script>";

                $_SESSION["activeTab"] = 1;
            }

            if (isset($_POST["btnMessage"])) {
                $input = $_POST["task_message"];
                $payload = array(
                    '_user_id' => $user["user_id"],
                    '_content' => $input,
                    '_task_id' => $task_id
                );
                $res1 = json_decode($controller->writeMessage($payload), true);
                echo "<script> alert('" . $res1["message"] . "'); </script>";

                $_SESSION["activeTab"] = 2;
            }


            // Get subtasks
            $subtask_res = json_decode($controller->getTaskSubTasksByParentId($task_id), true);

            // Get comments
            $comments_res = json_decode($controller->getTaskCommentByTaskId($task_id), true);

            // Get messages
            $messages_res = json_decode($controller->getMessagesByTaskId($task_id), true);



            $subtasks = $subtask_res["message"];
            $comments = $comments_res["message"];
            $messages = $messages_res["message"];

            // Temp Note::::

            // Message can be either from Creator or Assigned to.


        }
    } else {
        if (isset($_GET["slug"]) && strlen(trim($_GET["slug"])) > 5) header("refresh:0; url=./project.php?slug=" . $_GET["slug"]);
        else header("refresh:0; url=./projects.php");
    }
} else {
    header("location: ../login.php");
}


$activeTab = isset($_SESSION["activeTab"]) ? $_SESSION["activeTab"] : 0;








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
                    <h1>Task</h1>
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
                if ($user["user_id"] == $task["created_by"] && $task["project_status"] != 1) {
                ?>
                    <section id="extra-menu">
                        <ul>
                            <li> <a href="./edit-task.php?<?php echo "slug=$slug&task=$task_slug"; ?>">Edit Task</a> </li>
                            <li> <button id="btnDeleteTask" data-href="./manage-task.php?<?php echo "slug=$slug&task=$task_slug&delete=true"; ?>">Delete Task</button> </li>
                        </ul>
                    </section>

                <?php
                }
                if (count($task) > 0) {
                    $task_status = (int) $task["task_status"];

                ?>
                    <section id="task">
                        <div class="task_heading">
                            <h2><?php echo $task["task_title"]; ?></h2>
                            <p><?php echo $task["task_desc"]; ?></p>
                            <?php
                            if (count($attachments) > 0) {
                            ?>
                                <div class="attachments">
                                    <div class="head">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                        </svg>
                                        <span>Attachments</span>
                                    </div>
                                    <div class="attachment_list">
                                        <?php
                                        foreach ($attachments as $key => $attachment) {
                                        ?>
                                            <a href="<?php echo $attachment["file_path"]; ?>" target="_blank" class="attachment_item">
                                                Attachment <?php echo $key + 1; ?>
                                            </a>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                        </div>
                        <div class="extra_info">
                            <div class="info_list">
                                <span>Assignee</span>
                                <strong>
                                    <?php echo "@" . $task["user_username"]; ?>
                                </strong>
                            </div>
                            <div class="info_list">
                                <span>Deadline</span>
                                <strong><?php echo  $task["deadline"]; ?></strong>
                            </div>

                            <div class="info_list">
                                <span>Status</span>
                                <label><?php echo Helper::getTaskStatus($task_status); ?></label>
                            </div>



                            <div class="info_list">
                                <span>Action</span>
                                <div class="action_list">
                                    <?php
                                    $next_task = $task_status + 1;
                                    $prev_task = $task_status - 1;
                                    if ($task_status > 0) {
                                    ?>
                                        <a href="./manage-task.php?<?php echo "slug=$slug&task=$task_slug&action=$prev_task"; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061A1.125 1.125 0 0 1 21 8.689v8.122ZM11.25 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061a1.125 1.125 0 0 1 1.683.977v8.122Z" />
                                            </svg>
                                            Move to
                                            <?php echo Helper::getTaskAction($prev_task); ?>
                                        </a>
                                    <?php
                                    }
                                    if ($task_status < 3) {
                                    ?>
                                        <a href="./manage-task.php?<?php echo "slug=$slug&task=$task_slug&action=$next_task"; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                                            </svg>
                                            Move to
                                            <?php echo Helper::getTaskAction($next_task); ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="tabs">
                            <div class="task_tab">
                                <div class="tab_item <?php echo $activeTab == 0 ? 'active' : '' ?>" id="subTask" data-id="subTask">
                                    <b data-id="subTask">Subtasks</b>
                                    <span data-id="subTask"><?php echo count($subtasks); ?></span>
                                </div>
                                <div class="tab_item <?php echo $activeTab == 1 ? 'active' : '' ?>" id="comment" data-id="comment">
                                    <b data-id="comment">Comments</b>
                                    <span data-id="comment"><?php echo count($comments); ?></span>
                                </div>
                                <div class="tab_item <?php echo $activeTab == 2 ? 'active' : '' ?>" id="message_" data-id="message_">
                                    <b data-id="message_">Message</b>
                                </div>
                            </div>
                            <div class="task_tab_details">
                                <div class="task_subtask <?php echo $activeTab == 0 ? 'active' : '' ?>" id="subTask_details">
                                    <?php
                                    if (count($subtasks) > 0) {
                                    ?>
                                        <ul>
                                            <?php
                                            foreach ($subtasks as $key => $subtask) {
                                            ?>
                                                <li data-slug="<?php echo $subtask["subtask_slug"]; ?>" data-status="<?php echo $subtask["subtask_status"]; ?>" class="checkbox_item <?php echo $subtask["subtask_status"] == 1 ? "checked" : "" ?>">
                                                    <div class="custom-checkbox" data-slug="<?php echo $subtask["subtask_slug"]; ?>" data-status="<?php echo $subtask["subtask_status"] == 0 ? 1 : 0; ?>">
                                                        <input type="checkbox" <?php echo $subtask["subtask_status"] == 1 ? 'checked' : ''; ?> id="<?php echo $subtask["subtask_slug"]; ?>" name="subtask_1" data-slug="<?php echo $subtask["subtask_slug"]; ?>" data-status="<?php echo $subtask["subtask_status"] == 0 ? 1 : 0; ?>">
                                                        <label for="<?php echo $subtask["subtask_slug"]; ?>" data-slug="<?php echo $subtask["subtask_slug"]; ?>" data-status="<?php echo $subtask["subtask_status"] == 0 ? 1 : 0; ?>"></label>
                                                    </div>
                                                    <p data-slug="<?php echo $subtask["subtask_slug"]; ?>" data-status="<?php echo $subtask["subtask_status"] == 0 ? 1 : 0; ?>"><?php echo $subtask["subtask_desc"]; ?></p>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    <?php
                                    }
                                    if ($user["user_id"] == $task["assigned_to"]) {
                                    ?>
                                        <form action="" method="post">
                                            <input type="text" name="new_sub_task" id="new_sub_task" required placeholder="Type in the subtask accordingly...">
                                            <button type="submit" name="btnSubTask">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                                </svg>
                                            </button>
                                        </form>
                                    <?php } ?>
                                </div>

                                <div class="task_comment <?php echo $activeTab == 1 ? 'active' : '' ?>" id="comment_details">

                                    <?php
                                    if (count($comments) > 0) {
                                    ?>
                                        <ul>
                                            <?php
                                            foreach ($comments as $key => $comment) {
                                            ?>
                                                <li>
                                                    <p><?php echo $comment["comment_content"]; ?></p>
                                                    <div class="wrapper">
                                                        <div class="user">
                                                            <div class="image_holder">
                                                                <?php
                                                                $pic = $comment["user_picture"];
                                                                if ($pic !== NULL && strlen(trim($pic)) > 9) {
                                                                    echo " <img src='" . Helper::loadImage($pic) . "' alt='' /> ";
                                                                } else echo Helper::getInitialNames($comment["user_fullname"]);
                                                                ?>
                                                            </div>
                                                            <div class="info">
                                                                <strong><?php echo $comment["user_fullname"]; ?> </strong>
                                                                <span><?php echo Helper::convertTime($comment["created_at"]); ?> ago</span>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if ($user["user_id"] == $task["assigned_to"]) {
                                                        ?>
                                                            <svg class="active" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                                            </svg>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    <?php
                                    }
                                    ?>
                                    <form action="" method="post">
                                        <input type="text" name="add_comment" required minlength="10" id="add_comment" required placeholder="Enter your comment...">
                                        <button type="submit" name="btnComment">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <div class="task_message <?php echo $activeTab == 2 ? 'active' : '' ?>" id="message_details">
                                    <?php
                                    if (count($messages) > 0) {
                                    ?>
                                        <ul>
                                            <?php
                                            foreach ($messages as $key => $message) {
                                            ?>
                                                <li>
                                                    <p><?php echo $message["message_content"]; ?></p>
                                                    <div class="user">
                                                        <div class="info">
                                                            <div class="image_holder">
                                                                <?php
                                                                $pic = $message["user_picture"];
                                                                if ($pic !== NULL && strlen(trim($pic)) > 9) {
                                                                    echo " <img src='" . Helper::loadImage($pic) . "' alt='' /> ";
                                                                } else echo Helper::getInitialNames($message["user_fullname"]);
                                                                ?>
                                                            </div>
                                                            <strong><?php echo ucfirst($message["user_fullname"]); ?></strong>
                                                        </div>
                                                        <span>23 min ago</span>
                                                    </div>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    <?php
                                    }

                                    if ($user["user_id"] == $task["assigned_to"] || $user["user_id"] == $task["created_by"]) {
                                    ?>
                                        <form action="" method="post">
                                            <textarea name="task_message" required minlength="10" id="task_message" rows="5" placeholder="Add a message to this task"></textarea>
                                            <button type="submit" name="btnMessage">
                                                Send Message
                                            </button>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
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


    const btnDeleteTask = document.querySelector("#btnDeleteTask");

    btnDeleteTask.addEventListener('click', function() {
        if (confirm("Are you sure, you want to delete this task?")) {
            window.location.href = this.getAttribute('data-href');
        }
    });

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