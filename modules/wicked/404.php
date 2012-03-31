<? 
header("Status: 404 Not Found");

$s = "Oops, there is nothing here.";
$s = apply_filters('404', $s);
echo $s;