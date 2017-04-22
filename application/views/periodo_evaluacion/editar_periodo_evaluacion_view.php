<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    if(!isset($logueado) OR !$logueado) {
        redirect(site_url('home'));
    }
    if (!isset($nom_institucion) OR ! $nom_institucion) {
        $nom_institucion = "No está definido";
    }
    if (!isset($nom_producto) OR ! $nom_producto) {
        $nom_producto = "No está definido";
    }
    if (!isset($desc_producto) OR ! $desc_producto) {
        $desc_producto = "No está definido";
    }
    if (!isset($nom_usuario) OR ! $nom_usuario) {
        $nom_usuario = "No está definido";
    }
    if (!isset($nom_perfil) OR ! $nom_perfil) {
        $nom_perfil = "No está definido";
    }
    if (!isset($nom_periodoLectivo) OR ! $nom_periodoLectivo) {
        $nom_periodoLectivo = "No está definido";
    }
    if (!isset($color)) {
        $color = "red";
    }
    $base_url = base_url();
    switch ($periodo_evaluacion->pe_principal) {
        case 1: 
            $tipo_periodo = "QUIMESTRE";            
            break;
        case 2:
            $tipo_periodo = "SUPLETORIO";
            break;
        case 3:
            $tipo_periodo = "REMEDIAL";
            break;
        case 4:
            $tipo_periodo = "DE GRACIA";
            break;
    }
