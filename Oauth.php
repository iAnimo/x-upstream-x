<?php
echo "来到Oauth.php界面";
if (isset($_GET['code'])){
    echo "code:".$_GET['code']."<br>";
    echo "state:".$_GET["state"];
}else {
    echo "no code";
}
