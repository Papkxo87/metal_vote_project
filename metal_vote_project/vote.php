<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style type="text/css">
    body {
      background-color: black;
      margin-top: 100px;
      margin-bottom: 50px;
    }

    p {
      color: white;
      font-size: 50px;
      text-align: center;
      font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
      margin-top: 0px;
    }

    img {
      margin: 40px 20px -1px 70px;
      width: 90%;
      height: 60vh;
    }
  </style>
</head>

<body>
  <img src="images/metallica.png" alt="">
  <p>Спасибо за участие в голосовании!<br>Если интересует результат-связывайтесь с papkoi@mail.ru</p>

  <?php
  // получаем файлы по определенному голосованию
  $id =  (int) $_GET['id']; //приводим к целому числу идентификатор голосования
  $vote = (int) $_GET['vote']; //приводим к целому числу передаваемое значение голосования
  $name = $_GET['name'];

  //проверяем, существует ли такое голосование
  if (file_exists("$id.txt")) {

    $ip = $_SERVER['REMOTE_ADDR']; //получаем ip адрес
    $ip_file = file_get_contents("ip$id.json"); //читаем содержимое файла ip адресов и помещаем в строку
    $ip_abbr = explode(",", $ip_file); //получаем в массив имеющиеся ip адреса
    $data = file("$id.txt"); //читаем содержимое файла результатов и помещаем в массив

    // если это не просто просмотр результатов
    //if ($vote) {

    //сравниваем ip с уже записанными
    /*foreach($data as $value) 
    if ($ip_abbr == $value) {
      echo "<p><b><font color=red> Вы уже голосовали! </font></b></p>";
      exit;
    }
    echo "<p><b><font color=green> Спасибо за ваш голос! </font><p>";
    }*/

    // выводим заголовок голосования если это необходимо - 1я строка файла
    //echo "<b>$data[0]</b><p>";

    // печатаем список ответов и результатов - остальные строки
    /*for ($i = 1; $i < count($data); $i++) {
    $votes = explode("~", $data[$i]); // значение~ответ
    echo "$votes[1]: <b>$votes[0]</b><br>"; //поменяйте местами 0 и 1 в $votes и в результатах цифры будут первыми
  }
    echo "<br>Всего проголосовало: <b>" . (count($ip_abbr) - 1) . "</b>";*/

    // если это не просмотр результатов, а голосование,
    // производим необходимые действия для учета голоса
    if ($vote) {
      $f = fopen("$id.txt", "w");
      flock($f, LOCK_EX);
      fputs($f, "$data[0]");
      for ($i = 1; $i < count($data); $i++) {
        $votes = explode("~", $data[$i]);
        if ($i == $vote) $votes[0]++;
        fputs($f, "$votes[0]~$votes[1]");
        fflush($f);
        flock($f, LOCK_UN);
      }
      fclose($f);

      //записываем ip и дату голосования в json
      $ip_add = json_decode(file_get_contents("ip1.json"));
      $ip_add[] = ["name"=> $name, "ip" => $ip, "date" => date("d.n.Y")];
      file_put_contents("ip1.json", json_encode($ip_add, JSON_UNESCAPED_UNICODE));
    }
  }
  ?>
</body>

</html>