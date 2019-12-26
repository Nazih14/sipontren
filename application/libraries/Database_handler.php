<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Database_handler {

    public function set_select($post, $exl = null) {
        $select = '';
        foreach ($post as $field) {
            $passed = false;
            if (is_array($exl)) {
                foreach ($exl as $item) {
                    if ($field == $item)
                        $passed = true;
                }
            } else {
                if ($field == $exl)
                    $passed = true;
            }

            if ($field == 'ACTION' || $passed)
                continue;

            if ($select != '')
                $select .= ',';

            $select .= $field;
        }

        return $select;
    }

}
