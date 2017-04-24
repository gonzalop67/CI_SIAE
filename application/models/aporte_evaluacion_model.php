<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Aporte_evaluacion_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function obtenerAportesEvaluacion($id_periodo_evaluacion) {
        $this->db->where('id_periodo_evaluacion', $id_periodo_evaluacion);
        $this->db->order_by('id_aporte_evaluacion', 'asc');
        $query = $this->db->get('sw_aporte_evaluacion');
        return $query->result();
    }

    function obtenerPeriodoEvaluacion($id) {
        $this->db->where('id_periodo_evaluacion', $id);
        $query = $this->db->get('sw_periodo_evaluacion');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    function existeNombreAporteEvaluacion($nombreAporteEvaluacion,$id_periodo_evaluacion) {
        $this->db->select('id_aporte_evaluacion');
        $this->db->from('sw_aporte_evaluacion');
        $this->db->where('ap_nombre', $nombreAporteEvaluacion);
        $this->db->where('id_periodo_evaluacion', $id_periodo_evaluacion);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }
    
    function crearAporteEvaluacion($data){
	$this->db->insert('sw_aporte_evaluacion',
                            array('ap_nombre'=>$data['ap_nombre'],
                                'ap_abreviatura'=>$data['ap_abreviatura'],
                                'ap_tipo'=>$data['ap_tipo'],
                                'ap_estado'=>$data['ap_estado'],
                                'ap_fecha_inicio'=>$data['ap_fecha_inicio'],
                                'ap_fecha_fin'=>$data['ap_fecha_fin'],
                                'id_periodo_evaluacion'=>$data['id_periodo_evaluacion']
                            )
                        );
    }
    
    function actualizarPeriodoEvaluacion($id, $data){
        $datos = array(
            'pe_nombre'=>$data['pe_nombre'],
            'pe_abreviatura'=>$data['pe_abreviatura']
        );
        $this->db->where('id_periodo_evaluacion',$id);
        $this->db->update('sw_periodo_evaluacion', $datos);
    }
    
    function existeAportesEvaluacion($id){
        $query = $this->db->query("SELECT * FROM sw_aporte_evaluacion WHERE id_periodo_evaluacion = $id");
        return $query->num_rows();
    }
    
    function eliminarPeriodoEvaluacion($id){
        $query = "DELETE FROM sw_periodo_evaluacion WHERE id_periodo_evaluacion = $id";
        $this->db->query($query);
    }
}

