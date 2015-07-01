<?php

class user {
    function reg_user($arr){
        $data['user_pass'] = $arr['user_pass'];
        $data['user_login'] = $arr['user_login'];
        $data['user_email'] = $arr['user_email'];
        $data['first_name'] = $arr['first_name'];

        if($arr['role'] == 'manager'){
            $data['role'] = $arr['role'];
        }

        return wp_insert_user( $data );
    }


    function reg_user_meta($user_id, $arr){
       $pole_fields = $this->see_pole();
       foreach($pole_fields as $v){
            if($v->key != 'user_name' && $v->key != 'user_email' && $v->key != 'user_login' && $v->key != 'user_pass') {
                $user[$v->key] = $arr[$v->key];
            }
        }
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
        $data['first_name'] = $arr['first_name'];

        if (!empty($arr['user_pass'])){
            wp_set_password( $arr['user_pass'], $data['ID'] );
        }

        $k=wp_update_user($data);
    }


    function update_user_meta($arr){
        $pole_fields = $this->see_pole();
        $user_id = $arr['update'];
        foreach($pole_fields as $v){
            if($v->key != 'user_name' && $v->key != 'user_email' && $v->key != 'user_login' && $v->key != 'user_pass') {
                $user[$v->key] = $arr[$v->key];
            }
        }
        foreach($user as $k => $v){
            update_user_meta( $user_id, $k, $v );
        }
    }


    function add_pole($arr){
        $data['label'] = $arr['label'];
        $data['key'] = $arr['key'];
        $data['priority'] = $arr['priority'];
        $data['publication'] = $arr['publ'];
        global $wpdb;
        $wpdb->insert( 'filds', $data );
    }


    function see_pole(){
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM filds ORDER BY priority");
        return($result);
    }


    function get_pole(){
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM filds WHERE publication = 1 ORDER BY priority");
        return($result);
    }


    function del_pole($k){
        global $wpdb;
        $wpdb->delete( 'filds', array( 'id' => $k ) );
    }


    function get_label_by_key($key){
        $pole = $this->see_pole();
        foreach ($pole as $v){
            if ($v->key == $key){
                return $v->label;
                die();
            }
        }
    }


    function get_array_key(){
        $pole = $this->see_pole();
        foreach($pole as $v){
            $arr[] = $v->key;
        }

        return $arr;
    }


    function get_search_id_user($poisk){
        $p = $poisk['search'];
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM wp_users WHERE user_email LIKE '%".$p."%'");
        $arr = array();
        foreach($result as $v){
            if (!in_array($v->ID,$arr)){
                $arr[] = $v->ID;
            }
        }
        $result_m = $wpdb->get_results("SELECT * FROM wp_usermeta WHERE meta_value LIKE '%".$p."%'");
        $arr_m = array();
        foreach($result_m as $v){
            if(!in_array($v->user_id, $arr_m)){
                $arr_m[] = $v->user_id;
            }
        }
        $res = array_merge($arr,$arr_m);
        $arr_res = array();
        foreach($res as $v){
            if (!in_array($v,$arr_res)){
                $arr_res[] = $v;
            }
        }
        return $arr_res;
    }


    function max_num_pages(){
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM wp_users");
        $k='';
        foreach($result as $v){
            $k++;
        }
        $k = ceil($k/10);
        return $k;
    }
}