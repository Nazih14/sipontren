<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rombel extends CI_Controller {

    var $primaryKey = 'ID_ROMBEL';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'rombel_model' => 'rombel',
            'kelas_model' => 'kelas',
            'ruang_model' => 'ruang',
            'jurusan_model' => 'jurusan',
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Rombongan Belajar',
            'breadcrumb' => 'Pengaturan > Akademik > Rombongan Belajar',
            'table' => array(
                array(
                    'field' => "ID_ROMBEL",
                    'title' => "ID",
                    'sortable' => "ID_ROMBEL",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_ROMBEL' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_KELAS_ROMBEL",
                    'title' => "Kelas",
                    'sortable' => "NAMA_KELAS_ROMBEL",
                    'show' => true,
                    'filter' => array(
                        'KELAS_ROMBEL' => 'select'
                    ),
                    'filterData' => $this->kelas->get_all()
                ),
                array(
                    'field' => "NAMA_RUANG_ROMBEL",
                    'title' => "Ruang",
                    'sortable' => "NAMA_RUANG_ROMBEL",
                    'show' => true,
                    'filter' => array(
                        'RUANG_ROMBEL' => 'select'
                    ),
                    'filterData' => $this->ruang->get_all()
                ),
                array(
                    'field' => "NAMA_ROMBEL",
                    'title' => "Nama",
                    'sortable' => "NAMA_ROMBEL",
                    'show' => true,
                    'filter' => array(
                        'NAMA_ROMBEL' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_JURUSAN",
                    'title' => "Jurusan",
                    'sortable' => "NAMA_JURUSAN",
                    'show' => true,
                    'filter' => array(
                        'ID_JURUSAN' => 'select'
                    ),
                    'filterData' => $this->jurusan->get_all()
                ),
                array(
                    'field' => "KETERANGAN_ROMBEL",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_ROMBEL",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_ROMBEL' => 'text'
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
        $data = $this->rombel->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'dataKELAS_ROMBEL' => $this->kelas->get_all(),
            'dataRUANG_ROMBEL' => $this->ruang->get_all(),
            'dataJURUSAN_ROMBEL' => $this->jurusan->get_all(),
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->rombel->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->rombel->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->rombel->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->rombel->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->rombel->get_all();

        $this->output_handler->output_JSON($data);
    }

}
