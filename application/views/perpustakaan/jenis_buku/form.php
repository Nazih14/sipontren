<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Jenis Buku';
$controller = 'perpusJenis';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_PJB',
        'label' => 'Nama Jenis Buku'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>