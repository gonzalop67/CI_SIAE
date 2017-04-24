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
?>
<!DOCTYPE html>
<html lang="es">
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>SIAE Nuevo Aporte de Evaluación</title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="<?php echo $base_url; ?>css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo $base_url; ?>css/main.css">
        <link rel="stylesheet" href="<?php echo $base_url; ?>css/bootstrap-datetimepicker.css" >
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
        <script src="<?php echo $base_url; ?>js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
        <script src="<?php echo $base_url; ?>js/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>
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
                
                $("#new_aporte_evaluacion").submit(function(event){
                    event.preventDefault();
                    var ap_tipo = $("#cboTipo").val();
                    var ap_fecha_inicio = $("#fecInicio").val();
                    var ap_fecha_fin = $("#fecFin").val();
                    if(ap_tipo==0){
                        $("#mensaje").css("color","red");
                        $("#mensaje").html("Debe elegir un tipo de aporte de evaluación...");
                        $("#cboTipo").focus();
                    }else if(ap_fecha_inicio==''){
                        $("#mensaje").css("color","red");
                        $("#mensaje").html("Debe elegir una fecha de inicio...");
                        $("#fecInicio").focus();
                    }else if(ap_fecha_fin==''){
                        $("#mensaje").css("color","red");
                        $("#mensaje").html("Debe elegir una fecha de fin...");
                        $("#fecFin").focus();
                    }else{
                        $("#img_loader").show();
                        $.post(
                            "<?php echo site_url('aporte_evaluacion/recibirdatos'); ?>",
                            {
                                id_periodo_evaluacion: "<?php echo $id; ?>", 
                                ap_nombre: $("#nombre").val(),
                                ap_abreviatura: $("#abreviatura").val(),
                                ap_tipo: ap_tipo,
                                ap_fecha_inicio: ap_fecha_inicio,
                                ap_fecha_fin: ap_fecha_fin
                            },
                            function(respuesta){
                                var resp = JSON.parse(respuesta);
                                $("#img_loader").hide();
                                alert(resp.mensaje);
                                location.href = "<?php echo site_url('aporte_evaluacion') ?>";
                            }
                        );
                    }
                });
                
                $('.form_date').datetimepicker({
                    language:  'es',
                    weekStart: 1,
                    todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    minView: 2,
                    forceParse: 0
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
                                                    <a href="<?php site_url($menu2->mnu_enlace); ?>">
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
            <h2>ADMINISTRACIÓN DE APORTES DE EVALUACIÓN</h2>
	</div>

        <div class="row text-center color1">
            <h3>INGRESAR UN NUEVO APORTE DE EVALUACIÓN</h3>
        </div>

        <div id="mensaje" class="row text-center">
            <!-- Aqui va el mensaje de eliminacion -->
        </div>

        <!-- Aqui va el formulario de ingreso de un nuevo perfil -->
        <form id="new_aporte_evaluacion" class="form-horizontal form-margin" role="form" method="post" action="">
            <div class="form-group" style="margin-top: 8px">
                <label for="nomPeriodoEvaluacion" class="col-sm-2 control-label">Periodo de Evaluación:</label>
                <div class="col-sm-4">
                    <input class="form-control" id="nomPeriodoEvaluacion" name="nomPeriodoEvaluacion" type="text" value="<?php echo $periodo_evaluacion_nom ?>" disabled>
                </div>    
            </div>
            <div class="form-group" style="margin-top: 8px">
                <label for="nombre" class="col-sm-2 control-label">Nombre:</label>
                <div class="col-sm-10">
                    <input class="form-control" id="nombre" name="nombre" type="text" placeholder="Nombre del Aporte de Evaluación" value="" required autofocus>
                </div>    
            </div>
            <div class="form-group" style="margin-top: 8px">
                <label for="abreviatura" class="col-sm-2 control-label">Abreviatura:</label>
                <div class="col-sm-10">
                    <input class="form-control" id="abreviatura" name="abreviatura" type="text" placeholder="Abreviatura del Aporte de Evaluación" value="" required>
                </div>    
            </div>
            <div class="form-group" style="margin-top: 8px">
                <label for="cboTipo" class="col-sm-2 control-label">Tipo:</label>
                <div class="col-sm-2">
                    <select class="form-control" id="cboTipo" name="cboTipo">
                        <option value="0"> Seleccione... </option> 
                        <option value="1"> PARCIAL </option>
                        <option value="2"> EXAMEN QUIMESTRAL </option>
                        <option value="3"> SUPLETORIO </option>
                    </select>
                </div>    
            </div>
            <div class="form-group" style="margin-top: 8px">
                <label for="fecInicio" class="col-sm-2 control-label">Fecha de Inicio:</label>
                <div class="col-sm-2">
                    <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
                        <input id="fecInicio" class="form-control" type="text" value="" readonly required>
<!--                        <span class="add-on"><i class="icon-remove"></i></span>-->
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                    <input type="hidden" id="dtp_input1" value="" /><br/>
                </div>    
            </div>
            <div class="form-group" style="margin-top: 8px">
                <label for="fecFin" class="col-sm-2 control-label">Fecha de Fin:</label>
                <div class="col-sm-2">
                    <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                        <input id="fecFin" class="form-control" type="text" value="" readonly required>
<!--                        <span class="add-on"><i class="icon-remove"></i></span>-->
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>
                    <input type="hidden" id="dtp_input2" value="" /><br/>
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
            <a class="btn btn-default" href="<?php echo site_url('menu') ?>" role="button">Regresar a la lista</a>
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