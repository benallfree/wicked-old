<?

$_SESSION['flash'] = isset($_SESSION['flash_next']) ? $_SESSION['flash_next'] : array();
$_SESSION['flash_next'] = array();

$_SESSION['error'] = isset($_SESSION['error_next']) ? $_SESSION['error_next'] : array();
$_SESSION['error_next'] = array();
