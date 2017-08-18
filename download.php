<?php
require "swinggy.php";

const status_not_logged_in = 1;
const status_url_error = 2;
const status_ok = 666;
$sw = new Swinggy([
        status_init => function(){
                session_start();
                return false;
        },
        status_not_logged_in => function(){
                return !isset($_SESSION["user"]);
        },
        status_url_error => function(){
            if (!isset($_GET["url"])) {
                return true;
            }

            function validateURL($URL) {
                $pattern_1 = "/^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i";
                $pattern_2 = "/^(www)((\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i";
                if(preg_match($pattern_1, $URL) || preg_match($pattern_2, $URL)){
                    return true;
                } else{
                    return false;
                }
            }

            if (!validateURL($_GET["url"])) {
                return true;
            }
            return false;
        },
        status_ok => function(){
                return true;
        }
]);
$sw->ready();
$sw->set([
        "before" => function($boku){
            switch ($boku->stat) {
                case status_not_logged_in:
                    header("Location: login.php");
                    die();
                    break;
                case status_url_error:
                    // do nothing?
            }
        },
        "work" => function($boku) {
            if ($boku->stat != status_ok) {
                die("something is wrong");
            } else {
                function uuid() {
                    mt_srand((double)microtime()*10000);
                    $charid = strtolower(md5(uniqid(rand(),true)));
                    return $charid;
                }

                $output_dir = getcwd() . "/downloads/";
                $output_file = uuid();
                $output_path = $output_dir . $output_file;

                print '<textarea style="width: 100%; height: 80%">';

                $pipe = popen("wget --no-check-certificate -O \"" . $output_path . "\" \"". $_GET["url"] . "\" 2>&1","r");
                while (!feof($pipe)) {
                    print fread($pipe, 1024);
                    flush();
                }
                pclose($pipe);

                echo '</textarea>';
                echo "文件下载成功：";
                print "<a href=\"./downloads/".$output_file ."\">点我取回</a>";
            }
        }
]);
?>
<?php $sw->go("before") ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Giggles 下载页面</title>
</head>
<body>
<?php $sw->go("work") ?>
</body>
</html>
