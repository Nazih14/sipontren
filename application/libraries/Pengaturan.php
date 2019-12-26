<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Pengaturan {

    function __construct() {
        $this->CI = & get_instance();

        $this->CI->load->model('pengaturan_model', 'data_pengaturan');
    }

    public function getNamaApp() {
        return $this->CI->data_pengaturan->get_by_id('nama_aplikasi');
    }

    public function getVersiApp() {
        return $this->CI->data_pengaturan->get_by_id('versi_parent').'.'.$this->CI->data_pengaturan->get_by_id('versi_child');
    }

    public function getMotto() {
        return $this->CI->data_pengaturan->get_by_id('motto');
    }

    public function getNamaYayasan() {
        return $this->CI->data_pengaturan->get_by_id('nama_yayasan');
    }

    public function getNamaLembaga() {
        return $this->CI->data_pengaturan->get_by_id('nama_lembaga');
    }

    public function getNamaLembagaSingk() {
        return $this->CI->data_pengaturan->get_by_id('nama_lembaga_singkatan');
    }

    public function getAlamat() {
        return $this->CI->data_pengaturan->get_by_id('alamat');
    }

    public function getDesa() {
        return $this->CI->data_pengaturan->get_by_id('desa');
    }

    public function getKecamatan() {
        return $this->CI->data_pengaturan->get_by_id('kecamatan');
    }

    public function getKabupaten() {
        return $this->CI->data_pengaturan->get_by_id('kabupaten');
    }

    public function getProvinsi() {
        return $this->CI->data_pengaturan->get_by_id('provinsi');
    }

    public function getNegara() {
        return $this->CI->data_pengaturan->get_by_id('negara');
    }

    public function getKodepos() {
        return $this->CI->data_pengaturan->get_by_id('kode_pos');
    }

    public function getTelp() {
        return $this->CI->data_pengaturan->get_by_id('telp');
    }

    public function getFax() {
        return $this->CI->data_pengaturan->get_by_id('fax');
    }

    public function getWebsite() {
        return $this->CI->data_pengaturan->get_by_id('website');
    }

    public function getEmail() {
        return $this->CI->data_pengaturan->get_by_id('email');
    }

    public function getLogo() {
        return $this->CI->data_pengaturan->get_by_id('logo');
    }

    public function getUjianPSB() {
        return $this->CI->data_pengaturan->get_by_id('psb_ujian');
    }

    public function getTahunBerdiri() {
        return $this->CI->data_pengaturan->get_by_id('tahun_berdiri');
    }

    public function getUjianCawu() {
        return $this->CI->data_pengaturan->get_by_id('cawu_ujian');
    }

    public function getJedaPercobaanLogin() {
        return $this->CI->data_pengaturan->get_by_id('jeda_percobaan_login');
    }

    public function getLamaLogTersimpan() {
        return $this->CI->data_pengaturan->get_by_id('lama_log_tersimpan');
    }

    public function getBanyakPercobaanLogin() {
        return $this->CI->data_pengaturan->get_by_id('banyak_percobaan_login');
    }

    public function getNomorInduk($dept) {
        $data = json_decode($this->CI->data_pengaturan->get_by_id('nomor_induk_terakhir'), TRUE);

        foreach ($data as $jenjang => $nomor) {
            if ($dept == $jenjang) return $nomor;
        }
    }

    public function getNomorPokok($jenjang, $angkatan, $nomor) {
        if (strlen($nomor) > 4) return $jenjang.$angkatan.$nomor;
        else return $jenjang.$angkatan.'-'.$nomor;
    }

    public function setNomorTerakhir($dept, $nomor_terakhir) {
        $data = json_decode($this->CI->data_pengaturan->get_by_id('nomor_induk_terakhir'), TRUE);
        $data_tingkat = array();

        foreach ($data as $jenjang => $nomor) {
            if ($dept == $jenjang) $data_tingkat[$jenjang] = $nomor_terakhir;
            else $data_tingkat[$jenjang] = $nomor;
        }

        return $this->CI->data_pengaturan->update('nomor_induk_terakhir', json_encode($data_tingkat));
    }

    public function getNomorIjasah() {
        return $this->CI->data_pengaturan->get_by_id('nomor_ijasah');
    }

    public function setNomorIjasah($value) {
        return $this->CI->data_pengaturan->update('nomor_ijasah', $value);
    }

    public function getBulanHijriyah() {
        $bulan = array(
            '1' => 'Muharrom',
            '2' => 'Shofar',
            '3' => 'Rabiul Awal',
            '4' => 'Rabiul Akhir',
            '5' => 'Jumadil Awal',
            '6' => 'Jumadil Akhir',
            '7' => 'Rojab',
            '8' => 'Sya\'ban',
            '9' => 'Romadhon',
            '10' => 'Syawal',
            '11' => 'Dzulqo\'dah',
            '12' => 'Dzulhijjah',
        );

        return $bulan;
    }
}
