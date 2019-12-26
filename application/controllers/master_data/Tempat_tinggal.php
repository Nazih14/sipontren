<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tempat_tinggal extends CI_Controller {

    var $primaryKey = 'ID_TEMTING';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'tempat_tinggal_model' => 'tempat_tinggal'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Tempat Tinggal',
            'breadcrumb' => 'Master Data > Tempat Tinggal',
            'table' => array(
                array(
                    'field' => "ID_TEMTING",
                    'title' => "ID",
                    'sortable' => "ID_TEMTING",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_TEMTING' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_TEMTING",
                    'title' => "Nama Tempat Tinggal",
                    'sortable' => "NAMA_TEMTING",
                    'show' => true,
                    'filter' => array(
                        'NAMA_TEMTING' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_TEMTING",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_TEMTING",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_TEMTING' => 'text'
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
        $data = $this->tempat_tinggal->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->tempat_tinggal->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->tempat_tinggal->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->tempat_tinggal->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->tempat_tinggal->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->tempat_tinggal->get_all();

        $this->output_handler->output_JSON($data);
    }

}
