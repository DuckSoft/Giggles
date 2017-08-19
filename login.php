<?php
require "swinggy.php";

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
        if ($_GET["logout"] == "yes") {
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
                // not necessary. just for fun
                break;
            case status_log_out:
                // store username for the next step
                $boku->stor["user"] = $_SESSION["user"];
                $_SESSION["user"] = null;
                break;
        }
    },
    "status" => function($boku){
        switch ($boku->stat) {
            case status_not_logged_in:
                echo "You haven't logged in yet!";
                break;
            case status_logged_in:
                echo "Logged in as <b>" . $_SESSION['user'] . "</b>!";
                break;
            case status_log_out:
                echo "User <b>" . $boku->stor["user"] . "</b> have successfully logged out!";
                break;
            default:
                die("there is a bug!");
        }
    },
    "main" => function($boku){
        switch ($boku->stat) {
            case status_not_logged_in:
                echo <<<EOF
<form method="post" action="login.php">
    <label for="user">Username:</label><input id="user" name="user" maxlength="16" /><br />
    <label for="pass">Password:</label><input type="password" id="pass" name="pass" maxlength="32" /><br />
    <input type="submit" value="go" /><input type="reset" value="reset" />
</form>
EOF;
                break;
            case status_logged_in:
                echo <<<EOF
Actions available:
<ul>
    <li><a href="index.php">go downloading</a></li>
    <li><a href="login.php?logout=yes">&lt;&lt;&lt;log out</a></li>
    <li>... to be developed</li>
</ul>
EOF;
                break;
            case status_log_out:
                echo <<<EOF
<a href="login.php">Re-login&gt;&gt;&gt;</a>
EOF;
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
</head>
<body>
    <h2>Login</h2>
    <div id="status"><?php $sw->go("status")?></div>
    <hr/>
    <div id="main"><?php $sw->go("main")?></div>
    <hr/>
    <div id="footer">Powered by <a href="https://github.com/DuckSoft/Swinggy">Swinggy Engine</a>.</div>
</body>
</html>
