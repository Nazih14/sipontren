<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Penempatan_kamar extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'penempatan_kamar_model' => 'penempatan_kamar',
            'kelompok_model' => 'kelompok',
            'jk_model' => 'jk',
            'kamar_model' => 'kamar',
        ));
        $this->auth->validation(array(1, 3));
    }

    public function index() {
        $data = array(
            'title' => 'Penempatan Santri di Kamar',
            'breadcrumb' => 'Santri > Penempatan Kamar Santri',
            'kamar' => $this->kamar->get_all(),
            'table' => array(
                array(
                    'field' => "ID_SANTRI",
                    'title' => "ID",
                    'sortable' => "ID_SANTRI",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_SANTRI' => 'number'
                    ),
                ),
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
                    )
                ),
                array(
                    'field' => "NAMA_JK",
                    'title' => "JK",
                    'sortable' => "NAMA_JK",
                    'show' => true,
                    'filter' => array(
                        'JK_SANTRI' => 'text'
                    ),
                    'filterData' => $this->jk->get_all()
                ),
                array(
                    'field' => "TTL_SANTRI",
                    'title' => "TTL",
                    'sortable' => "TTL_SANTRI",
                    'show' => true,
                    'filter' => array(
                        'TTL_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "ALAMAT_LENGKAP_SANTRI",
                    'title' => "Alamat",
                    'sortable' => "ALAMAT_LENGKAP_SANTRI",
                    'show' => true,
                    'filter' => array(
                        'ALAMAT_LENGKAP_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "AYAH_NAMA_SANTRI",
                    'title' => "Nama Ayah",
                    'sortable' => "AYAH_NAMA_SANTRI",
                    'show' => true,
                    'filter' => array(
                        'AYAH_NAMA_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "ACTION",
                    'title' => "Aksi",
                ),
            )
        );
        $this->output_handler->output_JSON($data);
    }

    public function datatable_santri_no_kamar() {
        $data = $this->penempatan_kamar->get_datatable_santri_no_kamar();

        $this->output_handler->output_JSON($data);
    }

    public function datatable_santri_kamar() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->penempatan_kamar->get_datatable_santri_kamar($post['KAMAR_SK']);

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'uri' => array(
                'kecamatan' => site_url('master_data/kecamatan/get_all'),
            ),
            'kelompok' => $this->kelompok->get_all(),
            'jk' => $this->jk->get_all(),
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->penempatan_kamar->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->penempatan_kamar->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function prosesSantri() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->penempatan_kamar->prosesSantri($data);
        if ($data['ACTION'] == 'set')
            $message = 'ditempatkan di kamar';
        elseif ($data['ACTION'] == 'remove')
            $message = 'dihapus dari kamar';

        $this->output_handler->output_JSON($result, $message);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->penempatan_kamar->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->penempatan_kamar->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

}
