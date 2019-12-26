<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jurusan extends CI_Controller {

    var $primaryKey = 'ID_JURUSAN';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan_model' => 'jurusan',
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Jurusan',
            'breadcrumb' => 'Pengaturan > Akademik > Jurusan',
            'table' => array(
                array(
                    'field' => "ID_JURUSAN",
                    'title' => "ID",
                    'sortable' => "ID_JURUSAN",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_JURUSAN' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_JURUSAN",
                    'title' => "Nama",
                    'sortable' => "NAMA_JURUSAN",
                    'show' => true,
                    'filter' => array(
                        'NAMA_JURUSAN' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_JURUSAN",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_JURUSAN",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_JURUSAN' => 'text'
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
        $data = $this->jurusan->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->jurusan->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->jurusan->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->jurusan->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->jurusan->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->jurusan->get_all();

        $this->output_handler->output_JSON($data);
    }

}
