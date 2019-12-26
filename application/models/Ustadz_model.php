<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ustadz_model extends CI_Model {

    var $table = 'md_ustadz';
    var $primaryKey = 'ID_UST';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table($select = true) {
        if ($select)
            $this->db->select('*, CONCAT(ALAMAT_UST, ", ", NAMA_KEC, ", ", NAMA_KAB, ", ", NAMA_PROV) AS ALAMAT_LENGKAP_UST, DATE_FORMAT(TANGGAL_LAHIR_UST, "%d-%m-%Y") AS TANGGAL_LAHIR_UST_SHOW, IF(ID_ROMBEL IS NULL, "", CONCAT(NAMA_ROMBEL, " - ", NAMA_KEGIATAN)) AS ROMBEL_KEGIATAN');
        $this->db->from($this->table);
        $this->db->join('md_jenis_kelamin', 'ID_JK=JK_UST');
        $this->db->join('md_kecamatan', 'ID_KEC=KECAMATAN_UST');
        $this->db->join('md_kabupaten', 'ID_KAB=KABUPATEN_KEC');
        $this->db->join('md_provinsi', 'ID_PROV=PROVINSI_KAB');
        $this->db->join('md_rombel', 'ID_ROMBEL=ROMBEL_UST', 'LEFT');
        $this->db->join('md_kelas', 'ID_KELAS=KELAS_ROMBEL', 'LEFT');
        $this->db->join('md_kegiatan', 'ID_KEGIATAN=KEGIATAN_KELAS', 'LEFT');
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
        $this->db->select('ID_UST as id, CONCAT(IF(GELAR_AWAL_UST IS NOT NULL, IF(GELAR_AWAL_UST = "", CONCAT(GELAR_AWAL_UST, ". "), ""), ""), NAMA_UST, IF(GELAR_AKHIR_UST IS NOT NULL, IF(GELAR_AKHIR_UST = "", CONCAT(". ", GELAR_AKHIR_UST), ""), "")) as title');
        $this->_get_table(FALSE);
        $this->db->where('AKTIF_UST', 1);
        $result = $this->db->get();

        return $result->result();
    }

    public function get_data_form($id) {
        $this->db->from($this->table);
        $this->db->where($this->primaryKey, $id);
        $result = $this->db->get()->row_array();

        return $result;
    }

    public function save($data) {
        if ($data['ROMBEL_UST'] == '')
            $data['ROMBEL_UST'] = NULL;

        if (isset($data[$this->primaryKey])) {
            $where = array($this->primaryKey => $data[$this->primaryKey]);
            unset($data[$this->primaryKey]);
            $result = $this->update($data, $where);
            
            $data_user = array(
                'NAME_USER' => $data['NIP_UST'],
            );
            $where_user = array(
                'USTADZ_USER' => $data[$this->primaryKey]
            );
            $this->db->update('md_user', $data_user, $where_user);
        } else {
            unset($data[$this->primaryKey]);
            $result = $this->insert($data);

            $data_user = array(
                'NAME_USER' => $data['NIP_UST'],
                'PASSWORD_USER' => $this->crypt->encryptDefaultPassword(),
                'USTADZ_USER' => $result,
            );
            $this->db->insert('md_user', $data_user);
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

    public function get_rombel($id) {
        $this->db->select('ID_ROMBEL as id, CONCAT(NAMA_ROMBEL, " - ", NAMA_KEGIATAN) as title');
        $this->db->from($this->table);
        $this->db->join('md_rombel', 'ID_ROMBEL=ROMBEL_UST', 'RIGHT');
        $this->db->join('md_kelas', 'ID_KELAS=KELAS_ROMBEL');
        $this->db->join('md_kegiatan', 'ID_KEGIATAN=KEGIATAN_KELAS');
        $this->db->join('md_ruang', 'ID_RUANG=RUANG_ROMBEL');
        $this->db->join('md_gedung', 'ID_GEDUNG=GEDUNG_RUANG');
        $this->db->where('ID_UST', NULL);
        $this->db->or_where('ID_UST', $id);
        $result = $this->db->get()->result_array();

        $result[] = array(
            'id' => NULL,
            'title' => 'KOSONGKAN'
        );

        return $result;
    }

}
