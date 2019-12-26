<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Router extends CI_Router
{

    function _set_request($seg = array())
    {
        $route = str_replace('-', '/', $seg);

        parent::_set_request($route);
    }

}