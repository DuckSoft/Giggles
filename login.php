<?php
require "swinggy.php";
require "shared.php";

const status_not_logged_in = 1;
const status_logged_in = 2;
const status_login_validation = 4;
const status_log_out = 5;
$sw = new Swinggy([
    status_init => function(){
        session_start();
        return false;
    },
    status_log_out => function(){
        if (!empty($_GET["logout"]) and !empty($_SESSION["user"])) {
            return true;
        } else {
            return false;
        }
    },
    status_login_validation => function(){
        if (!empty($_POST["user"]) || !empty($_POST["pass"])) {
            return true;
        } else {
            return false;
        }
    },
    status_not_logged_in => function(){
        return !isset($_SESSION["user"]);
    },
    status_logged_in => function(){
        return true;
    }
]);

$sw->ready();
$sw->set([
    "before" => function($boku){
        switch ($boku->stat) {
            case status_login_validation:
                // TODO: implement of real auth login
                if ($_POST["user"] == "ducksoft" && $_POST["pass"]=="ducksoft") {
                    $_SESSION["user"] = "ducksoft";
                    header("Location: login.php");
                    die();
                }
                break;
            case status_log_out:
                // store username for the next step
                $boku->stor["user"] = $_SESSION["user"];
                $_SESSION["user"] = null;
                break;
        }
    },
    "status" => function($boku){
        echo "<div class=\"alert alert-";
        switch ($boku->stat) {
            case status_not_logged_in:
                echo "info\">要使用本服务，请先登录！";
                break;
            case status_logged_in:
                echo "success\">欢迎用户<b>" . $_SESSION['user'] . "</b>！";
                break;
            case status_log_out:
                echo "success\">您已成功登出，<b>" . $boku->stor["user"] . "</b>！";
                break;
            case status_login_validation:
                // if executed here, you are not permitted
                echo "danger\">您输入的登录信息未能通过验证！";
        }
        echo "</div>";
    },
    "main" => function($boku){
        switch ($boku->stat) {
            case status_not_logged_in:
            case status_login_validation:
            case status_log_out:
                insert_panel("panel-primary","用户登录",<<<EOF
<div class="container-fluid">
<div class="row">
<form class="form-horizontal" method="post" action="login.php">
<label class="form-control-static" for="user">用户名:</label>
<input class="form-control" id="user" name="user" maxlength="16" placeholder="username" /><br />
<label class="form-control-static" for="pass">密码:</label>
<input class="form-control" type="password" id="pass" name="pass" maxlength="32" placeholder="password" /><br />
<input class="btn btn-success" type="submit" value="go" />
<input class="btn btn-default" type="reset" value="reset" />
</form>
</div>
</div>
EOF
                );
                break;
            case status_logged_in:
                insert_panel("panel-default","用户操作",<<<EOF
<ul class="nav nav-pills nav-stacked">
<li><a href="index.php">转到下载页面&gt;&gt;&gt;</a></li>
<li><a href="login.php?logout=yes">&lt;&lt;&lt;安全登出</a></li>
<li></li>
</ul>
EOF
                );
                break;
        }
    }
]);
?>
<?php $sw->go("before")?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Giggles Login</title>
<link href="bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php insert_navbar(navbar_login)?>
<div class="container">
<div class="row">
<?php $sw->go("status")?>
<?php $sw->go("main")?>
<?php insert_footer() ?>
</div>
</div>
</body>
</html>
