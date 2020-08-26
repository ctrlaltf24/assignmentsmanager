<?php
require_once "../template/ui.php";
require_once "../../../staging_resources/connect.php";
require_once "../template/form.php";
echo template_header(true,$logged_in,$is_teacher);
?>
<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--8-col mdl-cell--2-offset">
        <h1>Create a Class</h1>
        <form action="postClass.php" method="post">
            <?php
            echo template_textField("","name","Name");
            echo template_textField("","subject","Subject");
            echo template_options_SQL($conn,"SELECT name FROM subjects","subject","name");
            echo template_textField("","year","Year",true,"value=".date("Y")." name='year'");
            echo template_textField("","period","Period");
            echo template_textField("","day","Day",true,'name="day" pattern="[0-9]*"');
            ?>
            <br>
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">
                Submit
            </button>
        </form>
    </div>
</div>
<?php
echo template_footer();