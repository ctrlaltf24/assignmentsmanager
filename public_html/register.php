<?php
require_once "template/ui.php";
require_once "template/form.php";
require_once "../resources/connect.php";
echo template_header(true, $logged_in, $is_teacher);
$found=false;
if ($result=$conn->query("SELECT * FROM users WHERE email = \"" . $user["email"] . "\"")) {
    while ($row = $result->fetch_assoc()) {
        $found=true;
    }
} else {
    log_error("failed to get users","",$conn->error);
}
if($logged_in&&!$found) {
    ?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--10-col mdl-cell--1-offset">
            <h2>Welcome! Since this is your first time here, we have a couple questions for you.</h2>
            <form action="registerationComplete.php" method="post">
                <?php
                echo template_textField("first_name","first_name", "First Name", true);
                echo template_textField("last_name","last_name", "Last Name", true);
                echo template_textField("class_code","class_code", "Class Code", true);
                ?>
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">
                    Submit
                </button>
            </form>
        </div>
    </div>
    <?php
} else {
    echo "<h2>Only <a href=\"login.php\">logged in</a> users may acsess this page.</h2>";
}
echo template_footer();