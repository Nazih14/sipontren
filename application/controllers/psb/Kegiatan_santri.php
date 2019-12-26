<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan_santri extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'kegiatan_santri_model' => 'kegiatan_santri',
            'kelas_model' => 'kelas',
            'jk_model' => 'jk',
            'kamar_model' => 'kamar',
        ));
        $this->auth->validation(array(1, 2));
    }

    public function index() {
        $data = array(
            'title' => 'Penentuan Kelas Santri',
            'breadcrumb' => 'Santri > Penentuan Kelas Santri',
            'kelas' => $this->kelas->get_all(),
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

    public function datatable_santri_no_kegiatan() {
        $post = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->kegiatan_santri->get_datatable_santri_no_kegiatan($post['KELAS_AS']);

        $this->output_handler->output_JSON($data);
    }

    public function datatable_santri_kegiatan() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->kegiatan_santri->get_datatable_santri_kegiatan($post['KELAS_AS']);

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->kegiatan_santri->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->kegiatan_santri->get_by_id($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function prosesSantri() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->kegiatan_santri->prosesSantri($data);
        if ($data['ACTION'] == 'set')
            $message = 'ditempatkan di kelas';
        elseif ($data['ACTION'] == 'remove')
            $message = 'dihapus dari kelas';

        $this->output_handler->output_JSON($result, $message);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->kegiatan_santri->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->kegiatan_santri->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

}
