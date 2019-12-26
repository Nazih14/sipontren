<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Tahun_ajaran_model extends CI_Model {

    var $table = 'md_tahun_ajaran';
    var $primaryKey = 'ID_TA';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->select('*, IF(AKTIF_TA = 1, "YA", "TIDAK") AS STATUS_AKTIF_TA');
        $this->db->from($this->table);
        $this->db->order_by('NAMA_TA', 'ASC');
    }

    public function get_datatable() {
        $this->_get_table();
        $data = $this->db->get()->result();

        $result = array(
            "data" => $data
        );

        return $result;
    }

    public function get_by_id($id) {
        $this->_get_table();
        $this->db->where($this->primaryKey, $id);
        $result = $this->db->get()->row_array();

        return $result;
    }

    public function get_next_ta() {
        $this->db->select('ID_TA as id, CONCAT("Tahun ", NAMA_TA) as title');
        $this->db->from($this->table);
        $this->db->where('NAMA_TA > ', $this->session->userdata('NAMA_TA'));
        $result = $this->db->get()->result_array();

        return $result;
    }

    public function get_ta_active() {
        $this->db->from($this->table);
        $this->db->where(array(
            'AKTIF_TA' => 1
        ));
        $result = $this->db->get()->row_array();

        return $result;
    }

    public function get_all() {
        $this->db->select('ID_TA as id, CONCAT("Tahun ", NAMA_TA) as title');
        $this->db->from($this->table);
        $result = $this->db->get();

        return $result->result();
    }

    public function save($data) {
        if ($data['AKTIF_TA'])
            $this->reset_aktif();
        
        if (isset($data[$this->primaryKey])) {
            $where = array($this->primaryKey => $data[$this->primaryKey]);
            unset($data[$this->primaryKey]);
            $result = $this->update($data, $where);
        } else {
            unset($data[$this->primaryKey]);
            $result = $this->insert($data);
        }
        
        return $result;
    }

    private function reset_aktif() {
        $data = array('AKTIF_TA' => 0);
        $where = array('AKTIF_TA' => 1);
        $this->update($data, $where);
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function update($data, $where) {
        $this->db->update($this->table, $data, $where);

        return $this->db->affected_rows();
    }

    public function delete($id) {
        $where = array($this->primaryKey => $id);
        $this->db->delete($this->table, $where);

        return $this->db->affected_rows();
    }

}
