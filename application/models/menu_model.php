<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function listarMenusNivel1($id_perfil) {
        $this->db->where('id_perfil', $id_perfil);
        $this->db->where('mnu_padre', '0');
        $this->db->order_by('mnu_orden','ASC');
        return $this->db->get('sw_menu')->result();
    }

    public function listarMenusHijos($mnu_padre) {
        $this->db->where('mnu_padre', $mnu_padre);
        $this->db->order_by('mnu_orden', 'ASC');
        return $this->db->get('sw_menu')->result();
    }
    
    public function obtenerSecuencial($id_perfil, $nivel){
        $resultado = $this->db->query("SELECT secuencial_menu_nivel_perfil($id_perfil,$nivel) AS secuencial");
        return $resultado->row()->secuencial;
    }

    public function obtenerSecuencialMenuPadre($nivel, $id_perfil, $mnu_padre){
        $resultado = $this->db->query("SELECT secuencial_menu_nivel_perfil_padre($nivel,$id_perfil,$mnu_padre) AS secuencial");
        return $resultado->row()->secuencial;
    }
    
    public function existeMenu($mnu_texto, $perfil){
        $resultado = $this->db->query("SELECT id_menu FROM sw_menu WHERE mnu_texto = '$mnu_texto' AND id_perfil = $perfil");
        return $resultado->num_rows() > 0;
    }

    public function existeSubMenu($mnu_texto, $mnu_padre){
        $resultado = $this->db->query("SELECT id_menu FROM sw_menu WHERE mnu_texto = '$mnu_texto' AND mnu_padre = $mnu_padre");
        return $resultado->num_rows() > 0;
    }

    function crearMenu($data){
        $this->db->insert('sw_menu',
                    array('id_perfil'=>$data['id_perfil'],
                            'mnu_texto'=>$data['mnu_texto'],
                            'mnu_enlace'=>$data['mnu_enlace'],
                            'mnu_nivel'=>$data['mnu_nivel'],
                            'mnu_orden'=>$data['mnu_orden'],
                            'mnu_padre'=>$data['mnu_padre']
                            ));
    }

    function obtenerMenu($idMenu) {
        $this->db->where('id_menu', $idMenu);
        $query = $this->db->get('sw_menu');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function actualizarMenu($idMenu, $data){
        $datos = array(
            'mnu_texto'=>$data['mnu_texto'],
            'mnu_enlace'=>$data['mnu_enlace']
        );
        $this->db->where('id_menu',$idMenu);
        $this->db->update('sw_menu', $datos);
    }
    
    function existeSubMenus($idMenu){
        $query = $this->db->query("SELECT * FROM sw_menu WHERE mnu_padre = $idMenu");
        return $query->num_rows();
    }
    
    function eliminarMenu($idMenu){
        $query = "DELETE FROM sw_menu WHERE id_menu = $idMenu";
        $this->db->query($query);
    }
 
    public function contarMenusHijos($id_menu){
        $resultado = $this->db->query("SELECT COUNT(id_menu) AS contador FROM sw_menu WHERE mnu_padre = $id_menu");
        return $resultado->row();
    }
}
