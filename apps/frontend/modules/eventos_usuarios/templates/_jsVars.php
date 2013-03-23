<? use_helper("I18N") ; ?>
<script type="text/javascript">
// URL
var sf_calendar_url_add = '<?php echo url_for('eventos_usuarios/create') ?>';
var sf_calendar_url_update = '<?php echo url_for('eventos_usuarios/update') ?>';
var sf_calendar_url_add_detail = '<?php echo url_for('eventos_usuarios/addDetail') ?>';
var sf_calendar_url_list = '<?php echo url_for('eventos_usuarios/list') ?>';
var sf_calendar_url_delete = '<?php echo url_for('eventos_usuarios/delete') ?>';
var sf_calendar_url_edit = '<?php echo url_for('eventos_usuarios/edit') ?>';
var sf_calendar_url_new = '<?php echo url_for('eventos_usuarios/new') ?>';
var sf_calendar_url_show = '<?php echo url_for('eventos_usuarios/show') ?>';

// Chaines traduisibles
var sf_calendar_delete = '<?php echo __("Esta seguro de borrar el evento?") ?>';
var sf_calendar_create = '<?php echo __("Crear nuevo evento") ?>';
var sf_calendar_manage = '<?php echo __("Editar evento") ?>';
var sf_calendar_loading = '<?php echo __("Cargando datos...") ?>';
var sf_calendar_success = '<?php echo __("Exito") ?>';
var sf_calendar_request = '<?php echo __("La solicitd esta siendo procesada ...") ?>';
</script>