<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Pembayaran_model extends CI_Model {

    var $table = 'akad_santri';
    var $primaryKey = 'ID_AS';

    public function __construct() {
        parent::__construct();
    }

    public function get_tagihan($ID_SANTRI) {
        $this->db->select('*, CONCAT("Rp ", FORMAT(NOMINAL_TAGIHAN, 2)) AS NOMIMAL_TAGIHAN_SHOW');
        $this->db->from('akad_santri');
        $this->db->join('keu_tagihan', 'TA_AS=TA_TAGIHAN AND KELAS_TAGIHAN=KELAS_AS');
        $this->db->join('keu_pembayaran', 'TA_BAYAR=TA_AS AND SANTRI_BAYAR=SANTRI_AS AND TAGIHAN_BAYAR=ID_TAGIHAN', 'LEFT');
        $this->db->join('keu_pengecualian', 'TAGIHAN_KPC = ID_TAGIHAN AND SANTRI_KPC=SANTRI_AS AND TA_KPC=TA_AS', 'LEFT');
        $this->db->where('SANTRI_AS', $ID_SANTRI);
        $this->db->where('SANTRI_KPC', NULL);
        $this->db->where('ID_BAYAR', NULL);
        $data = $this->db->get();

        $result = array(
            "data" => $data->result_array()
        );

        return $result;
    }

    public function prosesPembayaran($data) {
        foreach ($data['DATA_TAGIHAN'] as $id_tagihan) {
            $data = array(
                'TA_BAYAR' => $this->session->userdata('ID_TA'),
                'TANGGAL_BAYAR' => $this->datetime_handler->date_to_store(),
                'TAGIHAN_BAYAR' => $id_tagihan,
                'SANTRI_BAYAR' => $data['SANTRI_BAYAR'],
                'USER_BAYAR' => $this->session->userdata('ID_USER')
            );
            
            $result = $this->db->insert('keu_pembayaran', $data);
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
