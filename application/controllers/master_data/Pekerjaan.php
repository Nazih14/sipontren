<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pekerjaan extends CI_Controller {

    var $primaryKey = 'ID_JENPEK';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'pekerjaan_model' => 'pekerjaan'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Pekerjaan',
            'breadcrumb' => 'Master Data > Pekerjaan',
            'table' => array(
                array(
                    'field' => "ID_JENPEK",
                    'title' => "ID",
                    'sortable' => "ID_JENPEK",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_JENPEK' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_JENPEK",
                    'title' => "Nama Pekerjaan",
                    'sortable' => "NAMA_JENPEK",
                    'show' => true,
                    'filter' => array(
                        'NAMA_JENPEK' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_JENPEK",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_JENPEK",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_JENPEK' => 'text'
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
        $data = $this->pekerjaan->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->pekerjaan->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->pekerjaan->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->pekerjaan->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->pekerjaan->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->pekerjaan->get_all();

        $this->output_handler->output_JSON($data);
    }

}
