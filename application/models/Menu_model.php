<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Menu_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_menu($ID_HAKAKSES) {
    	$this->db->select('ID_MENU, NAME_MENU, TEMPLATE_MENU, DIR_MENU, CONTROLLER_MENU, FUNCTION_MENU, ICON_MENU, SHOW_MENU, LEVEL_CHILD, HAVE_CHILD');
    	$this->db->from('md_menu m');
    	$this->db->join('md_levelmenu lm', 'lm.MENU_LEVELMENU = m.ID_MENU');
    	$this->db->where('lm.HAKAKSES_LEVELMENU = '.$ID_HAKAKSES.'');
        $this->db->order_by('ID_MENU', 'ASC');

    	return $this->db->get()->result_array();
    }
}