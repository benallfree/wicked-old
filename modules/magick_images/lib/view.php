<?

function magick_key($params)
{
  $keys = array_keys($params);
  sort($keys);
  $s = array();
  foreach($keys as $key)
  {
    $s[] = "$key:{$params[$key]}";
  }
  $s = join('|',$s);
  $key = md5($s);
  return folderize($params['path']).'.'.$key;
}


function magick_size($size, $vpath, $params)
{
  global $__wicked;
  $config = $__wicked['modules']['magick_images'];

  $info = pathinfo($vpath);
  $fmt = $info['extension'];
  $params['size'] = $size;
  $params['path'] = substr($vpath,1);

  $src = ROOT_FPATH . $vpath;
  if(!file_exists($src)) wicked_error("$src does not exist for conversion.");
  $info = stat($src);
  $key=$params;
  $key['size'] = $size;
  $key['width'] = is_numeric($size) ? $size : $config['sizes'][$size];
  $key['fsize'] = $info['size'];
  $key['ctime'] = $info['ctime'];
  $key['mtime'] = $info['mtime'];
  $key['fmt'] = $fmt;

  $key = magick_key($key);

  foreach(array('jpg', 'png') as $ext)
  {
    $dst = $config['cache_fpath'] . "/$key.$ext";
    if(file_exists($dst)) 
    {
      $dst = $config['cache_fpath'] ."/$key.$ext";
      $i = new phMagick($dst);
      return $i->getInfo();
    }
  }
  
  wicked_error("Destination file not found $dst from $src");
}

function magick_img_url($size, $vpath, $params=array())
{
  $path = ftov(magick_fpath($size, $vpath, $params));
  return $path;
}

function magick_fpath($size, $vpath, $params=array())
{
  global $__wicked;
  $config = $__wicked['modules']['magick_images'];

  if(!isset($config['sizes'][$size]))
  {
    wicked_error("'$size' is not in \$magic_sizes. Better define it in config.", s_var_export($config['sizes']));
  }

  $info = pathinfo($vpath);
  $fmt = $info['extension'];
  $params['size'] = $size;
  $params['path'] = substr($vpath,1);

  $src = vtof($vpath);
  if(!file_exists($src)) wicked_error("$src does not exist for conversion.");
  $info = stat($src);
  $key=$params;
  $key['size'] = $size;
  $key['width'] = is_numeric($size) ? $size : $config['sizes'][$size];
  $key['fsize'] = $info['size'];
  $key['ctime'] = $info['ctime'];
  $key['mtime'] = $info['mtime'];
  $key['fmt'] = $fmt;
  $key = magick_key($key);

  foreach(array('jpg', 'png') as $ext)
  {
    $dst = $config['cache_fpath'] . "/$key.$ext";
    if(file_exists($dst)) return $config['cache_fpath'] ."/$key.$ext";
  }

  foreach(array('jpg', 'png') as $ext)
  {
    $dst = $config['cache_fpath'] . "/$key.$ext";
    try
    {
      convert($src, $dst, $params);
      $cmp[$ext] = filesize($dst);
    } catch(Exception $e) {
      return '';
    }
  }
  $min = 'jpg';
  foreach(array('jpg','png') as $ext)
  {
    if($cmp[$ext]<$cmp[$min]) $min = $ext;
  }

  foreach(array('jpg', 'png') as $ext)
  {
    if($ext == $min) continue;
    $dst = $config['cache_fpath'] . "/$key.$ext";
    unlink($dst);
  }
  return $config['cache_fpath'] . "/$key.$min";
}

function get_magick_url($o, $k, $size, $params=array())
{
  if (!$o->$k) return '#';
  $vpath = $o->$k->vpath;
  return magick_img_url($size, $vpath, $params);
}


function convert($src, $dst, $params)
{  
  global $__wicked;
  $config = $__wicked['modules']['magick_images'];

  extract($params);
  $i = new phMagick($src, $dst.".png");
  $i->convert();
  $info = $i->getInfo();

  $w = $info[0];
  $h = $info[1];
  if (isset($sw))
  {
    $ssw = $config['sizes'][$ssw];
    $mult = $w/$ssw;
    $sx = max(4,(int)($sx * $mult)-4);
    $sy = max(4,(int)($sy * $mult)-4);
    $sw = (int)($sw * $mult);
    $sh = (int)($sh * $mult);
    $i->crop($sw, $sh, $sy, $sx);
    $w = $sw;
    $h = $sh;
  }
  
  $sz = is_numeric($size) ? $size : $config['sizes'][$size];
  $ratio = min($sz/$w,$sz/$h);

  if(!isset($zc)) $zc = $config['settings']['zc'];
  if($zc)
  {
    $cropped_width = $w * $ratio;
    if($w>$h) // crop width
    {
      $i->crop($h, $h, 0, ($w-$h)/2);
    }
    if($h>$w) // crop height
    {
      $i->crop($w, $w, ($h-$w)/2, 0);
    }
    $i->resize($sz, $sz, true);
  } else {
    $i->resize($ratio * $w, $ratio * $h, true);
  }
  
  if(isset($pixelate) && $pixelate < 1 && $pixelate !==false)
  {
    $prev = $i->getInfo();
    $sz = round($prev[0]*$pixelate);
    $i->resize(max(5,$sz));
    $i->resize($prev[0], $prev[1], true);
  }
  
  if(isset($blur))
  {
    $info = explode('x', $blur);
    $radius = $info[0];
    $sigma = $info[1];
    $i->blur($radius, $sigma);
  }

  if(!isset($rad)) $rad=$config['settings']['rad'];
  if(!isset($bg)) $bg = $config['settings']['bg'];
  if($rad) $i->roundCorners($rad, $bg);
  if(!isset($ds)) $ds=$config['settings']['ds'];
  if($ds!==0 && $ds!==false)
  {
    $i->dropShadow($ds, $bg);
  }
  if(isset($polaroid) && $polaroid) $i->fakePolaroid();
  $i->setDestination($dst);
  $i->convert();
  unlink($dst.".png");
}


function magick_montage_fpath($size, $vpaths, $params=array())
{
  global $__wicked;
  $config = $__wicked['modules']['magick_images'];
  
  $key = $params;
  $fpaths = array();
  foreach($vpaths as $vp)
  {
    $fpath = magick_fpath($size, $vp, $params);
    $key[$vp] = $fpath;
    $fpaths[] = $fpath;
  }
  
  $key['path'] = 'montage';
  
  $key = magick_key($key);
  
  $fpath = $config['cache_fpath']."/{$key}.png";
  
  if(file_exists($fpath)) return $fpath;
  $w = $h = $config['sizes'][$size]+20;
  $cmd = array();
  $cmd[] = "convert -size {$w}x{$h} xc:none -background none -fill white -stroke grey60";
  for($i=count($fpaths)-1;$i>=0;$i--)
  {
    $fp = $fpaths[$i];
    $img = new phMagick($fp);
    $info = $img->getInfo();
    list($w,$h) = $img->getInfo();
    $w+=10;$h+=10;
    $r = rand(-2,2)*5;
    $cmd[] = "$fp -composite -rotate $r -interpolate bicubic";
  }
  $cmd[] = "-trim +repage -background White -flatten";
  $cmd[] = $fpath;
  $cmd = join(" ",$cmd);
  wax_exec($cmd);
  
  convert($fpath, $fpath, array('size'=>$size));
  
  return $fpath;
}

function magick_montage_url($size, $vpaths, $params=array())
{
  return ftov(magick_montage_fpath($size, $vpaths, $params));
}