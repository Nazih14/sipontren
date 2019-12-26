<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();
?>
<md-bottom-sheet class="md-list md-has-header" ng-controller="aboutController">
    <h1 class="md-title">Tentang Aplikasi</h1>
    <p class="md-body-1">
        Aplikasi <strong>SIPONTREN (Sistem Informasi Pondok Pesantren)</strong> ini dibuat dengan menggunakan <a href="https://www.mysql.com/" target="_blank">MySQL</a>, <a href="https://codeigniter.com/" target="_blank">CodeIgniter</a>, <a href="https://angularjs.org/" target="_blank">AngularJS</a>, dan <a href="https://material.angularjs.org" target="_blank">Angular Material</a>. Aplikasi ini bersifat gratis dan diperbolehkan dimodifikasi atau ditiru sesuai dengan kebutuhan Pondok Pesantren. Jika kebutuhan Pondok Pesantren melebihi dari aplikasi ini, silahkan menghubungi pengembang melalui email <a href="mailto:rohmad.ew@gmail.com">rohmad.ew@gmail.com</a> untuk pengembangan secara khusus.<br><br>Terimakasih kepada Dr. Agus Zainal Arifin, S.Kom, M.Kom, Pondok Pesantren Darul Falah Jekulo - Kudus (KH Ahmad Badawi dan KH Muhammad Alamul Yaqin), dan PW RMI NU Jawa Tengah atas dukungannya. <br><br><strong>SIPONTREN v{{version}}</strong>
    </p>
</md-bottom-sheet> 