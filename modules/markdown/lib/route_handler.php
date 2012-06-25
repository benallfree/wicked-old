<?

function handle_markdown_route($fpath)
{
  $s = file_get_contents($fpath);
  return Markdown($s);
}