<a id = "check" href = "#" return = "false">Запросить счет на email</a>
<br />
<div onclick="show('none')" id="wrap"></div>

<!-- Само окно-->
<div id="window">

    <!-- Картинка крестика-->
    <a class="close" onclick="show('none')">X</a>

    <form>
        <p>Введите ваше сообщение
             <textarea id = "text"></textarea>
        </p>
        <input type="button" name="" id="masege" value = "Отправить" />
    </form>




</div>

<a class="myButton" onclick="show('block')">Отправить сообщение</a>

{fields}