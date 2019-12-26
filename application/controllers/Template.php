<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends CI_Controller {

    public function show($view) {
//        $this->auth->log_out();
        $view = str_replace('-', '/', $view);

        if (substr($view, -14) == 'datatable.html')
            $view = 'template/datatable.html';

        $view = str_replace('html', 'php', $view);

        $this->load->view($view);
    }

    public function menu() {
        $data = array();
        $data['name_app'] = $this->pengaturan->getNamaApp();

        if ($this->auth->check_validation()) {
            $data['menus'] = array();
            $menus = json_decode($this->session->userdata('MENU_USER'));
            $temp_id_parent = array();
            foreach ($menus as $menu) {
                if ($menu->SHOW_MENU) {
                    if ($menu->LEVEL_CHILD == 1) {
                        $data['menus'][$menu->ID_MENU] = array(
                            'title' => $menu->NAME_MENU,
                            'childMenus' => array()
                        );
                    } elseif ($menu->LEVEL_CHILD == 2) {
                        if ($menu->HAVE_CHILD) {
                            $data['menus'][$temp_id_parent[1]]['childMenus'][$menu->ID_MENU] = array(
                                'link' => '#',
                                'title' => $menu->NAME_MENU,
                                'haveChild' => TRUE,
                                'childMenuChilds' => array()
                            );
                        } else {
                            $data['menus'][$temp_id_parent[1]]['childMenus'][$menu->ID_MENU] = array(
                                'link' => $menu->DIR_MENU . '-' . $menu->CONTROLLER_MENU . '/' . $menu->TEMPLATE_MENU . '/' . $menu->DIR_MENU . '/' . $menu->CONTROLLER_MENU,
                                'title' => $menu->NAME_MENU
                            );
                        }
                    } elseif ($menu->LEVEL_CHILD == 3) {
                        $data['menus'][$temp_id_parent[1]]['childMenus'][$temp_id_parent[2]]['childMenuChilds'][$menu->ID_MENU] = array(
                            'link' => $menu->DIR_MENU . '-' . $menu->CONTROLLER_MENU . '/' . $menu->TEMPLATE_MENU . '/' . $menu->DIR_MENU . '/' . $menu->CONTROLLER_MENU,
                            'title' => $menu->NAME_MENU
                        );
                    }
                }

                if ($menu->HAVE_CHILD)
                    $temp_id_parent[$menu->LEVEL_CHILD] = $menu->ID_MENU;
            }
        } else {
            show_error('Anda tidak memiliki akses pada halaman ini', '403', 'Silahkan login terlebih dahulu.');
        }

        echo json_encode($data);
    }

    public function info() {
        $data = array(
            'name_dev' => 'Rohmad Eko Wahyudi',
            'email_dev' => 'rohmad.ew@gmail.com',
            'version' => $this->pengaturan->getVersiApp(),
            'github' => 'https://github.com/RohmadEW/simapes'
        );

        echo json_encode($data);
    }

}
