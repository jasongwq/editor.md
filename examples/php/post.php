<?php
$PHPROOT='C:/wamp64/www/db';
// $dbserver='mysql:host=localhost;dbname=editormd';
$dbserver='sqlite:'.$PHPROOT.'/editormd.db';

//如果没有数据返回false
//时间数据格式2016-05-20 00:00:00
function md_update_status($type,$name,$time=0)
{
    global $dbserver;
    //php7中不再使用mysql接口 使用PDO
    $db = new PDO($dbserver, 'editormd', 'admin');
    $sql = "SELECT "
    .$type
    ." FROM `document` WHERE NAME = '"
    .$name
    ."' AND TIME >= '"
    .$time
    ."'";
    error_log(__FILE__.':'.__LINE__.':'.$sql);
    $query = $db->query($sql);
    //PDO::FETCH_ASSOC 返回键值对 默认返回键值对+索引
    $date=$query->fetch(PDO::FETCH_ASSOC);
    error_log(__FILE__.':'.__LINE__.': QUERY '.print_r($date,true));
    return $date;
}

function md_update_up($name,$time,$text,$auther)
{
    global $dbserver;
    $db = new PDO($dbserver, 'editormd', 'admin');

    //判断数据有没有数据 没有插入 有就更新
    $isInsert=$db->query("select name from `document` where name='".$name."'");
    // error_log(__FILE__.':'.__LINE__.':'.$isInsert);

    if ($isInsert->fetch(PDO::FETCH_ASSOC)) {
        //更新数据 使用 prepare不需要要考虑引号 更方便 更安全
        //获取时间将时间转成时间戳（s） 不然new() 得到的时201505。。。这样的数据
        //注意这边得到的时间戳是 (s) 而js 中得到的时间戳是 (ms)
        $sth = $db->prepare("UPDATE `document` SET time=:time, text=:text, auther=:auther where name =:name");

        //SQL语句是在这边执行的
        $result = $sth->execute(array (
                ':name' => $name,
                ':time' => time(),
                ':text' => $text,
                ':auther' => $auther
        ));
        error_log(__FILE__.':'.__LINE__.': UPDATE '.$result);
    }
    else
    {
        //插入数据
        $sth = $db->prepare("INSERT INTO `document` (`name`, `time`, `text`, `auther`) VALUES (:name, :time, :text, :auther)"
        );
        //用$limit1得到一个结果
        $result = $sth->execute(array (
                ':name' => $name,
                ':time' => time(),
                ':text' => $text,
                ':auther' => $auther
        ));
        error_log(__FILE__.':'.__LINE__.': INSERT time:'.time().$result);
    }
    return $result;
}
//md_update_up('132','s','123','3');
function md_htmltopdf($name)
{
    
}
    if (isset($_POST['tpye'])) {
        switch ($_POST['tpye'])
        {
            case 'md_update_status':
            //这边用三元运算符对时间值是否存在进行判断，第一次创建文档时间从本地存储中是获取不到的
            $time=isset($_POST['time'])?$_POST['time']:0;
            $status=md_update_status('time',$_POST['name'],$time);
            if (false==$status) {
                echo "up";
                error_log(__FILE__.':'.__LINE__.':'.'up');
            }else if ($status['time']==$time) {
                error_log(__FILE__.':'.__LINE__.':'.'none');
                error_log(__FILE__.':'.__LINE__.':'.$status['time'].$time);
            }
            else{
                echo 'down';
                error_log(__FILE__.':'.__LINE__.':'.'down');
            }
            // error_log(__FILE__.':'.__LINE__.':'.isset($status['time'])?$status['time']:0.isset($_POST['time'])?$_POST['time']:0);
            error_log(__FILE__.':'.__LINE__.':'.'md_update_status');
          break;
        case 'md_update_up':
            md_update_up($_POST['name'],'now_null',$_POST['text'],'jason');
            error_log(__FILE__.':'.__LINE__.':'.'md_update_up');
          break;
        case 'md_update_down':
            $text=md_update_status('text',$_POST['name']);
            echo $text['text'];
            error_log(__FILE__.':'.__LINE__.':'.'md_update_down');
            break;
        case 'md_htmltopdf':
            $url=md_htmltopdf($_POST['name']);
            echo $url;
            error_log(__FILE__.':'.__LINE__.':'.'md_htmltopdf');
            break;

        default:
            echo 'default';
        }
    }
    exit;
?>