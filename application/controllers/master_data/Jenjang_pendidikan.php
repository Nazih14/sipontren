<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jenjang_pendidikan extends CI_Controller {

    var $primaryKey = 'ID_JP';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jenjang_pendidikan_model' => 'jenjang_pendidikan'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Jenjang Pendidikan',
            'breadcrumb' => 'Master Data > Jenjang Pendidikan',
            'table' => array(
                array(
                    'field' => "ID_JP",
                    'title' => "ID",
                    'sortable' => "ID_JP",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_JP' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_JP",
                    'title' => "Nama Jenjang Pendidikan",
                    'sortable' => "NAMA_JP",
                    'show' => true,
                    'filter' => array(
                        'NAMA_JP' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_JP",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_JP",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_JP' => 'text'
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
        $data = $this->jenjang_pendidikan->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->jenjang_pendidikan->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->jenjang_pendidikan->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->jenjang_pendidikan->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->jenjang_pendidikan->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->jenjang_pendidikan->get_all();

        $this->output_handler->output_JSON($data);
    }

}
