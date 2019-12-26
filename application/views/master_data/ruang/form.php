<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Ruang';
$controller = 'akadRuang';
$data = array(
    array(
        'type' => 'select',
        'field' => 'GEDUNG_RUANG',
        'label' => 'Gedung'
    ),
    array(
        'type' => 'text',
        'field' => 'NAMA_RUANG',
        'label' => 'Nama Ruang'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_RUANG',
        'label' => 'Keterangan Ruang'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>