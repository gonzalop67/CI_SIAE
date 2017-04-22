<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    if(!isset($logueado) OR !$logueado) {
        redirect(site_url('home'));
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
        <title>SIAE Menús</title>
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
                
                cargarSubmenus();
                
                $("#cboPerfiles").change(function(event){
                    event.preventDefault();
                });                
            });

            function cargarSubmenus(){
                var id_perfil = <?php echo $idPerfil ?>;
                var id_menu = <?php echo $idMenu ?>;
                $("#tblMenus tbody").html("");
                if(id_perfil=='0'){
                    $("#btnNuevoSubMenu").hide();
                }else{
                    $.post(
                        "<?php echo site_url('menu/listarSubMenusPerfil') ?>",
                        {
                            id_perfil: id_perfil,
                            id_menu: id_menu
                        },
                        function(respuesta){
                            var obj = JSON.parse(respuesta);

                            $.each(obj,function(i,item){
                                $("#tblMenus tbody").append(
                                    '<tr>'+
                                        '<td>'+item.id_menu+'</td>'+
                                        '<td>'+item.mnu_texto+'</td>'+
                                        '<td><a href="'+'<?php echo $base_url ?>menu/editarSubmenu/'+item.id_menu+'/'+id_perfil+'">Editar</a></td>'+
                                        '<td><a href="#" onclick="eliminar('+item.id_menu+')">Eliminar</a></td>'+
                                    '</tr>'
                                );
                            });
                            $("#btnNuevoSubMenu").show();
                        }
                    );
                }
            }

            function nuevoSubMenu(){
                var id_perfil = "<?php echo $idPerfil ?>";
                var id_menu = "<?php echo $idMenu ?>";
                location.href = "<?php echo site_url('menu/nuevoSubmenu/') ?>"+id_menu+"/"+id_perfil;
            }

            function eliminar(id_menu) {
                if(confirm("¿Seguro que desea eliminar este submenú?")){
                    $("#img_loader").show();
                    console.log(id_menu);
                    $.post(
                        "<?php echo site_url('menu/eliminarSubmenu') ?>",
                        {
                            id_menu: id_menu
                        },
                        function(respuesta){
                            var resp = JSON.parse(respuesta);
                            alert(resp['mensaje']);
                            location.href = "<?php echo site_url('menu') ?>";
                        }
                    );
                }
            }
        </script>
    </head>
    
    <body>
    
        <header>
            <div class="container colorfuente">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8">
                        <h2><?php echo $nom_producto ?></h2>
                        <h5><?php echo "PANEL DE ADMINISTRACIÓN (" . $nom_periodoLectivo . ")" . "<br/>"; ?></h5>
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
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <?php echo $menu1->mnu_texto; ?> <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php foreach($this->Menu_model->listarMenusHijos($menu1->id_menu) as $menu2) { ?>
                                                <li>
                                                    <a href="<?php echo $base_url . $menu2->mnu_enlace; ?>">
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
            <h2>ADMINISTRACIÓN DE SUBMENÚS</h2>
	</div>

        <div class="row text-center color1">
            <h3>LISTA DE SUBMENÚS EXISTENTES</h3>
        </div>

        <form class="form-horizontal form-margin" role="form" method="post" action="">
            <div class="form-group" style="margin-top: 8px">
                <label for="nomPerfil" class="col-sm-6 control-label">Perfil:</label>
                <div class="col-sm-4">
                    <input class="form-control" id="nomPerfil" name="nomPerfil" type="text" value="<?php echo $nom_perfil_ed ?>" disabled>
                </div>    
            </div>
            <div class="form-group" style="margin-top: 8px">
                <label for="texto" class="col-sm-6 control-label">Menú Padre:</label>
                <div class="col-sm-4">
                    <input class="form-control" id="texto" name="texto" type="text" value="<?php echo $text_mnu_padre ?>" disabled>
                </div>    
            </div>
        </form>
        
        <div class="container-fluid margin-padding text-center">
            <div class="row">
                <div id="img_loader" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 div-oculto">
                    <!--Aqui va el gif de "procesando..." el formulario-->
                    <img src="<?php echo $base_url; ?>img/ajax-loader.gif" alt="Procesando...">
                </div>
            </div>
        </div>
        
        <div id="listado" class="table-responsive text-center">
            <table id="tblMenus" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th colspan="2">Acciones</th> 
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se van a listar los menúes por perfiles utilizando AJAX -->
                </tbody>
            </table>
        </div>

        <div id="btnNuevoSubMenu" class="text-center" style="margin-bottom: 8px;">
            <a class="btn btn-default" href="#" onclick="nuevoSubMenu()" role="button">NUEVO SUBMENÚ</a>
        </div>
        
        <div id="btnNuevoMenu" class="text-center" style="margin-bottom: 8px;">
            <a class="btn btn-default" href="<?php echo $base_url . "/menu" ?>" role="button">REGRESAR A LA LISTA DE MENÚS</a>
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
