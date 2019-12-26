<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Status_hidup extends CI_Controller {

    var $primaryKey = 'ID_SO';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'status_hidup_model' => 'status_hidup'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Status Hidup',
            'breadcrumb' => 'Master Data > Status Hidup',
            'table' => array(
                array(
                    'field' => "ID_SO",
                    'title' => "ID",
                    'sortable' => "ID_SO",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_SO' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_SO",
                    'title' => "Nama Status Hidup",
                    'sortable' => "NAMA_SO",
                    'show' => true,
                    'filter' => array(
                        'NAMA_SO' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_SO",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_SO",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_SO' => 'text'
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
        $data = $this->status_hidup->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->status_hidup->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->status_hidup->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->status_hidup->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->status_hidup->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->status_hidup->get_all();

        $this->output_handler->output_JSON($data);
    }

}
