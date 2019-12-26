<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rapor extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'rapor_model' => 'rapor',
            'kelas_model' => 'kelas',
            'kamar_model' => 'kamar',
            'nilai_model' => 'nilai',
            'rombel_model' => 'rombel',
            'kegiatan_model' => 'kegiatan',
            'akad_santri_model' => 'akad_santri',
        ));
        $this->auth->validation(array(1, 4));
    }

    public function index() {
        $data = array(
            'title' => 'Rapor Santri',
            'breadcrumb' => 'Akademik > Rapor',
            'table' => array(
                'kelas' => array(
                    array(
                        'field' => "NAMA_KELAS",
                        'title' => "Kelas",
                        'sortable' => "NAMA_KELAS",
                        'show' => true,
                        'filter' => array(
                            'NAMA_KELAS' => 'text'
                        ),
                    ),
                    array(
                        'field' => "NAMA_KEGIATAN",
                        'title' => "Kegiatan",
                        'sortable' => "NAMA_KEGIATAN",
                        'show' => true,
                        'filter' => array(
                            'ID_KEGIATAN' => 'select'
                        ),
                        'filterData' => $this->kegiatan->get_all()
                    ),
                    array(
                        'field' => "ACTION",
                        'title' => "Aksi",
                    ),
                ),
                'santri' => array(
                    array(
                        'field' => "NIS_SANTRI",
                        'title' => "NIS",
                        'sortable' => "NIS_SANTRI",
                        'show' => true,
                        'filter' => array(
                            'NIS_SANTRI' => 'text'
                        ),
                    ),
                    array(
                        'field' => "NAMA_SANTRI",
                        'title' => "Nama Santri",
                        'sortable' => "NAMA_SANTRI",
                        'show' => true,
                        'filter' => array(
                            'NAMA_SANTRI' => 'text'
                        ),
                    ),
                    array(
                        'field' => "NAMA_ROMBEL",
                        'title' => "Rombel",
                        'sortable' => "NAMA_ROMBEL",
                        'show' => true,
                        'filter' => array(
                            'ID_ROMBEL' => 'select'
                        ),
                        'filterData' => $this->rombel->get_all()
                    ),
                    array(
                        'field' => "NAMA_UST",
                        'title' => "Wali Kelas",
                        'sortable' => "NAMA_UST",
                        'show' => true,
                        'filter' => array(
                            'NAMA_UST' => 'text'
                        ),
                    ),
                    array(
                        'field' => "ACTION",
                        'title' => "Aksi",
                    ),
                ),
                'nilai' => array(
                    array(
                        'field' => "KODE_MAPEL",
                        'title' => "Kode",
                        'sortable' => "KODE_MAPEL",
                        'show' => true,
                        'filter' => array(
                            'KODE_MAPEL' => 'text'
                        ),
                    ),
                    array(
                        'field' => "NAMA_MAPEL",
                        'title' => "Nama Mapel",
                        'sortable' => "NAMA_MAPEL",
                        'show' => true,
                        'filter' => array(
                            'NAMA_MAPEL' => 'text'
                        ),
                    ),
                    array(
                        'field' => "NAMA_UST",
                        'title' => "Ustadz",
                        'sortable' => "NAMA_UST",
                        'show' => true,
                        'filter' => array(
                            'NAMA_UST' => 'text'
                        ),
                    ),
                    array(
                        'field' => "NILAI_NILAI",
                        'title' => "Nilai",
                        'sortable' => "NILAI_NILAI",
                        'show' => true,
                        'filter' => array(
                            'NILAI_NILAI' => 'text'
                        ),
                    ),
                )
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function get_datatable_kelas() {
        $data = $this->kelas->get_datatable();

        $this->output_handler->output_JSON($data);
    }

    public function get_datatable_santri() {
        $post = json_decode(file_get_contents('php://input'), true);

        $where = 'KELAS_AS=' . $post['ID_KELAS'] . ' AND ID_ROMBEL IS NOT NULL';
        $data = $this->akad_santri->get_datatable($where);

        $this->output_handler->output_JSON($data);
    }

    public function get_datatable_nilai() {
        $post = json_decode(file_get_contents('php://input'), true);

        $where = 'SANTRI_NILAI=' . $post['ID_AS'];
        $data = $this->nilai->get_datatable($where);

        $this->output_handler->output_JSON($data);
    }

}
