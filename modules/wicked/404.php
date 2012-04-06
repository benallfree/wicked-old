<? 
header("Status: 404 Not Found");

$s = "Oops, there is nothing here.";
$s = event('404', $s);
echo $s;