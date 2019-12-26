<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {

    var $primaryKey = 'ID_KELAS';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'kelas_model' => 'kelas',
            'kegiatan_model' => 'kegiatan',
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Kelas',
            'breadcrumb' => 'Pengaturan > Akademik > Kelas',
            'table' => array(
                array(
                    'field' => "ID_KELAS",
                    'title' => "ID",
                    'sortable' => "ID_KELAS",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_KELAS' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_KEGIATAN",
                    'title' => "Nama Kegiatan",
                    'sortable' => "NAMA_KEGIATAN",
                    'show' => true,
                    'filter' => array(
                        'ID_KEGIATAN' => 'select'
                    ),
                    'filterData' => $this->kegiatan->get_all()
                ),
                array(
                    'field' => "NAMA_KELAS",
                    'title' => "Nama Kelas",
                    'sortable' => "NAMA_KELAS",
                    'show' => true,
                    'filter' => array(
                        'NAMA_KELAS' => 'text'
                    )
                ),
                array(
                    'field' => "KETERANGAN_KELAS",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_KELAS",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_KELAS' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_KELAS",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_KELAS",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_KELAS' => 'text'
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
        $data = $this->kelas->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'dataKEGIATAN_KELAS' => $this->kegiatan->get_all()
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->kelas->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->kelas->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->kelas->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->kelas->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->kelas->get_all();

        $this->output_handler->output_JSON($data);
    }

}
