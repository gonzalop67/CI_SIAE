<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('perfil_model');
        $this->load->model('usuario_model');
        $this->load->model('producto_model');
        $this->load->model('institucion_model');
        $this->load->model('periodo_lectivo_model');
        $this->load->library('encrypter');
    }
    
    function index() {
        $id_usuario = $this->session->userdata('id_usuario');
        $id_perfil = $this->session->userdata('id_perfil');
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        $data['logueado'] = $this->session->userdata('logueado');
        $id_institucion = $this->session->userdata('id_institucion');
        $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
        $data['usuarios'] = $this->usuario_model->obtenerUsuarios($id_institucion);
        $this->load->view('usuarios/index_usuario_view.php', $data);
    }
    
    public function nuevo() {
        $data['logueado'] = $this->session->userdata('logueado');
        $id_institucion = $this->session->userdata('id_institucion');
        $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
        $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
        $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
        $id_usuario = $this->session->userdata('id_usuario');
        $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
        $id_perfil = $this->session->userdata('id_perfil');
        $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
        $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
        $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
        $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
        $this->load->view('usuarios/new_usuario_view', $data);
    }

    public function recibirdatos() {
        $data = array(
            'titulo' => $this->input->post('titulo'),
            'login' => $this->input->post('login'),
            'clave' => $this->encrypter->encrypt($this->input->post('clave')),
            'nombre_completo' => $this->input->post('nombre_completo'),
            'perfil' => $this->input->post('perfil'),
            'id_institucion' => $this->session->userdata('id_institucion')
        );
        $existeUsuario = $this->usuario_model->existeUsuario($data['login'], $data['clave'], $data['perfil']);
        if ($existeUsuario) {
            echo json_encode(array("mensaje"=>"Ya existe el usuario en la base de datos...","color"=>"red"));
        } else {
            $this->usuario_model->crearUsuario($data);
            echo json_encode(array("mensaje"=>"Datos ingresados exitosamente...","color"=>"blue"));
        }
    }
    
    public function editar(){
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
        
        $data['id'] = $this->uri->segment(3);
        $data['usuario'] = $this->usuario_model->obtenerUsuario($data['id']);
        $data['clave'] = $this->encrypter->decrypt($data['usuario']->us_password);
        $this->load->view('usuarios/editar_usuario_view',$data);
    }
    
    public function actualizar(){
        $data = array(
            'us_titulo' => $this->input->post('titulo'),
            'us_login' => $this->input->post('login'),
            'us_password' => $this->input->post('clave'),
            'us_fullname' => $this->input->post('nombre_completo')
        );
        $this->usuario_model->actualizarUsuario($this->uri->segment(3), $data);
        echo json_encode(array("mensaje"=>"Usuario actualizado exitosamente...","color"=>"blue"));
    }

    function eliminar(){
        $id = $this->input->post('id_usuario');
//        if ($this->usuario_model->existeAsignaturasUsuario($id) > 0){
//            echo json_encode(array("mensaje"=>"El Usuario no se puede eliminar porque tiene asignaturas relacionadas..."));
//        } else {
            $this->usuario_model->eliminarUsuario($id);
            echo json_encode(array("mensaje"=>"Usuario eliminado exitosamente..."));
//        }        
    }
    
    public function cambiarClave() {
        if ($this->session->userdata('logueado')) {
            $id_usuario = $this->session->userdata('id_usuario');
            $id_perfil = $this->session->userdata('id_perfil');
            $id_periodo_lectivo = $this->session->userdata('id_periodo_lectivo');
            $data['logueado'] = $this->session->userdata('logueado');
            $id_institucion = $this->session->userdata('id_institucion');
            $data['nom_institucion'] = $this->institucion_model->obtenerInstitucion($id_institucion)->in_nombre;
            $data['nom_producto'] = $this->producto_model->obtenerProducto()->pr_nombre;
            $data['desc_producto'] = $this->producto_model->obtenerProducto()->pr_descripcion;
            $data['nom_usuario'] = $this->usuario_model->obtenerUsuario($id_usuario)->us_fullname;
            $data['nom_perfil'] = $this->perfil_model->obtenerPerfil($id_perfil)->pe_nombre;
            $data['nom_periodoLectivo'] = $this->periodo_lectivo_model->obtenerNombrePeriodoLectivo($id_periodo_lectivo);
            $data['listarMenusNivel1'] = $this->Menu_model->listarMenusNivel1($id_perfil);
            $this->load->view('usuarios/cambiar_clave_view.php', $data);
        } else {
            redirect(site_url('usuarios/iniciar_sesion'));
        }
    }
    
    public function cambiar_clave_post() {
        sleep(0.5);
        if ($this->input->post()) {
            $clave_actual = $this->input->post('clave_actual');
            $clave_nueva = $this->input->post('clave_nueva');
            $clave_confirmada = $this->input->post('clave_confirmada');

            $clave_ingresada = $this->encrypter->encrypt($clave_actual);
            
            $id_usuario = $this->session->userdata('id_usuario');
            $clave_bd = $this->usuario_model->obtenerUsuario($id_usuario)->us_password;
            
            if ($clave_ingresada != $clave_bd) {
                echo json_encode(array("mensaje"=>"Clave actual no coincide con la clave del usuario. Reintente nuevamente.", "color"=>"red"));
            } else if ($clave_nueva != $clave_confirmada) {
                echo json_encode(array("mensaje"=>"Clave nueva y redigitada no coinciden. Reintente nuevamente.", "color"=>"red"));
            } else {
                    $id_usuario = $this->session->userdata('id_usuario');
                    $this->usuario_model->actualizarClave($id_usuario, $clave_nueva);
                    echo json_encode(array("mensaje"=>"Clave actualizada exitosamente.", "color"=>"blue"));
            }
        } else {
            $this->cambiarClave();
        }
    }

}
