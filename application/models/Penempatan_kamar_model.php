<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Penempatan_kamar_model extends CI_Model {

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

    public function get_datatable_santri_no_kamar() {
        $this->_get_table();
        $this->db->where(array(
            'ALUMNI_SANTRI' => 0,
            'STATUS_MUTASI_SANTRI' => NULL,
            'KAMAR_SANTRI' => NULL,
        ));
        $data = $this->db->get()->result();

        $result = array(
            "data" => $data
        );

        return $result;
    }

    public function get_datatable_santri_kamar($KAMAR_SK) {
        $this->_get_table();
        $this->db->where(array(
            'ALUMNI_SANTRI' => 0,
            'STATUS_MUTASI_SANTRI' => NULL,
            'KAMAR_SANTRI' => $KAMAR_SK,
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
        if ($data['ACTION'] == 'set') {
            $kamar = $data['KAMAR_SANTRI'];
        } elseif ($data['ACTION'] == 'remove') {
            $kamar = NULL;
        }
        
        $data_update = array(
            'KAMAR_SANTRI' => $kamar,
            'AKTIF_SANTRI' => 1
        );
        $where_update = array(
            'ID_SANTRI' => $data['ID_SANTRI']
        );
        $this->update($data_update, $where_update);

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
