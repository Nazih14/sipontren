<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Data_akademik_model extends CI_Model {

    var $table = 'md_santri';
    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->select('*, CONCAT(ALAMAT_SANTRI, ", ", NAMA_KEC, ", ", NAMA_KAB, ", ", NAMA_PROV) AS ALAMAT_LENGKAP_SANTRI, IF(ID_KAMAR IS NULL, "-", CONCAT(NAMA_KAMAR, " - ", NAMA_GEDUNG)) AS KAMAR_SANTRI, CONCAT(TEMPAT_LAHIR_SANTRI, ", ", DATE_FORMAT(TANGGAL_LAHIR_SANTRI, "%d-%m-%Y")) AS TTL_SANTRI, IF(ROMBEL_AS IS NULL, "-", CONCAT(NAMA_ROMBEL, " - ", NAMA_KEGIATAN)) AS ROMBEL_SANTRI');
        $this->db->from($this->table);
        $this->db->join('akad_santri', 'SANTRI_AS=ID_SANTRI');
        $this->db->join('md_rombel', 'ID_ROMBEL=ROMBEL_AS', 'LEFT');
        $this->db->join('md_kelas', 'KELAS_ROMBEL=ID_KELAS', 'LEFT');
        $this->db->join('md_kegiatan', 'ID_KEGIATAN=KEGIATAN_KELAS', 'LEFT');
        $this->db->join('md_jenis_kelamin', 'ID_JK=JK_SANTRI');
        $this->db->join('md_kecamatan', 'ID_KEC=KECAMATAN_SANTRI');
        $this->db->join('md_kabupaten', 'ID_KAB=KABUPATEN_KEC');
        $this->db->join('md_provinsi', 'ID_PROV=PROVINSI_KAB');
        $this->db->join('md_kamar', 'ID_KAMAR=KAMAR_SANTRI', 'LEFT');
        $this->db->join('md_gedung', 'GEDUNG_KAMAR=ID_GEDUNG', 'LEFT');
        $this->db->where(array(
            'ALUMNI_SANTRI' => 0,
            'AKTIF_SANTRI' => 1,
            'STATUS_MUTASI_SANTRI' => NULL,
            'KELAS_AS <> ' => NULL,
            'TA_AS' => $this->session->userdata("ID_TA"),
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
}
