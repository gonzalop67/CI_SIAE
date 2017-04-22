<?php
	if($curso) {
		echo form_open("/cursos/actualizar/$id");
		$nombre = array(
			'name' => 'nombre',
			'placeholder' => 'Escribe tu nombre',
			'value' => $curso->row()->nombreCurso
		);
		$videos = array(
			'name' => 'videos',
			'placeholder' => 'Cantidad videos del curso',
			'value' => $curso->row()->videosCurso
		);
		echo form_label('Nombre: ','nombre');
		echo form_input($nombre);
		echo "<br>";
	    echo form_label('Número videos: ','videos');
		echo form_input($videos);
		echo "<br>";
	    echo form_submit('','Actualizar Curso');
		echo form_close();
	} else {
		echo "<p>No existe el registro</p>";
	}
?>
</body>
</html>