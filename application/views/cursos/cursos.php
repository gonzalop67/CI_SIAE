<?php
	if($cursos) {
		foreach ($cursos->result() as $curso) { 
			echo "<ul>
				<li> $curso->nombreCurso </li>
			</ul>";
		} 
	}else {
		echo "<p>Error en la aplicacion</p>";
	}
?>
</body>
</html>