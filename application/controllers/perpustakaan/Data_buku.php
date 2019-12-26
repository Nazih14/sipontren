<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Data_buku extends CI_Controller {

    var $primaryKey = 'ID_BUKU';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'data_buku_model' => 'buku',
            'jenis_buku_model' => 'jenis',
        ));
        $this->auth->validation(array(1, 6));
    }

    public function index() {
        $data = array(
            'title' => 'Data Buku',
            'breadcrumb' => 'Perpustakaan > Buku',
            'table' => array(
                array(
                    'field' => "ID_BUKU",
                    'title' => "ID",
                    'sortable' => "ID_BUKU",
                    'show' => FALSE,
                    'filter' => array(
                        'ID_BUKU' => 'number'
                    )
                ),
                array(
                    'field' => "NAMA_PJB",
                    'title' => "Jenis Buku",
                    'sortable' => "NAMA_PJB",
                    'show' => true,
                    'filter' => array(
                        'ID_PJB' => 'select'
                    ),
                    'filterData' => $this->jenis->get_all()
                ),
                array(
                    'field' => "KODE_BUKU",
                    'title' => "Kode Buku",
                    'sortable' => "KODE_BUKU",
                    'show' => true,
                    'filter' => array(
                        'KODE_BUKU' => 'text'
                    )
                ),
                array(
                    'field' => "NAMA_BUKU",
                    'title' => "Nama Buku",
                    'sortable' => "NAMA_BUKU",
                    'show' => true,
                    'filter' => array(
                        'NAMA_BUKU' => 'text'
                    )
                ),
                array(
                    'field' => "PENGARANG_BUKU",
                    'title' => "Pengarang",
                    'sortable' => "PENGARANG_BUKU",
                    'show' => true,
                    'filter' => array(
                        'PENGARANG_BUKU' => 'text'
                    )
                ),
                array(
                    'field' => "PENERBIT_BUKU",
                    'title' => "Penerbit",
                    'sortable' => "PENERBIT_BUKU",
                    'show' => true,
                    'filter' => array(
                        'PENERBIT_BUKU' => 'text'
                    )
                ),
                array(
                    'field' => "STOK_BUKU",
                    'title' => "Stok",
                    'sortable' => "STOK_BUKU",
                    'show' => true,
                    'filter' => array(
                        'STOK_BUKU' => 'number'
                    )
                ),
                array(
                    'field' => "KETERANGAN_BUKU",
                    'title' => "Keterangan",
                    'sortable' => "KETERANGAN_BUKU",
                    'show' => true,
                    'filter' => array(
                        'KETERANGAN_BUKU' => 'number'
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
        $post = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->buku->get_datatable($post);

        $this->output_handler->output_JSON($data);
    }

    public function form() {
        $data = array(
            'jenis' => $this->jenis->get_all()
        );

        $this->output_handler->output_JSON($data);
    }

    public function data() {
        $data = $this->buku->get_datatables();

        $this->output_handler->output_JSON($data);
    }

    public function view() {
        $post = json_decode(file_get_contents('php://input'), true);

        $data = $this->buku->get_form_data($post[$this->primaryKey]);

        $this->output_handler->output_JSON($data);
    }

    public function save() {
        $data = json_decode(file_get_contents('php://input'), true);

        $result = $this->buku->save($data);

        if (isset($data[$this->primaryKey]))
            $message = 'diubah';
        else
            $message = 'dibuat';

        $this->output_handler->output_JSON($result, $message);
    }

    public function delete() {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = $this->buku->delete($post[$this->primaryKey]);
        $message = 'dihapus';

        $this->output_handler->output_JSON($result, $message);
    }

    public function get_all() {
        $data = $this->buku->get_all();

        $this->output_handler->output_JSON($data);
    }

}
