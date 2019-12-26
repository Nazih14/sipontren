<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Jadwal_model extends CI_Model {

    var $table = 'akad_jadwal';
    var $primaryKey = 'ID_AJ';
    var $user = 'USER_AJ';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table($select = true) {
        if ($select)
            $this->db->select('*, CONCAT(IF(GELAR_AWAL_UST IS NOT NULL, IF(GELAR_AWAL_UST = "", CONCAT(GELAR_AWAL_UST, ". "), ""), ""), NAMA_UST, IF(GELAR_AKHIR_UST IS NOT NULL, IF(GELAR_AKHIR_UST = "", CONCAT(". ", GELAR_AKHIR_UST), ""), "")) AS NAMA_UST_SHOW, CONCAT(NAMA_KELAS, " - ", NAMA_KEGIATAN) AS NAMA_KELAS_SHOW');
        $this->db->from($this->table);
        $this->db->join('md_mapel', 'ID_MAPEL=MAPEL_AJ');
        $this->db->join('md_kelas', 'KELAS_MAPEL=ID_KELAS');
        $this->db->join('md_kegiatan', 'KEGIATAN_KELAS=ID_KEGIATAN');
        $this->db->join('md_ustadz', 'ID_UST=USTADZ_AJ');
        $this->db->where('TA_AJ', $this->session->userdata('ID_TA'));
        $this->db->where('AKTIF_UST', 1);
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

    public function get_data_form($id) {
        $this->db->from($this->table);
        $this->db->where($this->primaryKey, $id);
        $result = $this->db->get()->row_array();

        return $result;
    }

    public function get_all($params = NULL) {
        $this->db->select('ID_AJ as id, CONCAT("MAPEL: ",KODE_MAPEL, " - ", NAMA_MAPEL, " | GURU: ", CONCAT(IF(GELAR_AWAL_UST IS NOT NULL, IF(GELAR_AWAL_UST = "", CONCAT(GELAR_AWAL_UST, ". "), ""), ""), NAMA_UST, IF(GELAR_AKHIR_UST IS NOT NULL, IF(GELAR_AKHIR_UST = "", CONCAT(". ", GELAR_AKHIR_UST), ""), ""))) as title');
        $this->_get_table(false);
        
        if (is_array($params)) {
            if (isset($params['ID_ROMBEL']))
                $this->db->join('md_rombel', 'KELAS_ROMBEL=ID_KELAS');

            $this->db->where($params);
        }

        $result = $this->db->get();

        return $result->result();
    }

    public function save($data) {
        $data['TA_AJ'] = $this->session->userdata('ID_TA');

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
