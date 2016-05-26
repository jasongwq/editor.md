<?php

// $db = new PDO('mysql:host=localhost;dbname=editormd', 'editormd', 'admin'
// );
// $sql = "SELECT * FROM `document`";
// echo "<pre>";
// $query = $db->query($sql); //Only 1 row
// print_r($query->fetch());
// error_log(print_r($query->fetch(),true))

//如果没有数据返回false
//时间数据格式2016-05-20 00:00:00
// function md_update_status($name,$time)
// {
//     $db = new PDO('mysql:host=localhost;dbname=editormd', 'editormd', 'admin');
// 	$sql = "SELECT * FROM `document` WHERE NAME = '"
// 	.$name
// 	."' AND TIME > '"
// 	.$time
// 	."'";
// 	error_log(__FILE__.':'.__LINE__.':'.$sql);
//     $query = $db->query($sql);
//     $date=$query->fetch(PDO::FETCH_ASSOC);

//     $jaonDate=json_encode($date);
//     error_log(__FILE__.':'.__LINE__.':'.$jaonDate);
//     return $jaonDate;
// }
// md_update_status('sqltest','2016-05-19 00:00:00');
// function md_update_up($name,$time,$text,$auther)
// {
//     $db = new PDO('mysql:host=localhost;dbname=editormd', 'editormd', 'admin');

//     $sth = $db->prepare("INSERT INTO `document` (`name`, `time`, `text`, `auther`) VALUES (:name, NOW(), :text, :auther)"
//     );
//     //用$limit1得到一个结果
//     $result = $sth->execute(array (
//             ':name' => $name,
//             //':time' => $time,
//             ':text' => $text,
//             ':auther' => $auther
//     ));
//     error_log(__FILE__.':'.__LINE__.':'.$result);
//     return $result;
// }
// md_update_up('132','s','123','3');


$dbserver='sqlite:/editormd.db';

//php7中不再使用mysql接口 使用PDO
$db = new PDO($dbserver, 'editormd', 'admin');

$sth = $db->exec("
CREATE TABLE if not EXISTS document (
  name text KEY NOT NULL,
  time INT  NOT NULL,
  text text NOT NULL,
  auther text NOT NULL
);");
?>