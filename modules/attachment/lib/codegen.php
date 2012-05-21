<?

function codegen_attachment_before_validate($obj, $event_data, $bt_names, $model_name)
{
  foreach($bt_names as $bt_name)
  {
    $data = "{$bt_name}_data";
    if (is_file_upload($obj->$data))
    {
      associate_attachment($obj, $bt_name);
    }
  }
}


function codegen_attachment_after_new($obj, $event_data, $bt_names, $model_name)
{
  foreach($bt_names as $bt_name)
  {
    $data = "{$bt_name}_data";
    $obj->$data = null;
  }
}

function codegen_get_attachment_thread($obj,$name)
{
  $at = AttachmentThread::find_or_new_by( array(
    'conditions'=>array('object_type = ? and object_id = ? and name = ?', $obj->klass, $obj->id, $name),
    'attributes'=>array(
      'object_type'=>$obj->klass,
      'object_id'=>$obj->id,
      'name'=>$name,
    ),
  ));
  $at->validate();
  if($at->is_valid) $at->save();
  return $at;
}