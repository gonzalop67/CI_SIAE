<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>SIAE Login</title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/main.css">

        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
        <script src="js/vendor/bootstrap.js"></script>
        <script src="js/main.js"></script>
        <script src="js/fecha_actual.js"></script>

        <script type="text/javascript">
            $(document).on("ready", function () {
                var fecha = $("#fecha_actual");
                fecha_actual(fecha);
                var f = new Date();
                var cadena = "Aplicación Web construida utilizando los siguientes frameworks y tecnologías:<br/>" +
                        "Bootstrap, jQuery, CodeIgniter y AJAX<br/>" +
                        ".: &copy; " + f.getFullYear() + " - UNIDAD EDUCATIVA PCEI FISCAL SALAMANCA :.";
                $("#pie").html(cadena);
            });
        </script>
    </head>
    <body>

        <header>
            <div class="container colorfuente">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8">
                        <h1>S I A E</h1>
                        <h6>Sistema Integrado de Administraci&oacute;n Estudiantil</h6>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 div-right">
                        <div id="fecha_actual" class="text-center">
                            <!-- Aquí va la fecha actual del sistema -->
                        </div>
                        
                        <div class="container">    
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center padding-div">
                                    <a href="<?php echo site_url('login'); ?>" class="btn btn-success btn-sm btn-block" role="button">
                                        Acceder al Sistema...
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-fluid margin-padding text-center color-fondo-error">
            <div class="row">
                <div id="mensaje-error" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!--Aqui va el mensaje de error de validacion de formulario-->
                </div>
            </div>
        </div>

        <div id="jumbotron" class="container">
            <div class="row">
                <div class="jumbotron hero-spacer">
                    <p class="parrafo">El Sistema Integrado de Administración Estudiantil (SIAE) almacena y maneja información estudiantil para el sistema educativo de nuestro país (Ecuador).</p>
                    <p class="parrafo">El SIAE es una sofisticada herramienta que integra en un solo paquete la administración y consulta de calificaciones estudiantiles.</p>
                </div>
            </div>
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