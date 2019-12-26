<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Gedung';
$controller = 'akadGedung';
$data = array(
    array(
        'type' => 'text',
        'field' => 'NAMA_GEDUNG',
        'label' => 'Nama Gedung'
    ),
    array(
        'type' => 'text',
        'field' => 'KETERANGAN_GEDUNG',
        'label' => 'Keterangan Gedung'
    ),
);

$this->output_handler->dialog_form($title, $controller, $data);

?>