<?php
/**
Plugin Name: Online Add Users by Art Craft
Plugin URI: http://web-artcraft.com
Description: Плагин
Version: 1.0.0
Author: ArtCraft
Author URI: http://web-artcraft.com
 */

 /*
dima
 */
 
define('ADD_USER_DIR', plugin_dir_path(__FILE__));
define('ADD_USER_URL', plugin_dir_url(__FILE__));

require_once(ADD_USER_DIR."/lib/parser_add_user.php");
require_once(ADD_USER_DIR."/lib/user.php");

function add_user_style(){
    wp_enqueue_style( 'main-style', ADD_USER_URL . 'css/style.css', array(), '1');
}

function add_user_script(){
    wp_enqueue_script( 'jquery-chat', 'http://code.jquery.com/jquery-1.9.1.min.js', array(), '1');
    wp_enqueue_script( 'chat_script', ADD_USER_URL.'js/script.js', array(), '1');
    wp_localize_script( 'chat_script', 'myajax', array('url' => admin_url('admin-ajax.php')));
}

function true_add_user_backend() {
    wp_enqueue_script( 'admin_js', ADD_USER_URL.'/js/script.js' );
    wp_localize_script( 'admin_js', 'myajax', array('url' => admin_url('admin-ajax.php')));
    wp_enqueue_style( 'true_style', ADD_USER_URL.'/css/style.css' );
    /* wp_enqueue_script( 'true_script', SV_URL.'js/script.js' );*/
}

add_action( 'admin_enqueue_scripts', 'true_add_user_backend' );
add_action( 'wp_enqueue_scripts', 'add_user_style' );
add_action( 'wp_enqueue_scripts', 'add_user_script' );

function add_user_menu_page(){
    add_menu_page( 'ADD USER', 'Добавить пользователя', 'administrator', 'add_user', 'add_user_admin_page' );
}

add_action('admin_menu', 'add_user_menu_page');

function add_user_admin_page(){

    $parser = new Parser_add_user();
    $user = new user();

    if(isset($_GET['action'])){
        if($_GET['action']=='add_form'){
            $parser->parse(ADD_USER_DIR."/view/add_worm.php",array(), true);
        }
        if($_GET['action']=='del'){
            $del = wp_delete_user($_GET['id']);
            print_main();
        }
        if($_GET['action']=='edit'){
           $info = get_userdata($_GET['id']);
           $info = (array)$info->data;
           $info2 = $user->get_all_user_meta($_GET['id']);
           $result = array_merge($info, $info2);
            if ($_GET['ref']=='see'){
                $result['ref'] = "<input type = 'hidden' name ='ref' value = '1' />";
            }
            else{
                $result['ref'] = "";
            }
           $parser->parse(ADD_USER_DIR."/view/edit_form.php",$result, true);
        }
        if ($_GET['action']=='see'){
            print_see($_GET['id']);
        }
    }
    else{
        if (isset($_POST['update'])){
            $user->update_user_main($_POST);
            $user->update_user_meta($_POST);
            if ($_POST['ref']=='1'){
                print_see($_POST['update']);
                wp_die();
            }
        }
        if(isset($_POST['user_name']) && !isset($_POST['update'])){

            $user = new user();
            $user_id = $user->reg_user($_POST);
            $user->reg_user_meta($user_id, $_POST);
        }
        print_main();
    }

    //prn($users);


   // $privet = "lkknjdkb h";

}

function print_main(){
    $parser = new Parser_add_user();
    $parser->parse(ADD_USER_DIR."/view/users.php",array(), true);
    $users = get_users();
    foreach($users as $v){
        $v->path=ADD_USER_URL;
        $parser->parse(ADD_USER_DIR."/view/user-box.php",(array)$v->data, true);
    }
}

function prn($content) {
    echo '<pre style="background: lightgray; border: 1px solid black; padding: 2px">';
    print_r ( $content );
    echo '</pre>';
}

function print_see($id){
    $parser = new Parser_add_user();
    $user = new user();

    $user_info = get_userdata($id);
    //$user_meta_info = get_user_meta($id);
    $user_info = (array)$user_info->data;
    $user_meta_info = $user->get_all_user_meta($id);
    //prn($user_info);
    //  prn($user_meta_info);
    $result = array_merge($user_info, $user_meta_info);
    $parser->parse(ADD_USER_DIR."/view/see.php",$result, true);
}