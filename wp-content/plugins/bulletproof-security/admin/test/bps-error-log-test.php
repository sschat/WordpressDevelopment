
<?php 
function disfunction() {
$bpsnofile = 'nofile.php';
if (file_exists($bpsnofile)) {
echo $bpsnofile;
} else {
echo "This file does not really exist";
}}
echo disfunction();

?>

