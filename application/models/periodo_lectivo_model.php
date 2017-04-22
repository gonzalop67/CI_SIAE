<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Periodo_lectivo_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function obtenerPeriodosLectivos() {
        $this->db->order_by('pe_anio_inicio','desc');
        $query = $this->db->get('sw_periodo_lectivo');
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }
    
    function obtenerPeriodoLectivo($id) {
        $this->db->where('id_periodo_lectivo', $id);
        $query = $this->db->get('sw_periodo_lectivo');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function obtenerNombrePeriodoLectivo($id) {
        //Obtiene los datos de un periodo lectivo determinado
        $this->db->where('id_periodo_lectivo', $id);
        $query = $this->db->get('sw_periodo_lectivo');
        if ($query->num_rows() > 0) {
            $anio_inicio = $query->row()->pe_anio_inicio;
            $anio_fin = $query->row()->pe_anio_fin;
            return $anio_inicio . " - " . $anio_fin;
        } else {
            return false;
        }
    }

    function existePeriodoLectivo($anio_inicio) {
        $this->db->select('id_periodo_lectivo');
        $this->db->from('sw_periodo_lectivo');
        $this->db->where('pe_anio_inicio', $anio_inicio);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }
    
    function crearPeriodoLectivo($data){
        $this->db->insert('sw_periodo_lectivo',
                    array('pe_anio_inicio'=>$data['pe_anio_inicio'],
                            'pe_anio_fin'=>$data['pe_anio_fin'],
                            'pe_estado'=>$data['pe_estado'],
                            'id_institucion'=>$data['id_institucion']
                            ));
    }
    
    function existeTiposEducacionPeriodoLectivo($id){
        $query = $this->db->query("SELECT * FROM sw_tipo_educacion WHERE id_periodo_lectivo = $id");
        return $query->num_rows();
    }
    
    function eliminarPeriodoLectivo($id){
        $query = "DELETE FROM sw_periodo_lectivo WHERE id_periodo_lectivo = $id";
        $this->db->query($query);
    }
}