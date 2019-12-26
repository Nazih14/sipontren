<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Akad_santri_model extends CI_Model {

    var $table = 'akad_santri';
    var $primaryKey = 'ID_AS';
    var $user = 'USER_AS';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->from($this->table);
        $this->db->join('md_tahun_ajaran', 'ID_TA=TA_AS');
        $this->db->join('md_kelas', 'ID_KELAS=KELAS_AS');
        $this->db->join('md_santri', 'ID_SANTRI=SANTRI_AS');
        $this->db->join('(SELECT *, CONCAT(NAMA_KAMAR, " - ", NAMA_GEDUNG) AS KAMAR_GEDUNG FROM md_kamar INNER JOIN md_gedung ON GEDUNG_KAMAR=ID_GEDUNG) md_kamar', 'KAMAR_SANTRI=ID_KAMAR');
        $this->db->join('md_rombel', 'ID_ROMBEL=ROMBEL_AS', 'LEFT');
        $this->db->join('md_ustadz', 'ID_ROMBEL=ROMBEL_UST', 'LEFT');
    }

    public function get_datatable($where = NULL) {
        $this->_get_table();
        
        if ($where != NULL)
            $this->db->where($where);
        
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

    public function get_row_simple($where) {
        $this->db->from($this->table);
        $this->db->where($where);
        $result = $this->db->get()->row();

        return $result;
    }

    public function get_rows_simple($where) {
        $this->db->from($this->table);
        $this->db->where($where);
        $result = $this->db->get()->result();

        return $result;
    }

    public function get_row($where) {
        $this->_get_table();
        $this->db->where($where);
        $result = $this->db->get()->row();

        return $result;
    }

    public function get_rows($where) {
        $this->_get_table();
        $this->db->where($where);
        $result = $this->db->get()->result();

        return $result;
    }

    public function save($data) {
        if ($this->user != '')
            $data[$this->user] = $this->session->userdata('ID_USER');
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
