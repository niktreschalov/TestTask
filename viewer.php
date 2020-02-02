<?php
require_once 'sql_request.php';

if (isset($_GET["book_card"])){
    echo "<b>Просмотр</b><br>";
$array=get_book_data_for_view($_GET["book_card"]);
echo "<a href='./index.php'>Назад</a>  <a href='./editor.php'>Редактировать</a>  <a href='./editor.php'>Удалить</a><br><br>";
echo "<table border=1>
<tr><td>Книга</td>
    <td>Рейтинг книги</td>
    <td>Жанр</td>
    <td>Автор</td>
    </tr>";

    echo "<tr><td>".$array["book_title"]."</td>
              <td>".$array["rating_book"]."</td>
              <td>".$array["genre_title"]."</td>
              <td>".$array["name"]."</td>
              </tr>";
}

if (isset($_GET["author"])){
    echo "<b>Просмотр</b><br>";
$array["author_name"]=get_author_name_by_card($_GET["author"]);
$array["author_rating"]=get_author_rating_by_card($_GET["author"]);    
echo "<a href='./index.php'>Назад</a>  <a href='./editor.php'>Редактировать</a>  <a href='./editor.php'>Удалить</a><br><br>";
echo "<table border=1>
<tr><td>Автор</td>
    <td>Рейтинг автора</td>
    </tr>";

    echo "<tr><td>".$array["author_name"]."</td>
              <td>".$array["author_rating"]."</td>
              </tr>";
}

?>