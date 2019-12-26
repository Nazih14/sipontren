<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kondisi extends CI_Controller {

    var $primaryKey = 'ID_KONDISI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'kondisi_model' => 'kondisi'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Kondisi',
            'breadcrumb' => 'Master Data > Kondisi',
            'table' => array(
                array(
                    'field' => "ID_KONDISI",
                    'title' => "ID",
                    'sortable' => "ID_KONDISI",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_KONDISI' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_KONDISI",
                    'title' => "Nama Kondisi",
                    'sortable' => "NAMA_KONDISI",
                    'show' => true,
                    'filter' => array(
                        'NAMA_KONDISI' => 'text'
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
        $data = $this->kondisi->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->kondisi->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->kondisi->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->kondisi->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->kondisi->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->kondisi->get_all();

        $this->output_handler->output_JSON($data);
    }

}
