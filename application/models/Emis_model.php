<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Emis_model extends CI_Model {

    var $table = 'md_santri';
    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
    }

    private function _get_table($select = true) {
        $this->db->select(''
                . 'NIS_SANTRI AS NIS'
                . ',NIK_SANTRI AS NIK'
                . ',NAMA_SANTRI AS NAMA'
                . ',PANGGILAN_SANTRI AS PANGGILAN'
                . ',ANGKATAN_SANTRI AS ANGKATAN'
                . ',SUKU_SANTRI AS KODE_SUKU'
                . ',NAMA_SUKU AS NAMA_SUKU'
                . ',KODE_EMIS_AGAMA AS KODE_AGAMA'
                . ',NAMA_AGAMA AS NAMA_AGAMA'
                . ',KONDISI_SANTRI AS KODE_KONDISI'
                . ',NAMA_KONDISI AS NAMA_KONDISI'
                . ',KODE_EMIS_JK AS KODE_JENIS_KELAMIN'
                . ',NAMA_JK AS NAMA_JENIS_KELAMIN'
                . ',TEMPAT_LAHIR_SANTRI AS TEMPAT_LAHIR'
                . ',TANGGAL_LAHIR_SANTRI AS TANGGAL_LAHIR'
                . ',ANAK_KE_SANTRI AS ANAK_KE'
                . ',JUMLAH_SDR_SANTRI AS JUMLAH_SAUDARA'
                . ',BERAT_SANTRI AS BERAT_BADAN'
                . ',TINGGI_SANTRI AS TINGGI_BADAN'
                . ',NAMA_DARAH AS GOLONGAN_DARAH'
                . ',ALAMAT_SANTRI AS ALAMAT'
                . ',ID_KEC AS KODE_KECAMATAN'
                . ',NAMA_KEC AS NAMA_KECAMATAN'
                . ',ID_KAB AS KODE_KABUPATEN'
                . ',NAMA_KAB AS NAMA_KABUPATEN'
                . ',ID_PROV AS KODE_PROVINSI'
                . ',NAMA_PROV AS NAMA_PROVINSI'
                . ',KODE_POS_SANTRI AS KODEPOS'
                . ',KODE_EMIS_TEMTING AS KODE_TEMPAT_TINGGAL'
                . ',NAMA_TEMTING AS NAMA_TEMPAT_TINGGAL'
                . ',RIWAYAT_KESEHATAN_SANTRI AS RIWAYAT_KESEHATAN'
                . ',NO_IJASAH_SANTRI AS NO_IJASAH'
                . ',TANGGAL_IJASAH_SANTRI AS TANGGAL_IJASAH'
                . ',AYAH_NIK_SANTRI AS NIK_AYAH'
                . ',AYAH_NAMA_SANTRI AS NAMA_AYAH'
                . ',ayahoh.KODE_EMIS_SO AS KODE_STATUS_HIDUP_AYAH'
                . ',ayahoh.NAMA_SO AS NAMA_STATUS_HIDUP_AYAH'
                . ',AYAH_TEMPAT_LAHIR_SANTRI AS TEMPAT_LAHIR_AYAH'
                . ',AYAH_TANGGAL_LAHIR_SANTRI AS TANGGAL_LAHIR_AYAH'
                . ',ayahjp.KODE_EMIS_JP AS KODE_JENJANG_PENDIDIKAN_AYAH'
                . ',ayahjp.NAMA_JP AS NAMA_JENJANG_PENDIDIKAN_AYAH'
                . ',ayahp.KODE_EMIS_JENPEK AS KODE_JENIS_PEKERJAAN_AYAH'
                . ',ayahp.NAMA_JENPEK AS NAMA_JENIS_PEKERJAAN_AYAH'
                . ',IBU_NIK_SANTRI AS NIK_IBU'
                . ',IBU_NAMA_SANTRI AS NAMA_IBU'
                . ',ibuoh.KODE_EMIS_SO AS KODE_STATUS_HIDUP_IBU'
                . ',ibuoh.NAMA_SO AS NAMA_STATUS_HIDUP_IBU'
                . ',IBU_TEMPAT_LAHIR_SANTRI AS TEMPAT_LAHIR_IBU'
                . ',IBU_TANGGAL_LAHIR_SANTRI AS TANGGAL_LAHIR_IBU'
                . ',ibujp.KODE_EMIS_JP AS KODE_JENJANG_PENDIDIKAN_IBU'
                . ',ibujp.NAMA_JP AS NAMA_JENJANG_PENDIDIKAN_IBU'
                . ',ibup.KODE_EMIS_JENPEK AS KODE_JENIS_PEKERJAAN_IBU'
                . ',ibup.NAMA_JENPEK AS NAMA_JENIS_PEKERJAAN_IBU'
                . ',WALI_NIK_SANTRI AS NIK_WALI'
                . ',WALI_NAMA_SANTRI AS NAMA_WALI'
                . ',KODE_EMIS_HUB AS KODE_HUBUNGAN_WALI'
                . ',NAMA_HUB AS NAMA_HUBUNGAN_WALI'
                . ',walijp.ID_JP AS KODE_JENJANG_PENDIDIKAN_WALI'
                . ',walijp.NAMA_JP AS NAMA_JENJANG_PENDIDIKAN_WALI'
                . ',walip.ID_JENPEK AS KODE_JENIS_PEKERJAAN_WALI'
                . ',walip.NAMA_JENPEK AS NAMA_JENIS_PEKERJAAN_WALI'
                . ',KODE_EMIS_HASIL AS KODE_PENGHASILAN_ORANG_TUA'
                . ',NAMA_HASIL AS NAMA_PENGHASILAN_ORANG_TUA'
                . ',ORTU_NOHP1_SANTRI AS NO_HP_ORANG_TUA_1'
                . ',ORTU_NOHP2_SANTRI AS NO_HP_ORANG_TUA_2'
                . ',ORTU_NOHP3_SANTRI AS NO_HP_ORANG_TUA_3'
                . ',ORTU_EMAIL_SANTRI AS EMAIL_ORANG_TUA'
                . ',KODE_EMIS_ASSAN AS KODE_ASAL_SANTRI'
                . ',NAMA_ASSAN AS NAMA_ASAL_SANTRI'
                . '');
        $this->db->from($this->table);
        $this->db->join('md_jenis_kelamin', 'ID_JK=JK_SANTRI');
        $this->db->join('md_kecamatan', 'ID_KEC=KECAMATAN_SANTRI');
        $this->db->join('md_kabupaten', 'ID_KAB=KABUPATEN_KEC');
        $this->db->join('md_provinsi', 'ID_PROV=PROVINSI_KAB');
        $this->db->join('md_kamar', 'ID_KAMAR=KAMAR_SANTRI', 'LEFT');
        $this->db->join('md_gedung', 'GEDUNG_KAMAR=ID_GEDUNG', 'LEFT');
        $this->db->join('md_suku', 'SUKU_SANTRI=ID_SUKU', 'LEFT');
        $this->db->join('md_agama', 'AGAMA_SANTRI=ID_AGAMA', 'LEFT');
        $this->db->join('md_kondisi', 'KONDISI_SANTRI=ID_KONDISI', 'LEFT');
        $this->db->join('md_kewarganegaraan', 'WARGA_SANTRI=ID_WARGA', 'LEFT');
        $this->db->join('md_golongan_darah', 'GOL_DARAH_SANTRI=ID_DARAH', 'LEFT');
        $this->db->join('md_tempat_tinggal', 'TEMPAT_TINGGAL_SANTRI=ID_TEMTING', 'LEFT');
        $this->db->join('md_ortu_hidup ayahoh', 'AYAH_HIDUP_SANTRI=ayahoh.ID_SO', 'LEFT');
        $this->db->join('md_jenjang_pendidikan ayahjp', 'AYAH_PENDIDIKAN_SANTRI=ayahjp.ID_JP', 'LEFT');
        $this->db->join('md_pekerjaan ayahp', 'AYAH_PEKERJAAN_SANTRI=ayahp.ID_JENPEK', 'LEFT');
        $this->db->join('md_ortu_hidup ibuoh', 'IBU_HIDUP_SANTRI=ibuoh.ID_SO', 'LEFT');
        $this->db->join('md_jenjang_pendidikan ibujp', 'IBU_PENDIDIKAN_SANTRI=ibujp.ID_JP', 'LEFT');
        $this->db->join('md_pekerjaan ibup', 'IBU_PEKERJAAN_SANTRI=ibup.ID_JENPEK', 'LEFT');
        $this->db->join('md_hubungan', 'WALI_HUBUNGAN_SANTRI=ID_HUB', 'LEFT');
        $this->db->join('md_jenjang_pendidikan walijp', 'WALI_PENDIDIKAN_SANTRI=walijp.ID_JP', 'LEFT');
        $this->db->join('md_pekerjaan walip', 'WALI_PEKERJAAN_SANTRI=walip.ID_JENPEK', 'LEFT');
        $this->db->join('md_penghasilan', 'ORTU_PENGHASILAN_SANTRI=ID_HASIL', 'LEFT');
        $this->db->join('md_asal_santri', 'STATUS_ASAL_SANTRI=ID_ASSAN', 'LEFT');
        $this->db->where(array(
            'ALUMNI_SANTRI' => 0,
            'AKTIF_SANTRI' => 1,
            'STATUS_MUTASI_SANTRI' => NULL,
        ));
    }

    public function get_datatable() {
        $this->_get_table();
        $data = $this->db->get()->result_array();
        $header_value = array_keys($data[0]);
        $header = array_combine($header_value, $header_value);

        $data[-1] = $header;
        ksort($data);

        return array_values($data);
    }

    public function simpan_data($santri, $akademik) {
        $santri['AKTIF_SANTRI'] = 1;
        $this->db->insert('md_santri', $santri);
        $id = $this->db->insert_id();

        if ($id) {
            $akademik['SANTRI_AS'] = $id;
            $akademik['TA_AS'] = $this->session->userdata('ID_TA');
            
            $sql = 'INSERT INTO akad_santri (`KELAS_AS`, `ROMBEL_AS`, `NO_ABSEN_AS`, `SANTRI_AS`, `TA_AS`, `USER_AS`) '
                    . 'SELECT ID_KELAS, '.$akademik['ROMBEL_AS'].', '.$akademik['NO_ABSEN_AS'].', '.$id.', '.$this->session->userdata('ID_TA').', '.$this->session->userdata('ID_USER').' '
                    . 'FROM md_kelas INNER JOIN md_kegiatan ON KEGIATAN_KELAS=ID_KEGIATAN AND KODE_EMIS_KELAS='.$akademik['KODE_EMIS_KELAS'].' AND ID_KEGIATAN='.$akademik['ID_KEGIATAN'];
            $this->db->query($sql);
            $result = $this->db->affected_rows();
            
            if($result) {
                return true;
            } else {
                $where = array(
                    'ID_SANTRI' => $id
                );
                $this->db->delete('md_santri', $where);
                
                return false;
            }
        } else {
            return false;
        }
    }

}
