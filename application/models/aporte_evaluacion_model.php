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
    
    function existeNombrePeriodoEvaluacion($nombrePeriodoEvaluacion,$id_periodo_lectivo,$id_institucion) {
        $this->db->select('id_periodo_evaluacion');
        $this->db->from('sw_periodo_evaluacion');
        $this->db->where('pe_nombre', $nombrePeriodoEvaluacion);
        $this->db->where('id_periodo_lectivo', $id_periodo_lectivo);
        $this->db->where('id_institucion', $id_institucion);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }
    
    function crearPeriodoEvaluacion($data){
	$this->db->insert('sw_periodo_evaluacion',
                            array('pe_nombre'=>$data['pe_nombre'],
                                'pe_abreviatura'=>$data['pe_abreviatura'],
                                'pe_principal'=>$data['pe_principal'],
                                'id_periodo_lectivo'=>$data['id_periodo_lectivo'],
                                'id_institucion'=>$data['id_institucion']
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

