<?php
include "template/ui.php";
include "../resources/connect.php";
echo template_header(true,$logged_in,$is_teacher);
?>

    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--8-col mdl-cell--2-offset">
            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                <div class="mdl-tabs__tab-bar">
                    <a href="#copyright-panel" class="mdl-tabs__tab is-active">Copyright</a>
                    <a href="#privacy-policy-panel" class="mdl-tabs__tab">Privacy Policy/Terms and Conditions</a>
                    <a href="#security-panel" class="mdl-tabs__tab">Security</a>
                </div>
                <div class="mdl-tabs__panel is-active" id="copyright-panel">
                    <h5>Logo</h5>
                    <a href="https://fontlibrary.org/en/font/pepsi-cyr-lat">Font: Pepsi Cyr Lat Made by Dmitry Astakhov. Russia, Penza.</a>
                    <h5>Calendar Popup</h5>
                    <a href="https://github.com/kylestetz/CLNDR">CLNDR</a>
                    <h5>General website template</h5>
                    <a href="https://getmdl.io/">Material Design Lite</a>
                </div>
                <div class="mdl-tabs__panel" id="security-panel">
                    <p>In the development of the site, we used the best practices in terms of security that were know to us at the time. If you are worried about this, simply do not input any sensitive information into answers to questions.</p>
                </div>
                <div class="mdl-tabs__panel" id="privacy-policy-panel">
                    <p>The Software/Service is provided AS IS with no warranty or guarantees.</p>
                    <h5>Overview</h5>
                    <p>Essentially we collect only the information we need for purpose of providing the service, nothing more. What that exactly means is defined below.</p>
                    <br>
                    <h5>Information We Collect From Unregistered Users</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Nothing. Just like it should be :)</span>
                        </li>
                    </ul>
                    <h5>Information We Collect From Registered Students</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">IP address (only in login cache, read more under information we store)</span>
                        </li>
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
                    </ul>
                    <h5>Information We Collect From Registered Teachers</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">IP address (only in login cache, read more under information we store)</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Emails (not passwords)</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Information about the class you input</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">Assignments you create</span>
                        </li>
                    </ul>
                    <h5>Information We Share From Registered Students</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">We share your response to a question, time take and hints taken with solely the teacher you are enrolled under.</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">When logging in with a google account, the Google privacy policy and terms apply, we have no jurisdiction over your google account.</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">There is some communication between our servers and Google for the sole purpose of verifying your identity.</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">No other data is shared with other third parties, just like it should be.</span>
                        </li>
                    </ul>
                    <h5>Information We Store Long Term</h5>
                    <ul class="mdl-list">
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">IP addresses are solely used to validate that you are the one who logged in. They are deleted from record after an hour.</span>
                        </li>
                        <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">All information we collect besides IP addresses.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php

echo template_footer();