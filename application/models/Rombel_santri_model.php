<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Rombel_santri_model extends CI_Model {

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
        $this->db->join('akad_santri', 'SANTRI_AS=ID_SANTRI');
        $this->db->where('TA_AS', $this->session->userdata('ID_TA'));
    }

    public function get_datatable_santri_no_rombel($ROMBEL_AS) {
        $this->_get_table();
        $this->db->join('(SELECT * FROM md_rombel WHERE ID_ROMBEL = ' . $ROMBEL_AS . ') md_kelas', 'KELAS_AS=KELAS_ROMBEL', 'LEFT');
        $this->db->where(array(
            'ALUMNI_SANTRI' => 0,
            'STATUS_MUTASI_SANTRI' => NULL,
            'ID_ROMBEL <> ' => NULL,
            'ROMBEL_AS' => NULL,
        ));
        $data = $this->db->get();

        $result = array(
            "data" => $data->result()
        );

        return $result;
    }

    public function get_datatable_santri_rombel($ROMBEL_AS) {
        $this->_get_table();
        $this->db->join('md_rombel', 'ROMBEL_AS=ID_ROMBEL');
        $this->db->where(array(
            'ALUMNI_SANTRI' => 0,
            'STATUS_MUTASI_SANTRI' => NULL,
            'ROMBEL_AS' => $ROMBEL_AS,
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
        $data['TA_AS'] = $this->session->userdata('ID_TA');

        $data['KELAS_AS'] = $data['DATA_ROMBEL']['KELAS_ROMBEL'];
        unset($data['DATA_ROMBEL']);
        
        $where_update = $data;

        if ($data['ACTION'] == 'set') {
            $data_update = array(
                'ROMBEL_AS' => $data['ROMBEL_AS']
            );
        } elseif ($data['ACTION'] == 'remove') {
            $data_update = array(
                'ROMBEL_AS' => NULL
            );
        }
        
        unset($where_update['ACTION']);
        unset($where_update['ROMBEL_AS']);
        $data_update['USER_AS'] = $this->session->userdata('ID_USER');
        
        $this->db->update('akad_santri', $data_update, $where_update);
        
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
