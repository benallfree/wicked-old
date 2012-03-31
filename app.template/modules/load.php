<?

/*
Lock into the render_widgets action and render a widget
*/
observe('render_widgets', 'common_render_widgets');

/*
This is the callback. This widget will always be rendered.
*/
function common_render_widgets()
{
  require(dirname(__FILE__)."/templates/facebook_widget.php");
}
