<?php
include "../template/ui.php";
include "../template/form.php";
include "../../resources/connect.php";
echo template_header(true,$logged_in,$is_teacher);
?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--8-col-desktop mdl-cell--12-col-tablet mdl-cell--2-offset-desktop">
            <h2>Feedback</h2>
            <?php if(isset($_POST["feedback"])){
                if(!$conn->query("INSERT INTO `feedback`(`feedback`, `email`,`time`) VALUES (\"".$_POST["feedback"]."\",\"".$user["email"]."\",".time().")")){
                    log_error("failed to insert feedback","",$conn->error);
                }
                ?>
                <p>
                    Thanks for the feedback!
                </p>
                <?php
            } else {?>
            <form action="./feedback.php" method="post">
                <?php
                echo template_textArea("feedback");
                 ?>
                 <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">Submit</button>
            </form>
        <?php } ?>
        </div>
    </div>

<?php

echo template_footer();
