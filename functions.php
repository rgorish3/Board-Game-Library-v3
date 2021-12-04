<?php
    function randomString($n)
    {
        $characters = '0213456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str='';
        for($i=0; $i<$n; $i++){
            $index = rand(0, strlen($characters) - 1 );
            $str .= $characters[$index];
        }

        return $str;
    }