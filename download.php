<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Giggles 下载页面</title>
</head>
<body>
<?php
/**
 * 生成UUID
 * @return string 生成的UUID
 */
function uuid() {
    mt_srand((double)microtime()*10000);
    $charid = strtolower(md5(uniqid(rand(),true)));
    return $charid;
}

// TODO: Safety check
// TODO: Auto-cleaning
if (isset($_GET["go"])) {
    if ($_GET["go"] == "download") {
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
    } else {
        die("下载失败");
    }
}
?>
</body>
</html>
