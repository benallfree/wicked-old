<?

function merge_bottom(&$target, $src, $name)
{
	if (is_array($src))
	{
		foreach($src as $k=>$v)
		{
			if (is_array($v))
			{
				merge_bottom($target[$k], $v, $name);
			} else {
				$target[$k][$name] = $v;
			}
		}
	} else {
		$target[$name] = $src;
	}
}
