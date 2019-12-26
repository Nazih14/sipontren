<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Data_psb_model extends CI_Model {

    var $table = 'md_santri';
    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->select('*, CONCAT(ALAMAT_SANTRI, ", ", NAMA_KEC, ", ", NAMA_KAB, ", ", NAMA_PROV) AS ALAMAT_LENGKAP_SANTRI, DATE_FORMAT(TANGGAL_LAHIR_SANTRI, "%d-%m-%Y") AS TANGGAL_LAHIR_SANTRI_SHOW');
        $this->db->from($this->table);
        $this->db->join('psb_kelompok', 'ID_PKK=PSB_KELOMPOK_SANTRI');
        $this->db->join('md_jenis_kelamin', 'ID_JK=JK_SANTRI');
        $this->db->join('md_kecamatan', 'ID_KEC=KECAMATAN_SANTRI');
        $this->db->join('md_kabupaten', 'ID_KAB=KABUPATEN_KEC');
        $this->db->join('md_provinsi', 'ID_PROV=PROVINSI_KAB');
        $this->db->where(array(
            'AKTIF_SANTRI' => 0,
            'ALUMNI_SANTRI' => 0,
            'STATUS_MUTASI_SANTRI' => NULL,
        ));
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

    public function save($data) {
        $ID_TA = $data['ID_TA'];
        $KEGIATAN = $data['KEGIATAN_SANTRI'];
        unset($data['ID_TA']);
        unset($data['KEGIATAN_SANTRI']);

        $updated = FALSE;
        if (isset($data[$this->primaryKey])) {
            $updated = TRUE;
            $where = array($this->primaryKey => $data[$this->primaryKey]);
            unset($data[$this->primaryKey]);
            $result = $this->update($data, $where);
        } else {
            unset($data[$this->primaryKey]);
            $data['ANGKATAN_SANTRI'] = date('Y');
            $result = $this->insert($data);
        }

        if ($result || $updated) {
            $data_akad = array();
            $where_akad = array(
                'TA_AS' => $ID_TA,
                'SANTRI_AS' => $updated ? $where[$this->primaryKey] : $result
            );

            foreach ($KEGIATAN as $KELAS_AS) {
                $data_akad[] = array(
                    'TA_AS' => $ID_TA,
                    'KELAS_AS' => $KELAS_AS,
                    'SANTRI_AS' => $updated ? $where[$this->primaryKey] : $result,
                    'USER_AS' => $this->session->userdata('ID_USER'),
                );
            }

            $result = $this->add_kegiatan($data_akad, $where_akad);
        }

        return $result;
    }

    public function add_kegiatan($data, $where) {
        $this->db->delete('akad_santri', $where);
        foreach ($data as $detail) {
            $this->db->insert('akad_santri', $detail);
        }

        return $this->db->insert_id();
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