?>
<!DOCTYPE html>
<html lang="es">
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>SIAE Editar Periodo de Evaluación</title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="<?php echo $base_url; ?>css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo $base_url; ?>css/main.css">
        <style>
            .color1 {
                background-color: #ccc;
            }
            th {
                text-align: center;
            }
        </style>
        <link rel="icon" href="<?php echo $base_url; ?>favicon.ico" />
        <script src="<?php echo $base_url; ?>js/vendor/jquery-1.10.1.min.js"></script>
        <script src="<?php echo $base_url; ?>js/vendor/bootstrap.min.js"></script>
        <script src="<?php echo $base_url; ?>js/main.js"></script>
        <script src="<?php echo $base_url; ?>js/fecha_actual.js"></script>
        <script src="<?php echo $base_url; ?>js/funciones.js"></script>
        
        <script type="text/javascript">
            $(document).on("ready", function () {
                var fecha = $("#fecha_actual");
                fecha_actual(fecha);
                var f = new Date();
                var cadena = "Aplicación Web construida utilizando los siguientes frameworks y tecnologías:<br/>" +
                        "Bootstrap, jQuery, CodeIgniter y AJAX<br/>" +
                        ".: &copy; " + f.getFullYear() + " - UNIDAD EDUCATIVA PCEI FISCAL SALAMANCA :.";
                $("#pie").html(cadena);
                
                $("#edit_periodo_evaluacion").submit(function(event){
                    event.preventDefault();
                    var pe_tipo = $("#cboTipo").val();
                    if(pe_tipo==0){
                        $("#mensaje").css("color","red");
                        $("#mensaje").html("Debe elegir un tipo de evaluación.");
                        $("#cboTipo").focus();
                    }else{
                        $("#img_loader").show();
                        $.post(
                            "<?php echo site_url('periodo_evaluacion/actualizar/'.$id); ?>",
                            {
                                nombre: $("#nombre").val(),
                                abreviatura: $("#abreviatura").val()
                            },
                            function(respuesta){
                                var resp = JSON.parse(respuesta);
                                $("#img_loader").hide();
                                $("#mensaje-error").css('color',resp['color']);
                                $("#mensaje-error").html(resp['mensaje']);
                            }
                        );
                    }
                });
            });
        </script>
    </head>
    
    <body>
    
        <header>
            <div class="container colorfuente">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8">
                        <h2><?php echo $nom_producto ?></h2>
                        <h5><?php echo $nom_institucion . " (" . $nom_periodoLectivo . ")" . "<br/>"; ?></h5>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 div-right">
                        <div id="fecha_actual" class="text-center">
                            <!-- Aquí va la fecha actual del sistema -->
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                    <?php echo $nom_usuario ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                    <?php echo $nom_perfil ?>
                                </div>
                            </div>
                        </div>
                        <div class="container">    
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center padding-div">
                                    <a href="<?php echo site_url('login/cerrar_sesion') ?>" class="btn btn-success btn-sm btn-block" role="button">
                                        Salir del Sistema
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div id="wrapper">
            <nav class="navbar navbar-static-top navbar-default" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <ul class="nav navbar-nav">
                            <?php foreach($listarMenusNivel1 as $menu1) { ?>
                                <?php if(count($this->Menu_model->listarMenusHijos($menu1->id_menu)) > 0) { ?>
                                    <li class="dropdown">
                                        <a href="<?php echo $menu1->mnu_enlace; ?>" class="dropdown-toggle" data-toggle="dropdown">
                                            <?php echo $menu1->mnu_texto; ?> <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php foreach($this->Menu_model->listarMenusHijos($menu1->id_menu) as $menu2) { ?>
                                                <li>
                                                    <a href="<?php echo $menu2->mnu_enlace; ?>">
                                                        <?php echo $menu2->mnu_texto; ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } else { ?>
                                    <li>
                                        <a href="<?php echo site_url($menu1->mnu_enlace); ?>">
                                            <?php echo $menu1->mnu_texto; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        
        <div class="row text-center color1">
            <h2>ADMINISTRACIÓN DE PERIODOS DE EVALUACIÓN</h2>
	</div>

        <div class="row text-center color1">
            <h3>EDITAR UN PERIODO DE EVALUACIÓN</h3>
        </div>

        <div id="mensaje" class="row text-center">
            <!-- Aqui va el mensaje de eliminacion -->
        </div>

        <!-- Aqui va el formulario de ingreso de un nuevo perfil -->
        <form id="edit_periodo_evaluacion" class="form-horizontal form-margin" role="form" method="post" action="">
            <div class="form-group" style="margin-top: 8px">
                <label for="nombre" class="col-sm-2 control-label">Nombre:</label>
                <div class="col-sm-10">
                    <input class="form-control" id="nombre" name="nombre" type="text" placeholder="Nombre del Periodo de Evaluación" 
                           value="<?php echo $periodo_evaluacion->pe_nombre ?>" required autofocus>
                </div>    
            </div>
            <div class="form-group" style="margin-top: 8px">
                <label for="abreviatura" class="col-sm-2 control-label">Abreviatura:</label>
                <div class="col-sm-10">
                    <input class="form-control" id="abreviatura" name="abreviatura" type="text" placeholder="Abreviatura del Periodo de Evaluación" 
                           value="<?php echo $periodo_evaluacion->pe_abreviatura ?>" required autofocus>
                </div>
            </div>
            <div class="form-group" style="margin-top: 8px">
                <label for="tipo" class="col-sm-2 control-label">Tipo:</label>
                <div class="col-sm-4">
                    <input class="form-control" id="tipo" name="tipo" type="text" value="<?php echo $tipo_periodo ?>" disabled>
                </div>    
            </div>
            <div class="form-group">
              <div class="col-sm-10 col-sm-offset-2">
                 <input type="submit" name="submit" id="submit" value="Enviar" class="btn btn-primary">
              </div>
            </div>
        </form>

        <div class="container-fluid margin-padding text-center">
            <div class="row">
                <div id="mensaje-error" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="color: <?php echo $color; ?>">
                    <!--Aqui va el mensaje de error de validacion de formulario-->
                </div>
                <div id="img_loader" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-oculto">
                    <!--Aqui va el gif de "procesando..." el formulario-->
                    <img src="<?php echo $base_url; ?>img/ajax-loader.gif" alt="Procesando...">
                </div>
            </div>
        </div>
        
        <div class="text-center" style="margin-bottom: 8px;">
            <a class="btn btn-default" href="<?php echo site_url('periodo_evaluacion') ?>" role="button">Regresar a la lista</a>
        </div>
        
        <footer class="color-footer text-center colorfuente">
            <div id="pie" class="container">
                <!-- Aqui va el mensaje del pie de página -->
            </div>
        </footer>
        
        <!-- Metis Menu Plugin JavaScript -->
        <script src="<?php echo $base_url; ?>metisMenu/dist/metisMenu.min.js"></script>
    
    </body>
    
</html>