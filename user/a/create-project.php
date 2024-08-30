<?php
$path = "";
$page = "projects";

use app\controller\ProjectController;
use app\utils\Helper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




include '../../vendor/autoload.php';


$threatCount = 0;

session_start();
if (isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] && isset($_SESSION["user"]) && is_array($_SESSION["user"]) && count($_SESSION["user"]) == 9) {

    $user = $_SESSION["user"];

    if (isset($_POST["btnProjectCreation"])) {
        $title = $_POST["title"];
        $deadline = $_POST["deadline"];
        $real_members = $_POST["real_members"];
        $color = $_POST["color"];

        $controller = new ProjectController();

        $payload = array(
            '_name' => $title,
            '_deadline' => $deadline,
            '_creator_id' => $user['user_id'],
            '_color' => $color,
            '_members' => $real_members
        );

        $response = json_decode($controller->addProject($payload), true);
        if ($response["status_code"] == 201 || $response["status_code"] == 422) {
            echo "<script> alert('" . $response["message"] . "'); </script>";
            header("refresh:0; url=./projects.php");
        } else {
            echo "<script> alert('" . $response["message"] . "'); </script>";
            $show_text = ["title" => $title, "deadline" => $deadline];
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
                    <h1>Create a new Project</h1>
                </div>
                <div class="side_menu">
                    <a href="" style="display: none;">
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

                <section id="create-project">
                    <form action="" method="post">
                        <div class="formControl">
                            <label for="title">Project Title</label>
                            <input type="text" name="title" value="<?php echo isset($show_text["title"]) ? $show_text["title"] : ""; ?>" required minlength="5" id="title" placeholder="Enter Project Title">
                        </div>
                        <div class="formControl">
                            <label for="deadline">Deadline</label>
                            <input type="date" required name="deadline" id="deadline" value="<?php echo isset($show_text["deadline"]) ? $show_text["deadline"] : ""; ?>">
                        </div>
                        <div class="formControl">
                            <label for="password">Invite members</label>
                            <input type="text" name="members" id="members" placeholder="Enter Usernames">
                            <input type="hidden" required minlength="5" name="real_members" id="real_members">
                            <small>Enter members name by seperating them with comma.</small>
                        </div>
                        <ul id="block-tags"></ul>

                        <div class="formControl">
                            <label for="color">Color</label>
                            <input type="color" name="color" id="color" required>
                        </div>
                        <div class="formControl">
                            <button type="submit" name="btnProjectCreation">Create project</button>
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

    var arr_username = [];


    function handleKeyPress(event) {
        if (event.keyCode === 32 || event.keyCode === 13) {
            const username = event.target.value.trim();
            if (username) {
                appendNameWithSvg(username);
                arr_username.push(username);
                event.target.value = ''; // Clear the input field
            }
            event.target.focus(); // Set focus on the input element
            realMembers();
        }
    }

    function appendNameWithSvg(name) {
        const ul = document.getElementById('block-tags');

        // Create a new li element
        const li = document.createElement('li');
        li.textContent = name;

        // Create the SVG element
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('class', 'cancel_member');
        svg.setAttribute('fill', 'none');
        svg.setAttribute('viewBox', '0 0 24 24');
        svg.setAttribute('stroke-width', '1.5');
        svg.setAttribute('stroke', 'currentColor');
        svg.setAttribute('data-id', arr_username.length)

        // Create the path element for the SVG
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        path.setAttribute('stroke-linecap', 'round');
        path.setAttribute('stroke-linejoin', 'round');
        path.setAttribute('d', 'M6 18 18 6M6 6l12 12');
        path.setAttribute('data-id', arr_username.length)


        // Append the path to the SVG
        svg.appendChild(path);

        // Append the SVG to the li
        li.appendChild(svg);

        // Append the li to the ul
        ul.appendChild(li);

        // Add event listener to SVG for any additional functionality
        svg.addEventListener('click', function(e) {
            const index = parseInt(e.target.getAttribute('data-id'));
            arr_username[index] = "";
            ul.removeChild(li);
            realMembers()
        });
    }


    function realMembers() {
        const members_list = [...arr_username.filter(ele => ele.trim().length > 1)].join(",")
        console.log(members_list);
        const real_members = document.querySelector("#real_members")
        real_members.setAttribute("value", members_list);

    }

    document.addEventListener('DOMContentLoaded', function() {
        const inputElement = document.getElementById('members');
        inputElement.addEventListener('keydown', handleKeyPress);

    });
</script>

</html>