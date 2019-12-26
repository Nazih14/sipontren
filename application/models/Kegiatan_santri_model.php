<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Kegiatan_santri_model extends CI_Model {

    var $table = 'md_santri';
    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->select('*, IF(NIS_SANTRI IS NULL, "-", NIS_SANTRI) AS NIS_SANTRI, CONCAT(ALAMAT_SANTRI, ", ", NAMA_KEC, ", ", NAMA_KAB, ", ", NAMA_PROV) AS ALAMAT_LENGKAP_SANTRI, CONCAT(TEMPAT_LAHIR_SANTRI, ", ", DATE_FORMAT(TANGGAL_LAHIR_SANTRI, "%d-%m-%Y")) AS TTL_SANTRI');
        $this->db->from($this->table);
        $this->db->join('md_jenis_kelamin', 'ID_JK=JK_SANTRI');
        $this->db->join('md_kecamatan', 'ID_KEC=KECAMATAN_SANTRI');
        $this->db->join('md_kabupaten', 'ID_KAB=KABUPATEN_KEC');
        $this->db->join('md_provinsi', 'ID_PROV=PROVINSI_KAB');
    }

    public function get_datatable_santri_no_kegiatan($KELAS_AS) {
        $this->_get_table();
        $this->db->join('(SELECT * FROM akad_santri WHERE KELAS_AS = '.$KELAS_AS.' AND TA_AS='. $this->session->userdata('ID_TA') .' GROUP BY SANTRI_AS) akad_santri', 'SANTRI_AS=ID_SANTRI', 'LEFT');
        $this->db->where(array(
            'ALUMNI_SANTRI' => 0,
            'STATUS_MUTASI_SANTRI' => NULL,
            'SANTRI_AS' => NULL
        ));
        $data = $this->db->get();
        
        $result = array(
            "data" => $data->result()
        );

        return $result;
    }

    public function get_datatable_santri_kegiatan($KELAS_AS) {
        $this->_get_table();
        $this->db->join('akad_santri', 'SANTRI_AS=ID_SANTRI');
        $this->db->where(array(
            'ALUMNI_SANTRI' => 0,
            'STATUS_MUTASI_SANTRI' => NULL,
            'KELAS_AS' => $KELAS_AS,
            'TA_AS' => $this->session->userdata('ID_TA'),
        ));
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

    public function save($data) {
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

    public function prosesSantri($data) {
        $data['USER_AS'] = $this->session->userdata('ID_USER');
        $data['TA_AS'] = $this->session->userdata('ID_TA');
        
        if ($data['ACTION'] == 'set') {
            unset($data['ACTION']);
            $this->db->insert('akad_santri', $data);
        } elseif ($data['ACTION'] == 'remove') {
            unset($data['ACTION']);
            $this->db->delete('akad_santri', $data);
        }

        return $this->db->affected_rows();
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
