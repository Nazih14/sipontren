<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gedung extends CI_Controller {

    var $primaryKey = 'ID_GEDUNG';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'gedung_model' => 'gedung'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Gedung',
            'breadcrumb' => 'Pengaturan > Akademik > Gedung',
            'table' => array(
                array(
                    'field' => "ID_GEDUNG",
                    'title' => "ID",
                    'sortable' => "ID_GEDUNG",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_GEDUNG' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_GEDUNG",
                    'title' => "Nama Gedung",
                    'sortable' => "NAMA_GEDUNG",
                    'show' => true,
                    'filter' => array(
                        'NAMA_GEDUNG' => 'text'
                    )
                ),
                array(
                    'field' => "KETERANGAN_GEDUNG",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_GEDUNG",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_GEDUNG' => 'text'
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
        $data = $this->gedung->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->gedung->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->gedung->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->gedung->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->gedung->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->gedung->get_all();

        $this->output_handler->output_JSON($data);
    }

}
