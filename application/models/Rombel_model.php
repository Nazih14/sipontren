<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Rombel_model extends CI_Model {

    var $table = 'md_rombel';
    var $primaryKey = 'ID_ROMBEL';
    var $user = 'USER_ROMBEL';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table($select = TRUE) {
        if ($select)
            $this->db->select('*, CONCAT(NAMA_KELAS, " - ", NAMA_KEGIATAN) AS NAMA_KELAS_ROMBEL, CONCAT(NAMA_RUANG, " - ", NAMA_GEDUNG) AS NAMA_RUANG_ROMBEL');
        $this->db->from($this->table);
        $this->db->join('md_jurusan', 'ID_JURUSAN=JURUSAN_ROMBEL');
        $this->db->join('md_kelas', 'ID_KELAS=KELAS_ROMBEL');
        $this->db->join('md_kegiatan', 'ID_KEGIATAN=KEGIATAN_KELAS');
        $this->db->join('md_ruang', 'ID_RUANG=RUANG_ROMBEL');
        $this->db->join('md_gedung', 'ID_GEDUNG=GEDUNG_RUANG');
        $this->db->join('md_ustadz', 'ID_ROMBEL=ROMBEL_UST', 'LEFT');
        $this->db->order_by('NAMA_KELAS, NAMA_ROMBEL', 'ASC');
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

    public function get_all() {
        $this->db->select('ID_ROMBEL as id, CONCAT(NAMA_ROMBEL, " - ", NAMA_KEGIATAN, " | ", "WALI KELAS: ",  IF(ID_UST IS NULL, CONCAT("BELUM ", "ADA"), NAMA_UST)) as title');
        $this->_get_table(FALSE);
        $result = $this->db->get();

        return $result->result();
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
