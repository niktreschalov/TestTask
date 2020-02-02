<?php

function add_book_editor($author,$book,$genre){
    $genre_id = add_genre($genre);
    $author_id = add_author($author);
    $book_id = add_book($book);
    $authorship_id = add_authorship($author_id,$book_id);
    $book_card_id = add_book_card($authorship_id,$genre_id);
}


function add_author($author){ //Проверка наличия автора/авторов, если нет, добавляем. Возвращает id_авторства на случай, если авторов у одной книги будет будет много  
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");
    $res = $mysqli->query("SELECT author_id FROM author WHERE name='$author';");
    $resoult = $res->fetch_assoc();
    if (empty($resoult)){    
        $mysqli->query("INSERT INTO `author` (`author_id`, `name`) VALUES (NULL, '".$author."');");
    }
    $res = $mysqli->query("SELECT author_id FROM author WHERE name='$author';");    
    $resoult = $res->fetch_assoc();
    $return = $resoult["author_id"];
    return $return;
    }
    
function add_genre($genre){ //Проверка наличия жанра, если нет, добавляем 
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");
    $res = $mysqli->query("SELECT genre_id FROM genre WHERE genre_title='$genre' LIMIT 1;");
    $resoult = $res->fetch_assoc();
    if (empty($resoult)){  
        $mysqli->query("INSERT INTO genre (genre_id, genre_title) VALUES (NULL,'".$genre."');");
    }
    $res = $mysqli->query("
    SELECT genre_id FROM genre WHERE genre_title='$genre' LIMIT 1;
    ");
    $resoult = $res->fetch_assoc();
    $return = $resoult["genre_id"];
    return $return;
}

function add_book($book_title){ //Проверка наличия книги, если нет, добавляем
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");
    $res = $mysqli->query("
    SELECT book_id FROM book WHERE book_title='$book_title'
    ");
    $resoult = $res->fetch_assoc();
    if (empty($resoult)){
        $mysqli->query("INSERT INTO book (book_id, book_title) VALUES (NULL, '".$book_title."');");
    }
    $res = $mysqli->query("SELECT book_id FROM book WHERE book_title='$book_title'");
    $resoult = $res->fetch_assoc();
    $return = $resoult["book_id"];
    return $return;
}

function add_authorship($author_id,$book_id){ //Проверка наличия авторства книги, если нет, добавляем
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");  
    $res = $mysqli->query("SELECT * FROM authorship WHERE (author_id='$author_id') AND (book_id='$book_id');");
    $resoult = $res->fetch_assoc();
    if (empty($resoult)){    
        $mysqli->query("INSERT INTO authorship (authorship_id,author_id,book_id)VALUES (NULL,'$author_id','$book_id');");
    } 
    $res = $mysqli->query("SELECT authorship_id FROM authorship WHERE (author_id='$author_id') AND (book_id='$book_id');");
    $resoult = $res->fetch_assoc(); 
    $return = $resoult["authorship_id"];  
    return $return;
}

function add_book_card($authorship_id,$genre_id){ //Проверка наличия авторства книги, если нет, добавляем
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");  
    $res = $mysqli->query("SELECT * FROM book_card WHERE (authorship_id='$authorship_id') AND (genre_id='$genre_id');");
    $resoult = $res->fetch_assoc();
    if (empty($resoult)){    
        $mysqli->query("INSERT INTO book_card (book_card_id,authorship_id,genre_id,rating_book)VALUES (NULL,'$authorship_id','$genre_id',NULL);");
    } 
    $res = $mysqli->query("SELECT * FROM book_card WHERE (authorship_id='$authorship_id') AND (genre_id='$genre_id');");
    $resoult = $res->fetch_assoc(); 
    $return = $resoult["book_card_id"];  
    return $return;
}

function write_list(){
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");  
    $res = $mysqli->query("
    SELECT book_card.book_card_id,book.book_title,author.name,genre.genre_title, book_card.rating_book 
    FROM book_card,book,authorship,author,genre
    WHERE
    (book_card.authorship_id=authorship.authorship_id) AND 
    (book_card.genre_id=genre.genre_id) AND
    (authorship.author_id=author.author_id) AND
    (book.book_id=book.book_id) GROUP BY author.name
    ;");
    while ($resoult = $res->fetch_assoc()){   
    print_r($resoult);
        echo "<br>";
    }
    return $resoult;
}

function get_book_card_id(){ //Вывод всех ID книжных карт
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");  
    $res = $mysqli->query("
    SELECT book_card_id 
    FROM book_card ORDER BY genre_id
    ;");
    $i=0;
    while ($resoult = $res->fetch_assoc()){   
    $book_card_id[$i]=$resoult["book_card_id"];
    $i++;
    }
    return $book_card_id;
}

function get_book_title_by_card($book_card_id){ //Вывод названия книги по ID карты
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");  
    $res = $mysqli->query("
    SELECT book.book_title 
    FROM book_card,authorship,book WHERE
    (book_card.book_card_id='$book_card_id') AND
    (book_card.authorship_id=authorship.authorship_id) AND
    (authorship.book_id=book.book_id) LIMIT 1
    ;");
    while ($resoult = $res->fetch_assoc()){   
    $book_title=$resoult["book_title"];
    }
    return $book_title;
}

function get_author_name_by_card($book_card_id){ //Вывод имени автора по ID карты
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");  
    $res = $mysqli->query("
    SELECT author.name 
    FROM book_card,authorship,author WHERE
    (book_card.book_card_id='$book_card_id') AND
    (book_card.authorship_id=authorship.authorship_id) AND
    (authorship.author_id=author.author_id) LIMIT 1
    ;");
    while ($resoult = $res->fetch_assoc()){   
    $author_name=$resoult["name"];
    }
    return $author_name;
}

function get_genre_by_card($book_card_id){ //Вывод жанра книги по ID карты
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");  
    $res = $mysqli->query("
    SELECT genre.genre_title 
    FROM book_card,genre WHERE
    (book_card.book_card_id='$book_card_id') AND
    (book_card.genre_id=genre.genre_id) LIMIT 1
    ;");
    while ($resoult = $res->fetch_assoc()){   
    $genre_title=$resoult["genre_title"];
    }
    return $genre_title;
}

function get_book_rating_by_card($book_card_id){ //Вывод рейтинга книги по ID карты
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");  
    $res = $mysqli->query("
    SELECT book_card.rating_book 
    FROM book_card WHERE
    book_card.book_card_id='$book_card_id' LIMIT 1
    ;");
    while ($resoult = $res->fetch_assoc()){   
    $rating_book=$resoult["rating_book"];
    }
    return $rating_book;
}

function get_author_rating_by_card($book_card_id){ //Вывод рейтинга книги по ID карты
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");  
    $res = $mysqli->query("
    SELECT AVG(book_card.rating_book) AS rating_author FROM book_card,authorship,author WHERE authorship.author_id=(SELECT author.author_id
    FROM book_card,authorship,author WHERE
    (book_card.book_card_id='$book_card_id') AND
    (book_card.authorship_id=authorship.authorship_id) AND
    (authorship.author_id=author.author_id)) AND (book_card.authorship_id=authorship.authorship_id) GROUP BY author.author_id LIMIT 1
    ;");
    while ($resoult = $res->fetch_assoc()){   
    
    $rating_author=round($resoult["rating_author"]);
    }
    return $rating_author;
}

function get_catalog_data() {
    $book_card_id = get_book_card_id();
    $array = array();
    for ($i = 0; $i < count($book_card_id); $i++)
        {
        $array[$i][0]=$book_card_id[$i];
        $array[$i][1]=get_book_title_by_card($book_card_id[$i]);
        $array[$i][2]=get_book_rating_by_card($book_card_id[$i]);
        $array[$i][3]=get_genre_by_card($book_card_id[$i]);
        $array[$i][4]=get_author_name_by_card($book_card_id[$i]);
        $array[$i][5]=get_author_rating_by_card($book_card_id[$i]);
        }
    return $array;  
}

function get_book_data_for_view($book_card_id){
    $mysqli = new mysqli("localhost", "root", "123", "library");
    $mysqli->query("SET NAMES 'utf8'");  
    $res = $mysqli->query("
    SELECT book.book_title,author.name,genre.genre_title,book_card.rating_book
    FROM book_card,authorship,book,author,genre WHERE
    (book_card.book_card_id='$book_card_id') AND
    (book_card.authorship_id=authorship.authorship_id) AND
    (book_card.genre_id=genre.genre_id) AND
    (authorship.book_id=book.book_id) GROUP BY book_card.book_card_id
    LIMIT 1
    ;");
    while ($resoult = $res->fetch_assoc()){   
    $book_title=$resoult;
    }
    return $book_title;
}
?>