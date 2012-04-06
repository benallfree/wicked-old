<?

$res = query_assoc("show variables where variable_name = 'ft_min_word_len'");
if ($res[0]['Value']> $config['ft_min_word_len']) wax_error("mySQL FullText searching error. Set ft_min_word_len >= {$config['ft_min_word_len']}. Currently set to: ". $res[0]['Value']);

$cg = new ArCodeGenerator($config, dirname(__FILE__), CACHE_FPATH."/activerecord");
$md5 = $cg->calc_hash();

clear_cache(CACHE_FPATH."/activerecord");
$cg->generate();

$config['model_info'] = $cg->model_info;
$config['attribute_names'] = $cg->attribute_names;
