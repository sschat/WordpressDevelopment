<?php if ($_SERVER['REMOTE_ADDR'] == '66.77.88.99') { 
phpinfo();
} else {
header("Status: 404 Not Found");
header("HTTP/1.0 404 Not Found");
exit();
}
?>