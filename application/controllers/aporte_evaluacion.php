<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Aporte_evaluacion extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('perfil_model');
        $this->load->model('usuario_model');
        $this->load->model('producto_model');
        $this->load->model('institucion_model');
        $this->load->model('periodo_lectivo_model');
        $this->load->model('periodo_evaluacion_model');
        $this->load->model('aporte_evaluacion_model');
    }
    
    public function index() {
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
        $data['periodos_evaluacion'] = $this->periodo_evaluacion_model->obtenerPeriodosEvaluacion($id_periodo_lectivo, $id_institucion);
        $this->load->view('aporte_evaluacion/index_aporte_evaluacion_view', $data);
    }
    
    function obtenerAportesEvaluacion(){
        $id_periodo_evaluacion = $this->input->post('id_periodo_evaluacion');
        $aportes_evaluacion = $this->aporte_evaluacion_model->obtenerAportesEvaluacion($id_periodo_evaluacion);
        echo json_encode($aportes_evaluacion);
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
        $data['id'] = $this->uri->segment(3);
        $data['periodo_evaluacion_nom'] = $this->periodo_evaluacion_model->obtenerPeriodoEvaluacion($data['id'])->pe_nombre;
        $this->load->view('aporte_evaluacion/new_aporte_evaluacion_view', $data);
    }
    
    public function recibirdatos() {
        $data = array(
            'ap_nombre' => $this->input->post('ap_nombre'),
            'ap_abreviatura' => $this->input->post('ap_abreviatura'),
            'ap_tipo' => $this->input->post('ap_tipo'),
            'ap_estado' => 'A',
            'ap_fecha_inicio' => $this->input->post('ap_fecha_inicio'),
            'ap_fecha_fin' => $this->input->post('ap_fecha_fin'),
            'id_periodo_evaluacion' => $this->input->post('id_periodo_evaluacion')
        );
        $existeAporteEvaluacion = $this->aporte_evaluacion_model->existeNombreAporteEvaluacion($data['ap_nombre'],$data['id_periodo_evaluacion']);
        if ($existeAporteEvaluacion) {
            echo json_encode(array("mensaje"=>"Ya existe el aporte de evaluación digitado...","color"=>"red"));
        } else {
            $this->aporte_evaluacion_model->crearAporteEvaluacion($data);
            echo json_encode(array("mensaje"=>"Datos ingresados exitosamente...","color"=>"blue"));
        }
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
        
        $data['id'] = $this->uri->segment(3);
        $data['periodo_evaluacion'] = $this->periodo_evaluacion_model->obtenerPeriodoEvaluacion($data['id']);
        $this->load->view('periodo_evaluacion/editar_periodo_evaluacion_view',$data);
    }
    
    function actualizar(){
        $data = array(
            'pe_nombre' => $this->input->post('nombre'),
            'pe_abreviatura' => $this->input->post('abreviatura')
        );
        $this->periodo_evaluacion_model->actualizarPeriodoEvaluacion($this->uri->segment(3), $data);
        echo json_encode(array("mensaje"=>"Periodo de Evaluación actualizado exitosamente...","color"=>"blue"));
    }
    
    function eliminar(){
        $id = $this->input->post('id_periodo_evaluacion');
        if ($this->periodo_evaluacion_model->existeAportesEvaluacion($id) > 0){
            echo json_encode(array("mensaje"=>"El periodo de evaluación no se puede eliminar porque tiene aportes de evaluación relacionados..."));
        } else {
            $this->periodo_evaluacion_model->eliminarPeriodoEvaluacion($id);
            echo json_encode(array("mensaje"=>"Periodo de Evaluación eliminado exitosamente..."));
        }        
    }
}
