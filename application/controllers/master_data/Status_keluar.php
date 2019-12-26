<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Status_keluar extends CI_Controller {

    var $primaryKey = 'ID_MUTASI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'status_keluar_model' => 'status_keluar'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Status Keluar',
            'breadcrumb' => 'Master Data > Status Keluar',
            'table' => array(
                array(
                    'field' => "ID_MUTASI",
                    'title' => "ID",
                    'sortable' => "ID_MUTASI",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_MUTASI' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_MUTASI",
                    'title' => "Nama Status Keluar",
                    'sortable' => "NAMA_MUTASI",
                    'show' => true,
                    'filter' => array(
                        'NAMA_MUTASI' => 'text'
                    )
                ),
                array(
                    'field' => "ACTION",
                    'title' => "Aksi",
                    'actions' => array(
                        array(
                            'title' => 'Ubah',
                            'update' => true
                        ),
                        array(
                            'title' => 'Hapus',
                            'delete' => true
                        )
                    )
                ),
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function datatable() {
        $data = $this->status_keluar->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->status_keluar->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->status_keluar->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->status_keluar->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->status_keluar->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->status_keluar->get_all();

        $this->output_handler->output_JSON($data);
    }

}
