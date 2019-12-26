<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan extends CI_Controller {

    var $primaryKey = 'ID_KEGIATAN';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'kegiatan_model' => 'kegiatan'
        ));
        $this->auth->validation(1);
    }

    public function index() {
        $data = array(
            'title' => 'Master Data Kegiatan',
            'breadcrumb' => 'Pengaturan > Akademik > Kegiatan',
            'table' => array(
                array(
                    'field' => "ID_KEGIATAN",
                    'title' => "ID",
                    'sortable' => "ID_KEGIATAN",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_KEGIATAN' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_KEGIATAN",
                    'title' => "Nama Kegiatan",
                    'sortable' => "NAMA_KEGIATAN",
                    'show' => true,
                    'filter' => array(
                        'NAMA_KEGIATAN' => 'text'
                    )
                ),
                array(
                    'field' => "KETERANGAN_KEGIATAN",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_KEGIATAN",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_KEGIATAN' => 'text'
                    )
                ),
                array(
                    'field' => "KODE_EMIS_KEGIATAN",
                    'title' => "Kode EMIS",
                    'sortable' => "KODE_EMIS_KEGIATAN",
                    'show' => true,
                    'filter' => array(
                        'KODE_EMIS_KEGIATAN' => 'text'
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
        $data = $this->kegiatan->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->kegiatan->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->kegiatan->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->kegiatan->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->kegiatan->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->kegiatan->get_all();

        $this->output_handler->output_JSON($data);
    }

}
