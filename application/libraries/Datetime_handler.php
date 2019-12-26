<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Datetime_handler {

    function date_to_store($date = null, $normal = true) {
        if ($date == NULL)
            $date = date('Y-m-d');

        if ($normal)
            return date('Y-m-d', strtotime($date));
        else
            return date('Y-m-d', strtotime('+1 days', strtotime($date)));
    }

}
