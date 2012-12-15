<table width="100%" >
<tr>
<td width="75%">
<?php echo $evento; ?>
</td>
<td width="25%">
<input type="button" value="Editar Evento" id="buttonEditarEvento" onclick="$.editarEvento('<?php echo $evento->getId() ?>');" />
</td>
</tr>
</table>
<script>
    $(document).ready(function(){
       $("input:submit, input:button").button();
   });
</script>   