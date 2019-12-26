<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jk extends CI_Controller {

    var $primaryKey = 'ID_JK';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jk_model' => 'jk'
        ));
        $this->auth->validation(array(1, 2, 3));
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Jenis Kelamin',
            'breadcrumb' => 'Master Data > Jenis Kelamin',
            'table' => array(
                array(
                    'field' => "ID_JK",
                    'title' => "ID",
                    'sortable' => "ID_JK",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_JK' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_JK",
                    'title' => "Nama",
                    'sortable' => "NAMA_JK",
                    'show' => true,
                    'filter' => array(
                        'NAMA_JK' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_JK",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_JK",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_JK' => 'text'
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
        $data = $this->jk->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->jk->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->jk->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->jk->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->jk->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->jk->get_all();

        $this->output_handler->output_JSON($data);
    }

}
