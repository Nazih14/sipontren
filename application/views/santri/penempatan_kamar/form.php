<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Penempatan Kamar Santri';
$message = 'Menambahkan data santri baru dapat dilakukan di menu <strong>PSB</strong> -> <strong>DATA</strong><br>Menambahkan data kamar baru dapat dilakukan di menu <strong>SANTRI</strong> -> <strong>KAMAR</strong>';

$this->output_handler->dialog_info($title, $message);

?>