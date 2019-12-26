<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Akun_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_datatable_akun($post) {
        $this->db->select($this->database_handler->set_select($post));
        $this->db->from('md_user');
        $this->db->join('md_ustadz', 'USTADZ_USER = ID_UST');
        $data = $this->db->get();

        $result = array(
            "data" => $data->result()
        );

        return $result;
    }

    public function get_datatable_hakakses($post) {
        $this->db->from('md_hakakses');
        $this->db->join('md_hakakses_user', 'HAKAKSES_HU = ID_HAKAKSES AND USER_HU=' . $post['ID_USER'], 'LEFT');
        $data = $this->db->get();

        $result = array(
            "data" => $data->result()
        );

        return $result;
    }

    public function proses_hakakses($post) {
        $set = $post['set'];
        unset($post['set']);

        if ($set) {
            $this->db->insert('md_hakakses_user', $post);
        } else {
            $this->db->delete('md_hakakses_user', $post);
        }

        return $this->db->affected_rows();
    }

    public function proses_status($post) {
        $set = array(
            'STATUS_USER' => 'ACTIVE',
            'SISA_PERCOBAAN_USER' => 10
        );
        $this->db->update('md_user', $set, $post);
        
        return $this->db->affected_rows();
    }

    public function change_password($post) {
        $set = array(
            'PASSWORD_USER' => $this->crypt->encryptPassword($post['NEW_PASSWORD']),
        );
        $where = array(
            'ID_USER' => $post['ID_USER']
        );
        $this->db->update('md_user', $set, $where);
        
        return $this->db->affected_rows();
    }

}
