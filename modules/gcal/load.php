<?

if (! ini_get('date.timezone') )
{
  wicked_error("date.timezone must be set to UTC in php.ini");
}

require_once 'google-api-php-client/src/apiClient.php';
require_once 'google-api-php-client/src/contrib/apiCalendarService.php';
