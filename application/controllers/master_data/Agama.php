<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Agama extends CI_Controller {

    var $primaryKey = 'ID_AGAMA';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'agama_model' => 'agama'
        ));
        $this->auth->validation(array(1, 2, 3));
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Agama',
            'breadcrumb' => 'Master Data > Agama',
            'table' => array(
                array(
                    'field' => "ID_AGAMA",
                    'title' => "ID",
                    'sortable' => "ID_AGAMA",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_AGAMA' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_AGAMA",
                    'title' => "Nama Gedung",
                    'sortable' => "NAMA_AGAMA",
                    'show' => true,
                    'filter' => array(
                        'NAMA_AGAMA' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_AGAMA",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_AGAMA",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_AGAMA' => 'text'
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
        $data = $this->agama->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->agama->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->agama->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->agama->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->agama->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->agama->get_all();

        $this->output_handler->output_JSON($data);
    }

}
