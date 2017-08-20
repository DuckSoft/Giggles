<?php
require "swinggy.php";
require "shared.php";

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
<link href="bootstrap.min.css" rel="stylesheet" />
</head>

<body>
<?php insert_navbar(navbar_index)?>

<div class="container">
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading">新建离线下载...</div>
<div class="panel-body">
<form class="form-horizontal" action="download.php">
<fieldset>
<label for="url">下载地址：</label><input class="form-control" type="url" id="url" name="url" placeholder="HTTP/HTTPS/FTP 地址..." /><br/>
<input class="btn btn-default btn-success" type="submit" value="开始下载" />
<input class="btn" type="reset" value="清空" />
</fieldset>
</form>
</div>
</div>
<?php insert_footer() ?>
</div>
</div>
</body>
</html>
