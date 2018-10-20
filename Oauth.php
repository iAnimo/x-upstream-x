<?php
echo "来到Oauth.php界面";
if (isset($_GET['code'])){
    echo $_GET['code'];
}else{
    echo "NO CODE";
}
?>
