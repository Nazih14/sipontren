<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Jenis Pelanggaran';
$controller = 'pelanggaranJenis';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_PJS',
        'label' => 'Jenis Pelanggaran'
    ),
    array(
        'type' => 'number',
        'field' => 'POIN_PJS',
        'label' => 'Poin'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>