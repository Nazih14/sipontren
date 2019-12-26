<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {
    
    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'data_psb_model' => 'data_psb',
            'kelompok_model' => 'kelompok',
            'jk_model' => 'jk',
            'kelas_model' => 'kelas',
            'akad_santri_model' => 'akad_santri',
        ));
        $this->auth->validation(array(1, 2));
    }

    public function index() {
        $data = array(
            'title' => 'Data PSB',
            'breadcrumb' => 'PSB > Data',
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
                    'field' => "NAMA_PKK",
                    'title' => "Nama Kelompok", 
                    'sortable' => "NAMA_PKK", 
                    'show' => true,
                    'filter' => array(
                        'ID_PKK' => 'select'
                    ),
                    'filterData' => $this->kelompok->get_all()
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
                    'field' => "TEMPAT_LAHIR_SANTRI",
                    'title' => "Tempat Lahir", 
                    'sortable' => "TEMPAT_LAHIR_SANTRI", 
                    'show' => true,
                    'filter' => array(
                        'TEMPAT_LAHIR_SANTRI' => 'text'
                    )
                ),
                array(
                    'field' => "TANGGAL_LAHIR_SANTRI_SHOW",
                    'title' => "Tanggal Lahir", 
                    'sortable' => "TANGGAL_LAHIR_SANTRI_SHOW", 
                    'show' => true,
                    'filter' => array(
                        'TANGGAL_LAHIR_SANTRI_SHOW' => 'text'
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
        $data = $this->data_psb->get_datatable();

        $this->output_handler->output_JSON($data);
    }
    
    public function form() {
        $data = array(
            'uri' => array(
                'kecamatan' => site_url('master_data/kecamatan/get_all'),
            ),
            'kelompok' => $this->kelompok->get_all(),
            'jk' => $this->jk->get_all(),
            'kelas' => $this->kelas->get_all(),
        );
        
        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->data_psb->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->data_psb->get_by_id($post[$this->primaryKey]);
        $data = (array) $result;
        
        $where = array(
            'TA_AS' => $this->session->userdata('ID_TA'),
            'SANTRI_AS' => $post[$this->primaryKey]
        );
        $data_akad = $this->akad_santri->get_rows_simple($where);
        
        $result = array();
        foreach ($data_akad as $detail) {
            $result[] = $detail->KELAS_AS;
        }
        $data['KEGIATAN_SANTRI'] = $result;

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $result = $this->data_psb->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->data_psb->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

}
