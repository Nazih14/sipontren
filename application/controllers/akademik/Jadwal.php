<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal extends CI_Controller {

    var $primaryKey = 'ID_AJ';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jadwal_model' => 'jadwal',
            'kelas_model' => 'kelas',
            'mapel_model' => 'mapel',
            'ustadz_model' => 'ustadz',
        ));
        $this->auth->validation(array(1, 4));
    }

    public function index() {
        $data = array(
            'title' => 'Data Jadwal',
            'breadcrumb' => 'Akademik > Jadwal',
            'table' => array(
                array(
                    'field' => "ID_AJ",
                    'title' => "ID",
                    'sortable' => "ID_AJ",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_AJ' => 'number'
                    )
                ),
                array(
                    'field' => "NIP_UST",
                    'title' => "NIP",
                    'sortable' => "NIP_UST",
                    'show' => true,
                    'filter' => array(
                        'NIP_UST' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_UST_SHOW",
                    'title' => "Nama Ustadz",
                    'sortable' => "NAMA_UST_SHOW",
                    'show' => true,
                    'filter' => array(
                        'NAMA_UST' => 'text'
                    ),
                ),
                array(
                    'field' => "KODE_MAPEL",
                    'title' => "Kode Mapel",
                    'sortable' => "KODE_MAPEL",
                    'show' => true,
                    'filter' => array(
                        'KODE_MAPEL' => 'text'
                    )
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
                    'field' => "NAMA_KELAS_SHOW",
                    'title' => "Nama Kelas",
                    'sortable' => "NAMA_KELAS_SHOW",
                    'show' => true,
                    'filter' => array(
                        'ID_KELAS' => 'select'
                    ),
                    'filterData' => $this->kelas->get_all()
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
        $data = $this->jadwal->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'ustadz' => $this->ustadz->get_all(),
            'mapel' => $this->mapel->get_all(),
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $post = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->jadwal->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->jadwal->get_data_form($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->jadwal->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->jadwal->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->jadwal->get_all();

        $this->output_handler->output_JSON($data);
    }

}
