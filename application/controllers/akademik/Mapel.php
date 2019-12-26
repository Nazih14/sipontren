<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mapel extends CI_Controller {

    var $primaryKey = 'ID_MAPEL';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'mapel_model' => 'mapel',
            'kelas_model' => 'kelas',
        ));
        $this->auth->validation(array(1, 4));
    }

    public function index() {
        $data = array(
            'title' => 'Data Mapel',
            'breadcrumb' => 'Akademik > Mapel',
            'table' => array(
                array(
                    'field' => "ID_MAPEL",
                    'title' => "ID",
                    'sortable' => "ID_MAPEL",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_MAPEL' => 'number'
                    )
                ),
                array(
                    'field' => "KELAS_KEGIATAN",
                    'title' => "Nama Kelas",
                    'sortable' => "KELAS_KEGIATAN",
                    'show' => true,
                    'filter' => array(
                        'ID_KELAS' => 'select'
                    ),
                    'filterData' => $this->kelas->get_all()
                ),
                array(
                    'field' => "NAMA_MAPEL",
                    'title' => "Nama Mapel",
                    'sortable' => "NAMA_MAPEL",
                    'show' => true,
                    'filter' => array(
                        'NAMA_MAPEL' => 'text'
                    )
                ),
                array(
                    'field' => "KETERANGAN_MAPEL",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_MAPEL",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_MAPEL' => 'text'
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
        $data = $this->mapel->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'dataKELAS_MAPEL' => $this->kelas->get_all()
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->mapel->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->mapel->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->mapel->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->mapel->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->mapel->get_all();

        $this->output_handler->output_JSON($data);
    }

}
