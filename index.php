<?php
/*

MySQL Setup (database: atomdev, username: atom, password: @t0mDev)

CREATE DATABASE `atomdev` COLLATE 'utf8_general_ci';
CREATE USER 'atom'@'localhost' IDENTIFIED BY PASSWORD '*F2DE1B6A8CEDDA93B27D320DDD118F938328C671';
GRANT ALL PRIVILEGES ON `atomdev`.* TO 'atom'@'localhost';



*/

require_once getcwd().'/include/common.php';
require_once getcwd().'/include/parseXML.php';






$content = (new Template('home'))->parse()->content;




$tpl = (new Template('main'))
	 ->set('title','CV Processor')
	 ->set('content', $content)
	 ->parse();

header('Content-Type: text/html; charset=UTF-8');
$tpl->flush();


/*
echo '<pre>'; print_r($_FILES); echo '</pre>';

if (move_uploaded_file($_FILES["xmlcv"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

Array
(
    [xmlcv] => Array
        (
            [name] => ews_stats.cdrp.xml
            [type] => text/xml
            [tmp_name] => /tmp/php8qIJQX
            [error] => 0
            [size] => 1853
        )

)



*/
