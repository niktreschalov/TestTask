<?php
require_once 'sql_request.php';

echo "<b>Каталог</b><br>";

$array=get_catalog_data();
echo "<a href='./editor.php'>Новая книга</a><br><br>";
echo "<table border=1>
<tr><td>ID карты</td>
    <td>Книга</td>
    <td>Рейтинг книги</td>
    <td>Жанр</td>
    <td>Автор</td>
    <td>Рейтинг автора</td></tr>";
for ($i = 0; $i < count($array); $i++)
  { 
    echo "<tr><td>".$array[$i][0]."</td>
              <td><a href='./viewer.php?book_card=".$array[$i][0]."'>".$array[$i][1]."</a></td>
              <td>".$array[$i][2]."</td>
              <td>".$array[$i][3]."</td>
              <td><a href='./viewer.php?author=".$array[$i][0]."'>".$array[$i][4]."</a></td>
              <td>".$array[$i][5]."</td></tr>";
}
echo "</table>";
       
?>