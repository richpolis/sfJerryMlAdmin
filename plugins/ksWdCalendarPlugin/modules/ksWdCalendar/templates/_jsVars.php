<? use_helper("I18N") ; ?>
<script type="text/javascript">
// URL
var sf_calendar_url_add = '<?php echo url_for('ksWdCalendar/create') ?>';
var sf_calendar_url_update = '<?php echo url_for('ksWdCalendar/update') ?>';
var sf_calendar_url_add_detail = '<?php echo url_for('ksWdCalendar/addDetail') ?>';
var sf_calendar_url_list = '<?php echo url_for('ksWdCalendar/list') ?>';
var sf_calendar_url_delete = '<?php echo url_for('ksWdCalendar/delete') ?>';
var sf_calendar_url_edit = '<?php echo url_for('ksWdCalendar/edit') ?>';

// Chaines traduisibles
var sf_calendar_delete = '<?php echo __("Esta seguro de eliminar el evento?") ?>';
var sf_calendar_create = '<?php echo __("Crear nuevo evento") ?>';
var sf_calendar_manage = '<?php echo __("Editar evento") ?>';
var sf_calendar_loading = '<?php echo __("Loading data...") ?>';
var sf_calendar_success = '<?php echo __("Success") ?>';
var sf_calendar_request = '<?php echo __("La solicitud ha sido procesada ...") ?>';
</script>