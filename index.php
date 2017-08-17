<?php
// TODO: Auth-Login
// TODO: Bootstrap beautify
?>
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
            <form action="download.php">
                <input type="hidden" name="go" value="download" /><br />
                <label for="url">url: </label><input type="url" id="url" name="url" value="https://www.baidu.com/" /><br />
                <input type="submit" value="go" />
                <input type="reset" value="reset" />
            </form>
        </main>
    </body>
</html>
