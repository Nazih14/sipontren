<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Asal_santri extends CI_Controller {

    var $primaryKey = 'ID_ASSAN';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'asal_santri_model' => 'asal_santri'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Asal Santri',
            'breadcrumb' => 'Master Data > Asal Santri',
            'table' => array(
                array(
                    'field' => "ID_ASSAN",
                    'title' => "ID",
                    'sortable' => "ID_ASSAN",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_ASSAN' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_ASSAN",
                    'title' => "Nama Asal Santri",
                    'sortable' => "NAMA_ASSAN",
                    'show' => true,
                    'filter' => array(
                        'NAMA_ASSAN' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_ASSAN",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_ASSAN",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_ASSAN' => 'text'
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
        $data = $this->asal_santri->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->asal_santri->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->asal_santri->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->asal_santri->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->asal_santri->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->asal_santri->get_all();

        $this->output_handler->output_JSON($data);
    }

}
