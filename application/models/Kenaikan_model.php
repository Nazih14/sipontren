<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Kenaikan_model extends CI_Model {

    var $table = 'akad_santri';
    var $primaryKey = 'ID_AS';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->from($this->table);
        $this->db->join('md_santri', 'ID_SANTRI=SANTRI_AS');
        $this->db->join('(SELECT *, CONCAT(NAMA_KAMAR, " - ", NAMA_GEDUNG) AS KAMAR_GEDUNG FROM md_kamar INNER JOIN md_gedung ON GEDUNG_KAMAR=ID_GEDUNG) md_kamar', 'KAMAR_SANTRI=ID_KAMAR');
    }

    private function set_key_data($data) {
        $return = array();

        foreach ($data as $detail) {
            $return[$detail->ID_SANTRI] = $detail;
        }

        return $return;
    }

    public function get_datatable($post) {
        $this->db->select($this->database_handler->set_select($post['select']));
        $this->_get_table();
        $this->db->where(array(
            'TA_AS' => $this->session->userdata('ID_TA'),
            'ROMBEL_AS' => $post['where']['ROMBEL_LAMA'],
        ));
        $data_lama = $this->db->get()->result();

        $this->db->select($this->database_handler->set_select($post['select']));
        $this->_get_table();
        $this->db->where(array(
            'TA_AS' => $post['where']['TA_BARU'],
            'ROMBEL_AS' => $post['where']['ROMBEL_BARU']
        ));
        $data_baru = $this->db->get()->result();

        $result_lama = $this->set_key_data($data_lama);
        $result_baru = $this->set_key_data($data_baru);

        foreach ($result_lama as $index => $item) {
            if (isset($result_baru[$index]))
                unset($result_lama[$index]);
        }

        $result = array(
            "lama" => array_values($result_lama),
            "baru" => array_values($result_baru)
        );

        return $result;
    }

    public function proses_kenaikan($data) {
        if (isset($data['ROMBEL_LAMA'])) {
            $post = array(
                'select' => array('*'),
                'where' => array(
                    'TA_BARU' => $data['TA_AS'],
                    'ROMBEL_BARU' => $data['ROMBEL_AS'],
                    'ROMBEL_LAMA' => $data['ROMBEL_LAMA'],
                )
            );
            $result = $this->get_datatable($post);

            if (count($result['baru']) == 0) {
                $sql = 'INSERT INTO ' . $this->table . ' (TA_AS, KELAS_AS, ROMBEL_AS, SANTRI_AS, USER_AS) SELECT ' . $data['TA_AS'] . ', ' . $data['KELAS_AS'] . ', ' . $data['ROMBEL_AS'] . ', SANTRI_AS, ' . $this->session->userdata('ID_USER') . ' FROM ' . $this->table . ' WHERE TA_AS=' . $this->session->userdata('ID_TA') . ' AND ROMBEL_AS=' . $data['ROMBEL_LAMA'];
                $this->db->query($sql);
            } else {
                unset($data['ROMBEL_LAMA']);
                foreach ($result['lama'] as $detail) {
                    $data['SANTRI_AS'] = $detail->ID_SANTRI;
                    $data['USER_AS'] = $this->session->userdata('ID_USER');
                    $this->db->insert($this->table, $data);
                }
            }
        } elseif ($data['naik']) {
            unset($data['naik']);
            unset($data['ID_AS']);

            $data['USER_AS'] = $this->session->userdata('ID_USER');
            $this->db->insert($this->table, $data);
        } else {
            $where = array(
                'ID_AS' => $data['ID_AS']
            );
            $this->db->delete($this->table, $where);
        }

        return $this->db->affected_rows();
    }

}
