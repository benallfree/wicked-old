<?
ensure_writable_folder($config['data_fpath']);

observe('attachment_before_delete', 'attachment_attachment_before_delete');
observe('attachment_after_load', 'attachment_attachment_after_load');
