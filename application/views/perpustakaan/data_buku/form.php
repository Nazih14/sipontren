<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Buku';
$controller = 'perpusBuku';
$data = array(
    array(
        'type' => 'select',
        'field' => 'JENIS_BUKU',
        'label' => 'Jenis Buku'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_BUKU',
        'label' => 'Kode Buku'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_BUKU',
        'label' => 'Nama Buku'
    ),
    array(
        'type' => 'text',
        'field' => 'PENGARANG_BUKU',
        'label' => 'Nama Pengarang'
    ),
    array(
        'type' => 'text',
        'field' => 'PENERBIT_BUKU',
        'label' => 'Nama Penertbit'
    ),
    array(
        'type' => 'number',
        'field' => 'STOK_BUKU',
        'label' => 'Stok Buku'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_BUKU',
        'label' => 'Keterangan Buku',
        'required' => false
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>