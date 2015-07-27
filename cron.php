<?php
define( 'ABSPATH', dirname(__FILE__) . '/' );
define( 'WPINC', 'wp-includes' );

require_once('/wp-content/plugins/add_user/lib/user.php');
require_once('/wp-includes/plugin.php');
require_once('/wp-includes/load.php');
require_once('/wp-includes/class-wp-error.php ');
require_once('/wp-includes/wp-db.php');
/*require_once('/wp-admin/load-styles.php');*/
require_once('/wp-includes/functions.php');
require_once('/wp-includes/meta.php');
require_once('/wp-includes/user.php');
require_once('/wp-includes/pluggable.php');
require_once('/wp-includes/general-template.php');
require_once('/wp-includes/cache.php');


global $wpdb;
$wpdb = new wpdb('king', '02011951', 'testword', 'localhost');
$user = new user();

$xmlstr = file_get_contents('import.xml');

$xml = simplexml_load_string($xmlstr);

$atr = $user->get_atributes_xml($xml);

$i = 0;
foreach ($atr as $v) {
    $nom_dog = $v['agreement'];
    $date = $v['date'][0] . $v['date'][1] . $v['date'][2] . $v['date'][3] . "." . $v['date'][4] . $v['date'][5] . '.' . $v['date'][6] . $v['date'][7];
    $sum = $v['sum'];
    $user_id = $user->get_overlap($nom_dog);
    if ($user_id) {
       /* $doc = update_user_meta($user_id, 'date_dog', $date);
        echo "<pre>";
        var_dump($doc);
        echo "</pre>";
        update_user_meta($user_id, 'dolg', $sum);*/
        $wpdb->update('wp_usermeta',['meta_value'=>$sum],['user_id'=>$user_id, 'meta_key'=>'dolg']);
        $i++;
    }
}
echo $i;
mail( 'korol_dima@list.ru', 'Обноваление базы данных пользователей '.date('Y-m-d'), "обновленно $i пользователей");
$wpdb->insert( 'ubdate_user', ['dt_add'=>time(),'ub_user'=>$i]);
