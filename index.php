<?php
require "swinggy.php";

const status_not_logged_in = 1;
const status_logged_in = 2;
$sw = new Swinggy([
        status_init =>
            function(){
                session_start();
                return false;
            },
        status_not_logged_in =>
            function(){
                return !isset($_SESSION["user"]);
            }
]);
$sw->ready();
$sw->set([
   "before" =>
       function($boku){
            if ($boku->stat == status_not_logged_in) {
                header("Location: login.php");
                die();
            }
       }
]);
?>
<?php $sw->go("before") ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Giggles</title>
    </head>

    <body>
        <header>
            <h2>Giggles<small>在线的离线下载工具</small></h2>
        </header>
        <main>
            <a href="login.php">用户中心</a>
            <form action="download.php">
                <label for="url">url: </label><input type="url" id="url" name="url" value="https://www.baidu.com/" /><br />
                <input type="submit" value="go" />
                <input type="reset" value="reset" />
            </form>
        </main>
    </body>
</html>
