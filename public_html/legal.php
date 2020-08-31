<?php
include "template/ui.php";
include "../resources/connect.php";
echo template_header(true,$logged_in,$is_teacher,$user["email"]);
?>

    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--8-col mdl-cell--2-offset">
            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                <div class="mdl-tabs__tab-bar">
                    <a href="#copyright-panel" class="mdl-tabs__tab is-active">Copyright</a>
                    <a href="#privacy-policy-panel" class="mdl-tabs__tab">Privacy Policy/Terms and Conditions</a>
                </div>
                <div class="mdl-tabs__panel is-active" id="copyright-panel">
                    <h5>Logo</h5>
                    <a href="https://fontlibrary.org/en/font/pepsi-cyr-lat">Font: Pepsi Cyr Lat Made by Dmitry Astakhov. Russia, Penza.</a>
                    <h5>Calendar Popup</h5>
                    <a href="https://github.com/kylestetz/CLNDR">CLNDR</a>
                    <h5>BBCode Parsing</h5>
                    <a href="https://github.com/jbowens/jBBCode">JBBCode</a>
                    <h5>Math Rendering</h5>
                    <a href="https://github.com/mathjax/MathJax">MathJax</a>
                    <h5>LaTeX Editor</h5>
                    <a href="https://sourceforge.net/projects/visualasciimath/">Visual Math Editor</a>
                    <h5>BBCode Editor</h5>
                    <a href="https://github.com/samclarke/SCEditor">sceditor</a>
                    <h5>General website template</h5>
                    <a href="https://getmdl.io/">Material Design Lite</a>
                </div>
                <div class="mdl-tabs__panel" id="security-panel">
                    <p>In the development of the site, we made an effort to store as little data as needed to provide the service, and secure the website . </p>
                </div>
                <div class="mdl-tabs__panel" id="privacy-policy-panel">
                    <p>The Software and The Service are provided AS IS with no warranty or guarantees.</p>
                    <h5>Overview</h5>
                    <p>Essentially we collect only the information we need for purpose of providing the service, nothing more. We collect and store all the information you voluntarily put into the various forms. DO NOT PUT SENSITIVE INFORMATION IN THE FORMS. We don't need to know. What exactly we collect is below.</p>
                    <br>
                    <h5>Information We Collect From Unregistered Users</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Nothing</span>
                        </li>
                    </ul>
                    <h5>Information We Collect From Registered Students</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Names for teacher to identify you</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Emails (not passwords)</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Answers to questions</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Hints used in questions</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Time taken in questions</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Any other information submitted in forms</span>
                        </li>
                    </ul>
                    <h5>Information We Collect From Registered Teachers</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Emails (not passwords)</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Information about the class you input</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Assignments you create</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Any other information submitted in forms</span>
                        </li>
                    </ul>
                    <h5>Information We Share From Registered Students To Their Teachers</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">We share your response to a question, time take and hints taken with solely the teacher you are enrolled under.</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Your name and email are shared with the teacher as well so they can identify you</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">General rule of thumb is if you are doing work in their class, they can see that.</span>
                        </li>
                    </ul>
                    <h5>Third Parties</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">When logging in with a google account, the Google privacy policy and terms apply, we have no jurisdiction over your google account.</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">No other data is intentionally shared with third parties</span>
                        </li>
                    </ul>
                    <h5>Conduct</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Do not attempt to gain access to others data.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php

echo template_footer();