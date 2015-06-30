<?php
function prin_user_admin($user_info, $pole){
    $html = '
        <tr>
            <td width="10%">
                <a href="/wp-admin/admin.php?page=add_user&action=del&id='.$user_info['ID'].'"><img src="'.ADD_USER_URL.'images/del.png" alt=""/></a>
                <a href="/wp-admin/admin.php?page=add_user&action=edit&id='.$user_info['ID'].'"><img src="'.ADD_USER_URL.'images/edit.png" alt=""/></a>
                <a href = "/wp-admin/admin.php?page=add_user&action=see&id='.$user_info['ID'].'"><img src="'.ADD_USER_URL.'images/see.png" alt=""/></a>
            </td>
            ';
foreach ($pole as $v){
    $html .= '<td>'.$user_info[$v->key].'</td>';
}
    $html.= '</tr>';
    return $html;
}


function print_table_user($pole){
    $html = '<table width="100%"><tr><th></th>';
    foreach($pole as $v) {
        $html .= '<th>'.$v->label.'</th>';
    }
    $html .= '</tr>';
    return $html;
}