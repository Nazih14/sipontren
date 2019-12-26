<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth {

    public function __construct() {

        $this->CI = &get_instance();
    }

    public function validation($ID_HAKAKSES = '') {
        if (!$this->CI->session->userdata('ID_USER'))
            show_error('Anda tidak memiliki akses pada halaman ini', '403', 'Silahkan login terlebih dahulu.');
        else {
//            if ($this->CI->session->userdata('ID_HAKAKSES') == NULL and $this->CI->router->fetch_method() != 'option_hakakses' and $this->CI->router->fetch_class() != 'pencarian') {
//                redirect('login/option_hakakses');
//            }

            if (is_array($ID_HAKAKSES)) {
                if (!in_array($this->CI->session->userdata('ID_HAKAKSES'), $ID_HAKAKSES))
                    show_error('Anda tidak memiliki akses pada halaman ini', '401', 'Kesalahan Hak Akses');
            } elseif (($this->CI->session->userdata('ID_HAKAKSES') != $ID_HAKAKSES) && ($ID_HAKAKSES != ''))
                show_error('Anda tidak memiliki akses pada halaman ini', '401', 'Kesalahan Hak Akses');
        }
    }

    public function check_validation() {
        if ($this->CI->session->userdata('ID_USER'))
            return true;
        else
            return false;
    }

    public function check_login() {
        if ($this->CI->session->userdata('ID_USER'))
            return array(
                'status' => true,
                'user' => $this->CI->session->userdata()
            );
        else
            return array(
                'status' => false
            );
    }

    public function proccess_login($data) {
        $model = array(
            'user_model' => 'user',
            'login_model' => 'login',
            'tahun_ajaran_model' => 'ta',
            'penanggalan_ajaran_model' => 'penanggalan_ajaran',
        );
        $this->CI->load->model($model);

        $result_check_login = '';

        if ($this->CI->login->login_diperbolehkan()) {
            $result_username = $this->CI->user->get_status_login_username($data);

            if ($result_username) {
                if ($result_username->STATUS_USER == 'ACTIVE') {
                    if ($result_username->PASSWORD_USER == $this->CI->crypt->encryptPassword($data->password)) {
                        $data_ta = $this->CI->ta->get_ta_active();
                        $data_cawu = $this->CI->penanggalan_ajaran->get_active();
                        $data = array(
                            'ID_USER' => $result_username->ID_USER,
                            'NAME_USER' => $result_username->NAME_USER,
                            'FULLNAME_USER' => $result_username->NAMA_UST,
                            'ID_TA' => $data_ta['ID_TA'],
                            'NAMA_TA' => $data_ta['NAMA_TA'],
                            'ID_CAWU' => $data_cawu['ID_CAWU'],
                            'NAMA_CAWU' => $data_cawu['NAMA_CAWU'],
                        );
                        $this->CI->user->update(array('ID_USER' => $result_username->ID_USER), array('LASTLOGIN_USER' => date('Y-m-d H:i:s')));

                        $this->CI->session->set_userdata($data);

                        $this->registration_hakakses();

                        $this->CI->login->login_benar($data);

                        $result_check_login = $result_username->STATUS_USER;
                    } elseif ($result_username->SISA_PERCOBAAN_USER > 0) {
                        $this->CI->user->update(array('ID_USER' => $result_username->ID_USER), array('SISA_PERCOBAAN_USER' => $result_username->SISA_PERCOBAAN_USER - 1));

                        $this->CI->login->login_salah($data);

                        $result_check_login = 'WRONG_PASSWORD#' . ($result_username->SISA_PERCOBAAN_USER - 1);
                    } else {
                        $this->CI->user->update(array('ID_USER' => $result_username->ID_USER), array('STATUS_USER' => 'BLOCK'));

                        $this->CI->login->login_salah($data);

                        $result_check_login = 'BLOCK';
                    }
                } else {
                    $this->CI->login->login_salah($data);

                    $result_check_login = $result_username->STATUS_USER;
                }
            } else {
                $this->CI->login->login_salah($data);

                $result_check_login = 'WRONG_USERNAME';
            }
        } else {
            $this->CI->login->login_salah($data);

            $result_check_login = 'TIMEOUT';
        }

        $return = $this->status_login($result_check_login, $data);

        return $return;
    }

    private function status_login($results, $data) {
        $results_ex = explode('#', $results);
        $result = $results_ex[0];
        $count = isset($results_ex[1]) ? $results_ex[1] : NULL;

        if ($result == 'ACTIVE') {
            $data = array(
                'status' => TRUE,
                'notification' => array(
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'text' => 'Selamat, Login berhasil.'
                ),
                'url' => site_url(),
                'user' => $data
            );
        } elseif ($result == 'BLOCK') {
            $data = array(
                'status' => FALSE,
                'notification' => array(
                    'type' => 'warning',
                    'title' => 'Gagal',
                    'text' => 'Akun Anda telah diblock. Silahkan hubungi Admin untuk keterangan lebih lanjut.'
                )
            );
        } elseif ($result == 'PENDING') {
            $data = array(
                'status' => FALSE,
                'notification' => array(
                    'type' => 'warning',
                    'title' => 'Gagal',
                    'text' => 'Akun Anda masih berstatus PENDING. Tunggu sampai kami mengaktifkan akun Anda.'
                )
            );
        } elseif ($result == 'DELETE') {
            $data = array(
                'status' => FALSE,
                'notification' => array(
                    'type' => 'warning',
                    'title' => 'Gagal',
                    'text' => 'Akun Anda telah dihapus oleh Admin. Silahkan hubungi admin untuk keterangan lebih lanjut.'
                )
            );
        } elseif ($result == 'WRONG_USERNAME') {
            $data = array(
                'status' => FALSE,
                'notification' => array(
                    'type' => 'error',
                    'title' => 'Gagal',
                    'text' => 'Username tidak cocok dengan database.'
                )
            );
        } elseif ($result == 'WRONG_PASSWORD') {
            $data = array(
                'status' => FALSE,
                'notification' => array(
                    'type' => 'error',
                    'title' => 'Gagal',
                    'text' => 'Password tidak cocok dengan database. Anda memiliki sebanyak ' . $count . ' lagi untuk mecoba masuk. Jika gagal, maka akun Anda akan diblokir.'
                )
            );
        } elseif ($result == 'WRONG') {
            $data = array(
                'status' => FALSE,
                'notification' => array(
                    'type' => 'error',
                    'title' => 'Gagal',
                    'text' => 'Username atau Password tidak cocok dengan database.'
                )
            );
        } elseif ($result == 'TIMEOUT') {
            $data = array(
                'status' => FALSE,
                'notification' => array(
                    'type' => 'warning',
                    'title' => 'Gagal',
                    'text' => 'Waktu percobaan login Anda telah melampaui batas. Silahkan coba lagi dalam ' . $this->CI->pengaturan->getJedaPercobaanLogin() . ' menit kedepan.'
                )
            );
        } elseif ($result == '') {
            $data = array(
                'status' => FALSE,
                'notification' => array(
                    'type' => 'error',
                    'title' => 'Gagal',
                    'text' => 'Error tidak diketahui.'
                )
            );
        }

        return $data;
    }

    private function clear_log() {
        $this->CI->load->model(array('log_query_model' => 'log_query'));

        $this->CI->log_query->clear_log();
    }

    public function unregistration_hakakses() {
        $data = array(
            'ID_HAKAKSES',
            'NAME_HAKAKSES',
            'MENU_USER'
        );

        $this->CI->session->unset_userdata($data);
    }

    public function registration_hakakses($ID_HAKAKSES = NULL) {
        $this->CI->load->model(array('hakakses_user_model' => 'hakakses_user', 'menu_model' => 'menu'));
        $result = $this->CI->hakakses_user->get_by_id($ID_HAKAKSES);

        if ($result) {
            $data = array(
                'ID_HAKAKSES' => $result->ID_HAKAKSES,
                'NAME_HAKAKSES' => $result->NAME_HAKAKSES,
                'MENU_USER' => json_encode($this->CI->menu->get_menu($result->ID_HAKAKSES, JSON_PRETTY_PRINT))
            );

            $this->CI->session->set_userdata($data);

            return TRUE;
        } else {
            return FALSE;
        }
    }

    // $type = 'add', 'edit', 'delete', 'view', 'export'
    public function crud_validation($type) {
        $MENUS = json_decode($this->CI->session->userdata('MENU_USER'));

        $result = FALSE;
        foreach ($MENUS as $MENU) {
            $menu = $MENU->CONTROLLER_MENU;
            if (strpos($menu, '/')) {
                $menu_ex = explode("/", $menu);
                $menu = $menu_ex[1];
            }

            if (strtolower($menu) == strtolower($this->CI->router->fetch_class()) && (strtolower($type) == strtolower($MENU->FUNCTION_MENU)))
                $result = TRUE;
        }

        return $result;
    }

    public function log_out() {
        $this->CI->session->sess_destroy();
    }

    public function generate_token() {
        $generate_token = $this->CI->crypt->randomString();

        $this->CI->session->unset_userdata('TOKEN');
        $this->CI->session->set_userdata('TOKEN', $this->CI->crypt->encryptPassword($generate_token));

        return $generate_token;
    }

}
