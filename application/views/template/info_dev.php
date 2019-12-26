<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->auth->validation();
?>
<md-bottom-sheet class="md-list md-has-header pt-5 pr-4 pl-4" ng-controller="aboutController">
    <h1 class="md-title">Tentang Aplikasi</h1>
    <p class="md-body-1">
        Aplikasi <strong>SIPONTREN (Sistem Informasi Pondok Pesantren)</strong> ini dibuat dengan menggunakan <a href="https://www.mysql.com/" target="_blank">MySQL</a>, <a href="https://codeigniter.com/" target="_blank">CodeIgniter</a>, <a href="https://angularjs.org/" target="_blank">AngularJS</a>, dan <a href="https://material.angularjs.org" target="_blank">Angular Material</a>. Aplikasi ini bersifat gratis dan diperbolehkan dimodifikasi atau ditiru sesuai dengan kebutuhan Pondok Pesantren. Jika kebutuhan Pondok Pesantren melebihi dari aplikasi ini, silahkan menghubungi pengembang melalui email <a href="mailto:rohmad.ew@gmail.com">rohmad.ew@gmail.com</a> untuk pengembangan secara khusus.<br><br>Terimakasih kepada Dr. Agus Zainal Arifin, S.Kom, M.Kom, Pondok Pesantren Darul Falah Jekulo - Kudus (KH Ahmad Badawi dan KH Muhammad Alamul Yaqin), dan PW RMI NU Jawa Tengah atas dukungannya. <br><br><strong>SIPONTREN v{{version}}</strong>
    </p>
</md-bottom-sheet> 

<!--

||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
||                                                                                  ||
||          ###### ## ###### ###### ###   ## ###### #####  ###### ###   ##          ||
||          ##     ## ##  ## ##  ## ####  ##   ##   ##  ## ##     ####  ##          ||
||          ###### ## ###### ##  ## ## ## ##   ##   #####  ####   ## ## ##          ||
||              ## ## ##     ##  ## ##  ####   ##   ## ##  ##     ##  ####          ||
||          ###### ## ##     ###### ##   ###   ##   ##  ## ###### ##   ###          ||
||                                                                                  ||
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
||                                                                                  ||
||                       Dibuat oleh: rohmad.ew@gmail.com                           ||
||                       Dikembangkan oleh: nazihbopas@gmail.com                    ||
||                                                                                  ||
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
||                                                                                  ||
||                         DDDDD    AA   RRRRR  II                                  ||
||                         DD  DD  AAAA  RR  RR II                                  ||
||                         DD  DD AA  AA RRRRR  II                                  ||
||                         DD  DD AAAAAA RR RR  II                                  ||
||                         DDDDD  AA  AA RR  RR II                                  ||
||                                                                                  ||
||                SSSSSS   AA   NNN   NN TTTTTT RRRRR  II                           ||
||                SS      AAAA  NNNN  NN   TT   RR  RR II                           ||
||                SSSSSS AA  AA NN NN NN   TT   RRRRR  II                           ||
||                    SS AAAAAA NN  NNNN   TT   RR RR  II                           ||
||                SSSSSS AA  AA NN   NNN   TT   RR  RR II                           ||
||                                                                                  ||
||                 UU  UU NNN   NN TTTTTT UU  UU KK  KK                             ||
||                 UU  UU NNNN  NN   TT   UU  UU KK KK                              ||
||                 UU  UU NN NN NN   TT   UU  UU KKKK                               ||
||                 UU  UU NN  NNNN   TT   UU  UU KK KK                              ||
||                 UUUUUU NN   NNN   TT   UUUUUU KK  KK                             ||
||                                                                                  ||
||                NNN   NN EEEEEE GGGGGG EEEEEE RRRRR  II                           ||
||                NNNN  NN EE     GG     EE     RR  RR II                           ||
||                NN NN NN EEEE   GG GGG EEEE   RRRRR  II                           ||
||                NN  NNNN EE     GG  GG EE     RR RR  II                           ||
||                NN   NNN EEEEEE GGGGGG EEEEEE RR  RR II                           ||
||                                                                                  ||
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
||                                                                                  ||
||    Ya Lal Wathon Ya Lal Wathon Ya Lal Wathon                                     ||
||    Hubbul Wathon minal Iman                                                      ||
||    Wala Takun minal Hirman                                                       ||
||    Inhadlu Alal Wathon                                                           ||
||                                                                                  ||
||    Indonesia Biladi                                                              ||
||    Anta ‘Unwanul Fakhoma                                                         ||
||    Kullu May Ya’tika Yauma                                                       ||
||    Thomihay Yalqo Himama                                                         ||
||                                                                                  ||
||    Pusaka Hati Wahai Tanah Airku                                                 ||
||    Cintamu dalam Imanku                                                          ||
||    Jangan Halangkan Nasibmu                                                      ||
||    Bangkitlah Hai Bangsaku                                                       ||
||                                                                                  ||
||    Pusaka Hati Wahai Tanah Airku                                                 ||
||    Cintamu dalam Imanku                                                          ||
||    Jangan Halangkan Nasibmu                                                      ||
||    Bangkitlah Hai Bangsaku                                                       ||
||                                                                                  ||
||    Indonesia Negeriku                                                            ||
||    Engkau Panji Martabatku                                                       ||
||    Siapa Datang Mengancammu                                                      ||
||    Kan Binasa di bawah durimu                                                    ||
||                                                                                  ||
||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||


-->