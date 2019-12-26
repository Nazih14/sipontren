<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();

$title = 'Penempatan Kamar Santri';
$message = 'Menambahkan data santri baru dapat dilakukan di menu PSB -> DATA<br>Menambahkan data kelas dapat dilakukan di menu Pengaturan -> Master Data -> Kelas.<br>';

$this->output_handler->dialog_info($title, $message);

?>