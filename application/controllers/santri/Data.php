<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {
    
    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'data_santri_model' => 'data_santri',
            'jk_model' => 'jk',
            'kondisi_model' => 'kondisi',
            'pekerjaan_model' => 'pekerjaan',
            'masterdata_model' => 'masterdata',
            'status_hidup_model' => 'hidup',
            'jenjang_pendidikan_model' => 'pendidikan',
            'kamar_model' => 'kamar',
            'penghasilan_model' => 'penghasilan',
            'asal_santri_model' => 'asal_santri',
        ));
        $this->auth->validation(array(1, 3));
    }

    public function index() {
        $data = array(
            'title' => 'Data Santri',
            'breadcrumb' => 'Santri > Data',
            'wide' => true,
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
                        'JK_SANTRI' => 'select'
                    ),
                    'filterData' => $this->jk->get_all()
                ),
                array(
                    'field' => "ANGKATAN_SANTRI",
                    'title' => "Angkatan", 
                    'sortable' => "ANGKATAN_SANTRI", 
                    'show' => true,
                    'filter' => array(
                        'ANGKATAN_SANTRI' => 'text'
                    )
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
                    'field' => "NOHP_SANTRI",
                    'title' => "No HP", 
                    'sortable' => "NOHP_SANTRI", 
                    'show' => true,
                    'filter' => array(
                        'NOHP_SANTRI' => 'text'
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
                    'field' => "KAMAR_SANTRI",
                    'title' => "Kamar", 
                    'sortable' => "KAMAR_SANTRI", 
                    'show' => true,
                    'filter' => array(
                        'ID_KAMAR' => 'select'
                    ),
                    'filterData' => $this->kamar->get_all()
                ),
                array(
                    'field' => "ACTION",
                    'title' => "Aksi", 
                    'actions' => array(
                        array(
                            'title' => 'Ubah Data Diri',
                            'update' => true
                        ),
                        array(
                            'title' => 'Ubah Data Ayah',
                            'form' => 'form_ayah'
                        ),
                        array(
                            'title' => 'Ubah Data Ibu',
                            'form' => 'form_ibu'
                        ),
                        array(
                            'title' => 'Ubah Data Wali',
                            'form' => 'form_wali'
                        ),
                        array(
                            'title' => 'Ubah Data Orangtua',
                            'form' => 'form_orangtua'
                        ),
                    )
                ),
            )
        );
        $this->output_handler->output_JSON($data);
    }
    
    public function datatable() {
        $data = $this->data_santri->get_datatable();

        $this->output_handler->output_JSON($data);
    }
    
    public function form() {
        $data = array(
            'uri' => array(
                'kecamatan' => site_url('master_data/kecamatan/get_all'),
            ),
            'JK' => $this->jk->get_all(),
            'AGAMA' => $this->masterdata->get_agama(),
            'SUKU' => $this->masterdata->get_suku(),
            'KONDISI' => $this->kondisi->get_all(),
            'GOL_DARAH' => $this->masterdata->get_gol_darah(),
            'HIDUP_SANTRI' => $this->hidup->get_all(),
            'PENDIDIKAN_SANTRI' => $this->pendidikan->get_all(),
            'PEKERJAAN_SANTRI' => $this->pekerjaan->get_all(),
            'HUBUNGAN_SANTRI' => $this->masterdata->get_hubungan(),
            'ORTU_PENGHASILAN_SANTRI' => $this->penghasilan->get_all(),
            'STATUS_ASAL_SANTRI' => $this->asal_santri->get_all(),
        );
        
        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->data_santri->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->data_santri->get_data_form($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $result = $this->data_santri->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->data_santri->get_all();

        $this->output_handler->output_JSON($data);
    }

}
