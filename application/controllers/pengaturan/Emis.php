<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emis extends CI_Controller {

    var $primaryKey = 'ID_SANTRI';

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'emis_model' => 'emis',
            'jk_model' => 'jk',
            'masterdata_model' => 'masterdata',
            'asal_santri_model' => 'asal_santri',
            'tempat_tinggal_model' => 'tempat_tinggal',
            'kecamatan_model' => 'kec',
            'kabupaten_model' => 'kab',
            'provinsi_model' => 'prov',
            'status_hidup_model' => 'status_hidup',
            'jenjang_pendidikan_model' => 'jp',
            'pekerjaan_model' => 'pekerjaan',
            'penghasilan_model' => 'penghasilan',
            'kegiatan_model' => 'kegiatan',
            'kelas_model' => 'kelas',
            'rombel_model' => 'rombel',
        ));
        $this->load->library('PHPExcel/PHPExcel');
        $this->auth->validation(array(1));
    }

    public function index() {
        $data = array(
            'title' => 'EMIS',
            'breadcrumb' => 'Pengaturan > EMIS',
            'KEGIATAN' => $this->kegiatan->get_all()
        );
        $this->output_handler->output_JSON($data);
    }

    public function upload_emis() {
        $file_element_name = 'file';
        $config['upload_path'] = './assets/dist/';
        $config['allowed_types'] = 'xls';
        $config['max_size'] = '10000';
        $config['overwrite'] = TRUE;
        $config['file_name'] = 'emis_upload';
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_element_name)) {
            $result = true;
            $message = 'diimport';

            $inputFileName = $config['upload_path'] . '/' . $config['file_name'] . '.' . $config['allowed_types'];

            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(1);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $field = array(
                'NIS_SANTRI',
                'NIK_SANTRI',
                'NAMA_SANTRI',
                'TEMPAT_LAHIR_SANTRI',
                'TANGGAL_LAHIR_SANTRI',
                'JK_SANTRI',
                'AGAMA_SANTRI',
                'TANGGAL_MASUK_SANTRI',
                'STATUS_ASAL_SANTRI',
                'ID_KEGIATAN',
                'KODE_EMIS_KELAS',
                'ROMBEL_AS',
                'NO_ABSEN_AS',
                'TEMPAT_TINGGAL_SANTRI',
                'ALAMAT_SANTRI',
                'KECAMATAN_SANTRI',
                'NAMA_KAB',
                'NAMA_PROV',
                'AYAH_NAMA_SANTRI',
                'AYAH_HIDUP_SANTRI',
                'AYAH_NIK_SANTRI',
                'AYAH_PENDIDIKAN_SANTRI',
                'AYAH_PEKERJAAN_SANTRI',
                'IBU_NAMA_SANTRI',
                'IBU_HIDUP_SANTRI',
                'IBU_NIK_SANTRI',
                'IBU_PENDIDIKAN_SANTRI',
                'IBU_PEKERJAAN_SANTRI',
                'WALI_NAMA_SANTRI',
                'WALI_HUBUNGAN_SANTRI',
                'WALI_NIK_SANTRI',
                'WALI_PENDIDIKAN_SANTRI',
                'WALI_PEKERJAAN_SANTRI',
                'ORTU_PENGHASILAN_SANTRI'
            );

            $source_jk = $this->jk->get_datatable();
            $data_relasi[5] = $this->susun_table('KODE_EMIS_JK', 'ID_JK', $source_jk);

            $source_rombel = $this->rombel->get_datatable();
            $data_relasi[11] = $this->susun_table('NAMA_ROMBEL', 'ID_ROMBEL', $source_rombel);

            $source_penghasilan = $this->penghasilan->get_datatable();
            $data_relasi[33] = $this->susun_table('KODE_EMIS_HASIL', 'ID_HASIL', $source_penghasilan);

            $source_hubungan = $this->masterdata->get_data_hubungan();
            $data_relasi[29] = $this->susun_table('KODE_EMIS_HUB', 'ID_HUB', $source_hubungan);

            $source_pekerjaan = $this->pekerjaan->get_datatable();
            $data_relasi[27] = $this->susun_table('KODE_EMIS_JENPEK', 'ID_JENPEK', $source_pekerjaan);
            $data_relasi[22] = $data_relasi[27];

            $source_jp = $this->jp->get_datatable();
            $data_relasi[21] = $this->susun_table('KODE_EMIS_JP', 'ID_JP', $source_jp);
            $data_relasi[26] = $data_relasi[21];

            $source_status_hidup = $this->status_hidup->get_datatable();
            $data_relasi[24] = $this->susun_table('KODE_EMIS_SO', 'ID_SO', $source_status_hidup);
            $data_relasi[19] = $data_relasi[24];

            $source_prov = $this->prov->get_datatable();
            $data_relasi[17] = $this->susun_table('NAMA_PROV', 'ID_PROV', $source_prov);

            $source_kab = $this->kab->get_datatable();
            $data_relasi[16] = $this->susun_table('NAMA_KAB', 'ID_KAB', $source_kab);

            $source_kec = $this->kec->get_datatable();
            $data_relasi[15] = $this->susun_table('NAMA_KEC', 'ID_KEC', $source_kec);

            $source_tempat_tinggal = $this->tempat_tinggal->get_datatable();
            $data_relasi[13] = $this->susun_table('KODE_EMIS_TEMTING', 'ID_TEMTING', $source_tempat_tinggal);

            $source_asal_santri = $this->asal_santri->get_datatable();
            $data_relasi[8] = $this->susun_table('KODE_EMIS_ASSAN', 'ID_ASSAN', $source_asal_santri);

            $source_kegiatan = $this->kegiatan->get_datatable();
            $data_relasi[9] = $this->susun_table('KODE_EMIS_KEGIATAN', 'ID_KEGIATAN', $source_kegiatan);

//            $source_kelas = $this->kelas->get_datatable();
//            $data_relasi[10] = $this->susun_table('KODE_EMIS_KELAS', 'ID_KELAS', $source_kelas);

            $start = 4;
            for ($row = $start; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                $data_santri = array();
                $data_akademik = array();
                foreach ($rowData[0] as $key => $detail) {
                    if ($key == 4) {
                        if ($detail == NULL) {
                            $result = false;
                            $message = 'gagal disimpan didatabase pada baris ' . $row . ' karena kolom ke-' . ($key + 1) . ' kosong.';

                            break 2;
                        }
                        
                        $tanggal = explode('/', $detail);
                        
                        if(!isset($tanggal[2]) || !isset($tanggal[1]) || !isset($tanggal[0])) {
                            $result = false;
                            $message = 'gagal disimpan didatabase pada baris ' . $row . ' karena format tanggal salah.';

                            break 2;
                        }
                        
                        $data_santri[$field[$key]] = $tanggal[2] . '-' . $tanggal[1] . '-' . $tanggal[0];
                    } elseif ($key == 9 || $key == 10 || $key == 11 || $key == 12) {
                        if ($detail == NULL) {
                            $result = false;
                            $message = 'gagal disimpan didatabase pada baris ' . $row . ' karena kolom ke-' . ($key + 1) . ' kosong.';

                            break 2;
                        }

                        $detail = strtoupper($detail);
                        $data_akademik[$field[$key]] = (isset($data_relasi[$key]) && $detail != NULL) ? $data_relasi[$key][$detail] : $detail;
                    } else {
                        if (isset($data_relasi[$key]) && $detail != NULL)
                            $data_santri[$field[$key]] = $data_relasi[$key][$detail];
                        elseif ($detail != NULL)
                            $data_santri[$field[$key]] = $detail;
                    }
                }

                unset($data_santri['NAMA_KAB']);
                unset($data_santri['NAMA_PROV']);
                $result = $this->emis->simpan_data($data_santri, $data_akademik);

                if (!$result) {
                    $result = false;
                    $message = 'gagal disimpan didatabase pada baris ' . $row;

                    break;
                }
            }
        } else {
            $result = false;
            $message = 'gagal diimport (ERROR: ' . $this->CI->upload->display_errors('', '') . ')';
        }

        $this->output_handler->output_JSON($result, $message);
    }

    private function susun_table($field_emis, $field_data, $source_data) {
        $return = array();

        $data = isset($source_data['data']) ? $source_data['data'] : $source_data;
        foreach ($data as $detail) {
            if (is_array($detail)) {
                $return[$detail[$field_emis]] = strtoupper(trim($detail[$field_data]));
            } elseif (is_object($detail)) {
                $return[$detail->$field_emis] = strtoupper(trim($detail->$field_data));
            }
        }

        return $return;
    }

    public function download_emis() {
        $data = $this->emis->get_datatable();

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Rohmad Eko Wahyudi")->setTitle("SIPONTREN - EMIS");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->fromArray($data, NULL, 'A1');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="sipontren_emis_' . date('Y_m_d_H_i_s') . '.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

}
