<?php

/**
 * Description of Crypt
 *
 * @author rohmad
 */
class Crypt {

    public function encryptPassword($string) {
        return md5($string);
    }

    public function encryptDefaultPassword() {
        return md5('12345');
    }

    public function randomString($length = 25, $char_kapital = FALSE) {
        if ($char_kapital)
            $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        else
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function next_char($char, $count) {
        for ($i = 0; $i < $count; $i++) {
            ++$char;
        }

        return $char;
    }

}
