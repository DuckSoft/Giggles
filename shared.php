<?php

const navbar_index = 0;
const navbar_login = 1;

function insert_panel($panel_type = "panel-default", $panel_title = "Title", $panel_body = "Body") {
    echo "<div class=\"panel $panel_type\">
<div class=\"panel-heading\">$panel_title</div>
<div class=\"panel-body\">
$panel_body
</div>
</div>";
}


function insert_footer() {
    echo <<<EOF
<hr/>
<small>Copyleft 2017 DuckSoft. Code Powered by <a target="_blank" href="https://github.com/DuckSoft/Swinggy">Swinggy Engine</a>. NO WARRANTY!</small>
EOF;
}

function insert_navbar($active) {
    echo <<<EOF
<div class="navbar navbar-default">
<div class="container">
<div class="navbar-header">
<a class="navbar-brand">Giggles</a>
</div>
<div class="navbar-collapse">
<ul class="nav navbar-nav">
<li
EOF;
    if ($active == navbar_index) echo ' class="active"';
    echo <<<EOF
><a href="index.php">离线下载</a></li>
<li
EOF;
    if ($active == navbar_login) echo ' class="active"';
    echo <<<EOF
><a href="login.php">用户中心</a></li>
<li><a href="https://github.com/DuckSoft/Giggles" target="_blank">GitHub</a></li>
</ul>
</div>
</div>
</div>
EOF;
}
?>