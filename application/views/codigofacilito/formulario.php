<?php echo form_open("/codigofacilito/recibirdatos") ?>
<?php
	$nombre = array(
		'name' => 'nombre',
		'placeholder' => 'Escribe tu nombre'
	);
	$videos = array(
		'name' => 'videos',
		'placeholder' => 'Cantidad videos del curso'
	);
?>
<?php echo form_label('Nombre: ','nombre') ?>
<?php echo form_input($nombre) ?><br>
<?php echo form_label('Número videos: ','videos') ?>
<?php echo form_input($videos) ?><br>
<?php echo form_submit('','Subir Curso') ?>
<?php echo form_close() ?>
</body>
</html>