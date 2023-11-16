<?php
/* если нет запроса то просто выводим содержимое страницы*/
if(isset($_POST['phone-number'])){

    /* получаем данные*/
    $url    = 'https://cdn.jsdelivr.net/gh/andr-04/inputmask-multi@master/data/phone-codes.json';
    $data   = file_get_contents($url);
    if ( ! empty($data)) {
        $numbers = json_decode(html_entity_decode($data), TRUE);

        /* преобразуем в нужный формат массив и полученный телефон*/
        $replace_array = array (' ','-','(',')','+','#');
        foreach ($numbers as $key => $number) {
            $numbers[$key]['mask'] = str_replace($replace_array, '', $number['mask']);
        }

        $mynumber = $_POST['phone-number'];
        $mynumber = str_replace($replace_array, '', $mynumber);

        /* ищем совпадение*/
        foreach ($numbers as $key => $number) {
            if (strpos($mynumber,$number['mask'])===0){
                $result = $numbers[$key]['name_ru'].'<br>';
                break;
            }
        }
    }   
    /* Выводим результат*/
    if ($result){
        echo json_encode('Ваша страна - '.$result);
    }
    else {
        echo json_encode('Ваша страна не найдена');
    }
}
else {  ?>

<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>ТЗ для Гризли</title>
    <style>
        html, body {
            height: 100%;
        }
        .w-80{
            width:80%;
        }
        .br-position{
            position: absolute;
            bottom: 0;
            right: 30px;
        }
    </style>
  </head>
  <body >
    <div class="container justify-content-center d-flex align-items-center h-100" >
        <div class="row align-items-center w-80" >
            <div class="col-12">
                <div>
                    <h3 style="text-align:center;">Узнай код своей страны!</h3>
                </div>
                <div>
                <form id="number-form">
                    <div class="row justify-content-md-center gy-2">
                        <div class="col-md-12 col-lg-9">
                            <input type="text" class="form-control" id="phone-number" name="phone-number" placeholder="Введите номер телефона">
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <button type="submit" class="btn btn-primary w-100" id="get-counry-code">Узнать!</button>
                        </div>
                    </div>
                </form>
                <div id="result_form"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Модальное Окно  -->
    <div class="modal" id="overlay" tabindex="-1" >
        <div class="modal-dialog br-position">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Осторожно кукисы!</h5>
                <button type="button" class="btn-close "  data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Тут используются кукисы! Нажми Принять чтобы увидеть его следующий раз завтра, иначе он будет тебя преслееееедовааааать \О.О/
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="hide_popup">Принять</button>
            </div>
            </div>
        </div>
    </div>
    



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script>
        jQuery( document ).ready(function() {
            jQuery("#get-counry-code").click(
                function(){
                    sendAjaxForm( 'number-form', 'index.php','#result_form');
                    return false; 
                }
            );
        });
        function sendAjaxForm( ajax_form, url, result_form) {

            jQuery(result_form).html('');
            jQuery.ajax({
                url:     url, //url страницы 
                type:     "POST", //метод отправки
                dataType: "html", //формат данных
                data: jQuery("#"+ajax_form).serialize(),  // Сеарилизуем объект
                success: function(response) { //Данные отправлены успешно
                    jQuery(result_form).html(JSON.parse(response));
                },
                error: function(response) { // Данные не отправлены
                    jQuery(result_form).html('Error');
                }
            });
        }

        //проверка куки
        function getCookie(name) {
            var cookie_arr = document.cookie.split('; ');
            var cookie_obj = {};
            for (var i=0; i<cookie_arr.length; i++) {
                var nv = cookie_arr[i].split('=');
                cookie_obj[nv[0]] = nv[1]; 
            }
            return cookie_obj[name];
        }        

        //наше модальное окно
        var myModal = new bootstrap.Modal(document.getElementById('overlay'), {
            keyboard: false
        })

        if ( getCookie('hide_popup') == 'yes' ) {
            myModal.hide();
        }
        else {
            myModal.show();
        }

        // При нажатии на кнопку ставим cookie, которая будет запрещать показ модального окна
        document.getElementById('hide_popup')
            .addEventListener('click', function() { 
                // Ставим cookie на минуту.                
                var date = new Date(new Date().getTime() + 60000);
                // Cтавим cookie на день. 
                //var date = new Date(new Date().getTime() + 86 400 000);

                document.cookie = "hide_popup=yes; path=/; expires=" + date.toUTCString();

                // и сразу же скрываем окно
                myModal.hide();
            });
    
    
    
    </script>

</body>
</html>

<?php
}

?>