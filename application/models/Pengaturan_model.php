<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Pengaturan_model extends CI_Model {
    
    var $table = 'md_pengaturan';
    var $primary_key = "ID_PENGATURAN";

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->from($this->table);
    }
    
    public function get_all() {
        $this->_get_table();

        return $this->db->get()->result();
    }
    
    public function get_editable() {
        $this->_get_table();
        $this->db->where('EDITABLE_PENGATURAN', 1);
        $this->db->order_by('ORDER_PENGATURAN', 'ASC');

        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->_get_table();
        $this->db->where($this->primary_key, $id);

        return $this->db->get()->row()->NAMA_PENGATURAN;
    }

    public function update($id, $value) {
        $data = array('NAMA_PENGATURAN' => $value);
        $where = array('ID_PENGATURAN' => $id);
        
        $this->db->update($this->table, $data, $where);

        return $this->db->affected_rows();
    }
}
