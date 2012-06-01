<?


function fetch($url, $headers = array(), $http_user='', $http_pass='',$use_cache=true)
{
  $keys = array_merge(array($url, $http_user, $http_pass), array_values($headers));
  $md5 = md5(join('|',$keys));
  $fname = "cache/$md5";
  if($use_cache && file_exists($fname)) return json_decode(file_get_contents($fname),true);
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_VERBOSE, true); // Display communication with server
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return data instead of display to std out
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  if($http_user)
  {
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
    curl_setopt($ch, CURLOPT_USERPWD, "{$http_user}:{$http_pass}");
  }                     
  $data = curl_exec($ch);
  $error = curl_error($ch);
  $info = curl_getinfo($ch);
  curl_close($ch);
  $res = array($data, $error, $info);
  file_put_contents($fname,json_encode($res));
  return $res;
}   
