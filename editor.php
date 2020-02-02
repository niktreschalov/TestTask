<?php
require_once 'sql_request.php';

    if (isset($_POST["add_book"]))
        add_book_editor($_POST["author_name"],$_POST["book_title"],$_POST["genre"]);
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Редактор</title>
    </head>
    <body>
        <b>Редактор книг</b><br>
        <a href='./index.php'>Назад</a><br>
        <br>
        <form name="add_book" action="editor.php" method="post">
            <input type="text" name="book_title" placeholder="Название книги">
            
            <input type="text" name="author_name" placeholder="Автор">
            
            <input type="text" name="genre" placeholder="Жанр">
            
            <input type="submit" name="add_book" value="Добавить">
        </form>
    </body>
</html>