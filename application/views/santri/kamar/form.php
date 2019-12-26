<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Kamar';
$controller = 'santriKamar';
$data = array(
    array(
        'type' => 'select',
        'field' => 'GEDUNG_KAMAR',
        'label' => 'Gedung'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_KAMAR',
        'label' => 'Nama Kamar'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_KAMAR',
        'label' => 'Keterangan Kamar'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>