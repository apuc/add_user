
    <form action="/wp-admin/admin.php?page=add_user" method="POST">
       <!-- <p>Введите имя <input type="text" name="user_name" value="{first_name}" id=""/></p>-->
       <!-- <p>Введите логин <input type="text" name="user_login" value="{user_login}"id=""/></p>
        <p>Введите пароль <input type="text" name="user_pass" value="{user_pass}" id=""/></p>-->
       <!-- <p>Введите Email <input type="text" name="user_email" value="{user_email}" id=""/></p>
        
        <p>Введите название компании <input type="text" name="name_komp" id="" value="{name_komp}"/></p>
        <p>Введите номер телефона <input type="text" name="telephone" value="{telephone}" id=""/></p>
        <p>Введите ИНН<input type="text" name="inn" value="{inn}" id=""/></p>
        <p>Введите лицевой счет в руб <input type="text" name="lic" value="{lic}" id=""/></p>
        <p>Введите номер договора <input type="text" name="nomdogovor" value="{nomdogovor}" id=""/></p>
        <p>Введите дату заключения дговора <input type="text" name="date_dog" value="{date_dog}" id=""/></p>
        <p>Введите адрес объекта <input type="text" name="adres" value="{adres}" id=""/></p>-->
        <input type="hidden" name="update" value="{ID}" id=""/>
        {fields}
        {ref}
        <!--<p>Введите логин<input type = "text" name="login"/> </p>
        <p>Введите пароль<input type = "password" name = "pass"/></p>
        <p>Введите название компании <input type="text" name="name_komp" /></p>
        <p>Введите номер телефона<input type="text" name="namtel"/></p>
        <p>Введите имя <input type="text" name="name" /></p>
        <p>Введите ИНН <input type="text" name="inn"/></p>
        <p>Введите лицевой счет в рублях <input type="text" name="check"/></p>
        <p>Введите номер договора <input type="text" name="namdogovor" id=""/></p>
        <p></p>-->
        <input type="submit" value = "Сохранить"/>
    </form>