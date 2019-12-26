<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Kegiatan';
$controller = 'akadKegiatan';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_KEGIATAN',
        'label' => 'Nama Kegiatan'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_KEGIATAN',
        'label' => 'Keterangan Kegiatan'
    ),
    array(
        'type' => 'text',
        'field' => 'KODE_EMIS_KEGIATAN',
        'label' => 'Kode EMIS'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>