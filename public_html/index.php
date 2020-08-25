<?php
include "template/ui.php";
include "../resources/connect.php";
echo template_header(true,$logged_in,$is_teacher);
/*
    <div class="jumbotron"></div>
    <div class="jumbotronCap" style="background-color: #263238">
        <div class="jumbotronCapText">
            <div class="mdl-typography--display-2 mdl-typography--font-thin">Title</div>
            <p class="mdl-typography--headline mdl-typography--font-thin">
                Description
            </p>
            <a href="BlogPage.php" class="mdl-button mdl-js-button mdl-button--accent mdl-js-ripple-effect" data-upgraded=",MaterialButton,MaterialRipple">
                Read More&nbsp;<i class="material-icons">chevron_right</i>
                <span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span></a>
        </div>
    </div>*/?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--8-col-desktop mdl-cell--12-col-tablet mdl-cell--2-offset-desktop">
            <h2>Welcome to the site!</h2>
            <p>This assignment management system was created for teachers with teachers in mind. It is a work in progress, and strives to be a flexible solution for homework/quiz creation and answer reporting.</p>
            <a href="https://class.assignmentsmanager.com"><h3>Go to the class information page.</h3></a>
            <h5>Features will include:</h5>
            <ul class="mdl-list">
                <li class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">Random numbers in questions</span>
                </li>
                <li class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">Hints</span>
                </li>
                <li class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">Answer collection</span>
                </li>
                <li class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">Answer reporting</span>
                </li>
            </ul>
        </div>
    </div>

<?php

echo template_footer();
