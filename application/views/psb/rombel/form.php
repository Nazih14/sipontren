<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Penempatan Rombel Santri';
$message = 'Menambahkan data santri baru dapat dilakukan di menu PSB -> DATA<br>Menambahkan data rombel dapat dilakukan di menu Pengaturan -> Master Data -> Rombongan Belajar.<br>';

$this->output_handler->dialog_info($title, $message);

?>