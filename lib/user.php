<?php

class user {
    function reg_user($arr){
        $data['user_pass'] = $arr['user_pass'];
        $data['user_login'] = $arr['user_login'];
        $data['user_email'] = $arr['user_email'];
        $data['first_name'] = $arr['user_name'];

        return wp_insert_user( $data );
    }

    function reg_user_meta($user_id, $arr){
        $user = array(
            'name_komp' => $arr['name_komp'],
            'telephone' => $arr['telephone'],
            'inn' => $arr['inn'],
            'lic' => $arr['lic'],
            'nomdogovor' => $arr['nomdogovor'],
            'date_dog' => $arr['date_dog'],
            'adres' => $arr['adres'],
        );

        foreach($user as $k => $v){
            add_user_meta( $user_id, $k, $v, true );
        }
    }

    function get_all_user_meta($user_id){
        global $wpdb;

        $info = $wpdb->get_results( "SELECT * FROM wp_usermeta WHERE user_id = $user_id", ARRAY_A );
        foreach($info as $v){
            $arr[$v['meta_key']] = $v['meta_value'];
        }
        return $arr;
    }

    function update_user_main($arr){
        $data['ID'] = $arr['update'];
        $data['user_email'] = $arr['user_email'];
        $data['first_name'] = $arr['user_name'];

        $k=wp_update_user( $data);
    }

    function update_user_meta($arr){
        $user_id = $arr['update'];
        $user = array(
            'name_komp' => $arr['name_komp'],
            'telephone' => $arr['telephone'],
            'inn' => $arr['inn'],
            'lic' => $arr['lic'],
            'nomdogovor' => $arr['nomdogovor'],
            'date_dog' => $arr['date_dog'],
            'adres' => $arr['adres'],
        );

        foreach($user as $k => $v){
            update_user_meta( $user_id, $k, $v );
        }
    }
} 