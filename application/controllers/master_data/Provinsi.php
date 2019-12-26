<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Provinsi extends CI_Controller {

    var $primaryKey = 'ID_PROV';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'provinsi_model' => 'provinsi'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Provinsi',
            'breadcrumb' => 'Master Data > Provinsi',
            'table' => array(
                array(
                    'field' => "ID_PROV",
                    'title' => "ID",
                    'sortable' => "ID_PROV",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_PROV' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_PROV",
                    'title' => "Nama Provinsi",
                    'sortable' => "NAMA_PROV",
                    'show' => true,
                    'filter' => array(
                        'NAMA_PROV' => 'text'
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
        $data = $this->provinsi->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->provinsi->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->provinsi->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->provinsi->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->provinsi->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->provinsi->get_all();

        $this->output_handler->output_JSON($data);
    }

}
