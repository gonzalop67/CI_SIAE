<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($nom_institucion) OR ! $nom_institucion) {
    redirect(site_url("home"));
}
if (!$nom_producto) {
    $nom_producto = "No está definido";
}
if (!$desc_producto) {
    $desc_producto = "No está definido";
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>SIAE Login Error</title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/signin.css">

        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
        <script src="js/vendor/bootstrap.js"></script>
        <script src="js/main.js"></script>
        <script src="js/fecha_actual.js"></script>
        <script src="js/funciones.js"></script>

        <script type="text/javascript">
            $(document).on("ready", function () {
                var fecha = $("#fecha_actual");
                fecha_actual(fecha);
                var f = new Date();
                var cadena = "Aplicación Web construida utilizando los siguientes frameworks y tecnologías:<br/>" +
                        "Bootstrap, jQuery, CodeIgniter y AJAX<br/>" +
                        ".: &copy; " + f.getFullYear() + " - UNIDAD EDUCATIVA PCEI FISCAL SALAMANCA :.";
                $("#pie").html(cadena);
        </script>
    </head>
    <body>

        <header>
            <div class="container colorfuente">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8">
                        <h1><?php echo $nom_producto; ?></h1>
                        <h5><?php echo $desc_producto; ?></h5>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 div-right">
                        <div id="fecha_actual" class="text-center">
                            <!-- Aquí va la fecha actual del sistema -->
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                    <?php echo "<br>".$nom_institucion; ?>
                                </div>
                            </div>
                        </div>
                        <div class="container">    
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center padding-div">
                                    <a href="<?php echo site_url('home'); ?>" class="btn btn-success btn-sm btn-block" role="button">
                                        Ir a la página de inicio...
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div id="login-form" class="form-signin">
            <h4 class="form-signin-heading"><?php echo $error; ?></h4>
            <button id="btnRegresar" class="btn btn-lg btn-primary btn-block" onclick="<?php echo site_url('login.php'); ?>">Regresar</button>
        </div>

        <div class="container">
            <section>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="row">
                            <div class="hidden-xs col-sm-3 col-md-6 col-lg-6">
                                <br><br><img src="img/profesional.png" class="img-responsive">
                            </div>
                            <div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
                                <h3>Matriculación</h3><hr>
                                <p align="left">A través del perfil de Secretaría, 
                                    el personal de este departamento puede matricular estudiantes nuevos y 
                                    antiguos en el SIAE.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="row">
                            <div class="hidden-xs col-sm-3 col-md-6 col-lg-6">
                                <br><br><img src="img/estudiantes.png" class="img-responsive">
                            </div>
                            <div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
                                <h3>Estudiantes</h3><hr>
                                <p align="left">A través del perfil Estudiante, el dicente puede consultar 
                                    sus calificaciones que fueron registradas por los docentes en el SIAE.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="row">
                            <div class="hidden-xs col-sm-3 col-md-6 col-lg-6">
                                <br><br><img src="img/edificio.png" class="img-responsive">
                            </div>
                            <div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
                                <h3>Docentes</h3><hr>
                                <p align="left">Mediante el rol de docente, cada profesor puede gestionar sus 
                                    calificaciones utilizando una interfaz amigable.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="color-footer text-center colorfuente">
            <div id="pie" class="container">
                <!-- Aqui va el mensaje del pie de página -->
            </div>
        </footer>

    </body>
</html>