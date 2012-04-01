<? 
header("Status: 404 Not Found");

$s = "Oops, there is nothing here.";
$s = do_filter('404', $s);
echo $s;