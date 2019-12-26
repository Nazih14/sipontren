<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ruang extends CI_Controller {

    var $primaryKey = 'ID_RUANG';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'ruang_model' => 'ruang',
            'gedung_model' => 'gedung',
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Ruang',
            'breadcrumb' => 'Pengaturan > Akademik > Ruang',
            'table' => array(
                array(
                    'field' => "ID_RUANG",
                    'title' => "ID",
                    'sortable' => "ID_RUANG",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_RUANG' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_GEDUNG",
                    'title' => "Nama Gedung",
                    'sortable' => "NAMA_GEDUNG",
                    'show' => true,
                    'filter' => array(
                        'ID_GEDUNG' => 'select'
                    ),
                    'filterData' => $this->gedung->get_all()
                ),
                array(
                    'field' => "NAMA_RUANG",
                    'title' => "Nama Ruang",
                    'sortable' => "NAMA_RUANG",
                    'show' => true,
                    'filter' => array(
                        'NAMA_RUANG' => 'text'
                    )
                ),
                array(
                    'field' => "KETERANGAN_RUANG",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_RUANG",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_RUANG' => 'text'
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
        $data = $this->ruang->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'dataGEDUNG_RUANG' => $this->gedung->get_all()
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->ruang->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->ruang->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->ruang->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->ruang->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->ruang->get_all();

        $this->output_handler->output_JSON($data);
    }

}
