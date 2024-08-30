<?php
$path = isset($GLOBALS["path"]) ? $GLOBALS["path"] : "./";

include $path . '/vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use app\controller\ContactController;
use app\utils\Helper;

$services = [];

$controller = new ContactController();

$res =  json_decode($controller->getAllContacts(), true);

?>
<footer>
    <ul>
        <li>
            <div>
                <img src="<?php echo $path . "assets/images/logo.png"; ?>" alt="HB-Solar">
            </div>
        </li>
        <li>
            <h3>Company</h3>
            <ul>
                <li> <a href="<?php echo $path; ?>">Home</a> </li>
                <li> <a href="<?php echo $path . "services.php"; ?>">Our Services</a> </li>
                <li> <a href="<?php echo $path . "products.php"; ?>">Our Products</a> </li>
                <li> <a href="<?php echo $path . "projects.php"; ?>">Past Projects</a> </li>
            </ul>
        </li>
        <li>
            <h3>Others</h3>
            <ul>
                <li> <a href="<?php echo $path . "about-us.php"; ?>">About Us</a> </li>
                <li> <a href="<?php echo $path . "faqs.php"; ?>">FAQs</a> </li>
                <li> <a href="<?php echo $path . "contact-us.php"; ?>">Contact Us</a> </li>
            </ul>
        </li>
        <li>
            <h3>Contact</h3>
            <ul>
                <li> <a href=""><?php echo Helper::getContactItems('contact_address', $res["data"]); ?></a> </li>
                <li style="display: flex; flex-wrap: wrap;"><?php echo Helper::setPhoneContact('contact_phone', $res["data"]); ?></li>
                <li>
                    <a class="truncate" href="mailto:<?php echo Helper::getContactItems('contact_email', $res["data"]); ?>"><?php echo Helper::getContactItems('contact_email', $res["data"]); ?></a>
                </li>
            </ul>
        </li>
    </ul>
    <div id="bottom">
        <p>
            &copy; <?php echo date("Y"); ?> All Rights Reserved. HomeBest Solar Energy Limited.
        </p>
        <strong>
            Developed By <a href="" target="_blank">ContriveLab</a>
        </strong>
    </div>
</footer>