<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
        $this->load->model('perfil_model');
        $this->load->model('usuario_model');
        $this->load->model('producto_model');
        $this->load->model('institucion_model');
        $this->load->model('periodo_lectivo_model');
    }
    
    function index() {
        $id_usuario = $this->session->userdata('id_usuario');
        $id_perfil = $this->session->userdata('id_perfil');
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        $data['logueado'] = $this->session->userdata('logueado');
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->menu_model->listarMenusNivel1($id_perfil);
        $data['perfiles'] = $this->perfil_model->obtenerPerfiles();
        $this->load->view('menus/index_menu_view.php', $data);
    }
    
    function submenus() {
        $id_usuario = $this->session->userdata('id_usuario');
        $id_perfil = $this->session->userdata('id_perfil');
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        $data['logueado'] = $this->session->userdata('logueado');
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->menu_model->listarMenusNivel1($id_perfil);
        $data['idMenu'] = $this->uri->segment(3);
        $data['text_mnu_padre'] = $this->menu_model->obtenerMenu($data['idMenu'])->mnu_texto;
        $data['idPerfil'] = $this->uri->segment(4);
        $data['nom_perfil_ed'] = $this->perfil_model->obtenerPerfil($data['idPerfil'])->pe_nombre;
        $this->load->view('submenus/index_submenu_view.php', $data);
    }
    
    public function nuevo() {
        $data['logueado'] = $this->session->userdata('logueado');
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $id_usuario = $this->session->userdata('id_usuario');
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        $id_perfil = $this->session->userdata('id_perfil');
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->menu_model->listarMenusNivel1($id_perfil);
        $data['id'] = $this->uri->segment(3);
        $data['nom_perfil_ed'] = $this->perfil_model->obtenerPerfil($data['id'])->pe_nombre;
        $this->load->view('menus/new_menu_view', $data);
    }
    
    public function recibirdatos() {
        $id_perfil = $this->input->post('id_perfil');
        $secuencial = $this->menu_model->obtenerSecuencial($id_perfil,1);
        $data = array(
            'id_perfil' => $this->input->post('id_perfil'),
            'mnu_texto' => $this->input->post('mnu_texto'),
            'mnu_enlace' => $this->input->post('mnu_enlace'),
            'mnu_nivel' => '1',
            'mnu_padre' => '0',
            'mnu_orden' => $secuencial
        );
        $existeMenu = $this->menu_model->existeMenu($data['mnu_texto'],$id_perfil);
        if ($existeMenu) {
            $mensaje = "Ya existe el menú en la base de datos...";
            $color = "red";
        } else {
            $this->menu_model->crearMenu($data);
            $mensaje = "Menú ingresado exitosamente...";
            $color = "blue";
        }
        echo json_encode(array("mensaje"=>$mensaje,"color"=>$color));
    }
    
    function editar(){
        $data['logueado'] = $this->session->userdata('logueado');
        $id_institucion = $this->session->userdata('id_institucion');
        $id_usuario = $this->session->userdata('id_usuario');
        $id_perfil = $this->session->userdata('id_perfil');
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        
        $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
        
        $data['idMenu'] = $this->uri->segment(3);
        $data['menu'] = $this->menu_model->obtenerMenu($data['idMenu']);
        
        $data['idPerfil'] = $this->uri->segment(4);
        $data['nom_perfil_ed'] = $this->perfil_model->obtenerPerfil($data['idPerfil'])->pe_nombre;
        $this->load->view('menus/edit_menu_view',$data);
    }

    public function actualizar() {
        $data = array(
            'id_menu' => $this->input->post('id_menu'),
            'mnu_texto' => $this->input->post('mnu_texto'),
            'mnu_enlace' => $this->input->post('mnu_enlace')
        );
        $this->menu_model->actualizarMenu($data['id_menu'], $data);
        echo json_encode(array("mensaje" => "Menu actualizado exitosamente...", "color" => "blue"));
    }

    public function listarMenusPerfil(){
        $id_perfil = $this->input->post('id_perfil');
        echo json_encode($this->Menu_model->listarMenusNivel1($id_perfil));
    }

    public function listarSubMenusPerfil(){
        $id_menu = $this->input->post('id_menu');
        echo json_encode($this->Menu_model->listarMenusHijos($id_menu));
    }
    
    public function eliminar(){
        $idMenu = $this->input->post('id_menu');
        if ($this->menu_model->existeSubMenus($idMenu) > 0){
            echo json_encode(array("mensaje"=>"El menú no se puede eliminar porque tiene submenús relacionados..."));
        } else {
            $this->menu_model->eliminarMenu($idMenu);
            echo json_encode(array("mensaje"=>"Menú eliminado exitosamente..."));
        }        
    }

    public function nuevoSubmenu() {
        $data['logueado'] = $this->session->userdata('logueado');
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $id_usuario = $this->session->userdata('id_usuario');
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        $id_perfil = $this->session->userdata('id_perfil');
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->menu_model->listarMenusNivel1($id_perfil);
        $data['idMenu'] = $this->uri->segment(3);
        $data['text_mnu_padre'] = $this->menu_model->obtenerMenu($data['idMenu'])->mnu_texto;
        $data['idPerfil'] = $this->uri->segment(4);
        $data['nom_perfil_ed'] = $this->perfil_model->obtenerPerfil($data['idPerfil'])->pe_nombre;
        $this->load->view('submenus/new_submenu_view', $data);
    }
    
    public function nuevo_submenu_post() {
        $id_perfil = $this->input->post('id_perfil');
        $mnu_padre = $this->input->post('mnu_padre');
        $secuencial = $this->menu_model->obtenerSecuencialMenuPadre(2,$id_perfil,$mnu_padre);
        $data = array(
            'id_perfil' => $id_perfil,
            'mnu_texto' => $this->input->post('mnu_texto'),
            'mnu_enlace' => $this->input->post('mnu_enlace'),
            'mnu_nivel' => '2',
            'mnu_padre' => $mnu_padre,
            'mnu_orden' => $secuencial
        );
        $existeMenu = $this->menu_model->existeSubMenu($data['mnu_texto'],$mnu_padre);
        if ($existeMenu) {
            $mensaje = "Ya existe el menú en la base de datos...";
            $color = "red";
        } else {
            $this->menu_model->crearMenu($data);
            $mensaje = "SubMenú ingresado exitosamente...";
            $color = "blue";
        }
        echo json_encode(array("mensaje"=>$mensaje,"color"=>$color));
    }

    public function editarSubmenu() {
        $data['logueado'] = $this->session->userdata('logueado');
        $id_institucion = $this->session->userdata('id_institucion');
        $id_usuario = $this->session->userdata('id_usuario');
        $id_perfil = $this->session->userdata('id_perfil');
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');

        $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;

        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;

        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;

        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);

        $data['idMenu'] = $this->uri->segment(3);
        $data['menu'] = $this->menu_model->obtenerMenu($data['idMenu']);
        
        $data['text_mnu_padre'] = $this->menu_model->obtenerMenu($data['menu']->mnu_padre)->mnu_texto;        

        $data['idPerfil'] = $this->uri->segment(4);
        $data['nom_perfil_ed'] = $this->perfil_model->obtenerPerfil($data['idPerfil'])->pe_nombre;
        $this->load->view('submenus/edit_submenu_view', $data);
    }

    public function contarMenusHijos(){
        $id_menu = $this->input->post('id_menu');
        echo json_encode($this->Menu_model->contarMenusHijos($id_menu));
    }
}
