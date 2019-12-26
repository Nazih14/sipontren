<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    var $table = 'md_user';
    var $column = array('ID_USER', 'NAME_USER', 'NAMA_UST','STATUS_USER','LASTLOGIN_USER','ID_USER');
    var $primary_key = "ID_USER";
    var $order = array("ID_USER" => 'ASC');

    public function __construct() {
        parent::__construct();
    }

    private function _get_table() {
        $this->db->from($this->table);
        $this->db->join('md_ustadz mp', $this->table.'.USTADZ_USER=mp.ID_UST');
    }

    private function _get_datatables_query() {
        $this->_get_table();
        $i = 0;
        $search_value = $_POST['search']['value'];
        $search_columns = $_POST['columns'];
        foreach ($this->column as $item) {
            if ($search_value || $search_columns) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $search_value);
                } else {
                    $this->db->or_like($item, $search_value);
                }
                if (count($search_columns) - 1 == $i) {
                    $this->db->group_end();
                    break;
                }
            }
            $column[$i] = $item;
            $i++;
        }
        $i = 0;
        foreach ($this->column as $item) {
            if ($search_columns) {
                if ($i === 0)
                    $this->db->group_start();
                $this->db->like($item, $search_columns[$i]['search']['value']);
                if (count($search_columns) - 1 == $i) {
                    $this->db->group_end();
                    break;
                }
            }
            $column[$i] = $item;
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();

        return $query->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_by_id($id) {
        $this->_get_table();
        $this->db->where($this->primary_key, $id);

        return $this->db->get()->row();
    }

    public function get_all($for_html = true) {
        if ($for_html) $this->db->select("ID_USER as value, NAME_USER as label");
        $this->_get_table();

        return $this->db->get()->result();
    }

    public function get_all_ac($where) {
        $this->db->select("ID_USER as id, NAME_USER as text");
        $this->_get_table();
        $this->db->like('NAME_USER', $where);

        return $this->db->get()->result();
    }

    public function count_all() {
        $this->db->from($this->table);

        return $this->db->count_all_results();
    }

    public function save($data) {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function update($where, $data) {
        $this->db->update($this->table, $data, $where);
        
        return $this->db->affected_rows();
    }

    public function delete_by_id($id) {
        $where = array($this->primary_key => $id);
        $this->db->delete($this->table, $where);
        
        return $this->db->affected_rows();
    }

    public function get_status_login($data) {
        $this->_get_table();
        if (is_array($data))
            $this->db->where(array('NAME_USER' => $data->username, 'PASSWORD_USER' => $this->crypt->encryptPassword($data->password)));
        else
            $this->db->where('NAME_USER', $data->username);
        
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_status_login_username($data) {
        $this->_get_table();
        $this->db->where('NAME_USER', $data->username);
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_hakakses() {
        $this->db->from('md_hakakses');
        return $this->db->get()->result();
    }
    
    public function tambah_percobaan_login($id) {
        $this->db->update(
            $this->table, 
            'PERCOBAAN_USER = (PERCOBAAN_USER + 1)', 
            array('ID_USER' => $id)
        );
        
        return $this->db->affected_rows();
    }

}
