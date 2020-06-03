<?php

/*
 * title function
 * check if title is set echo it
 * else echo default
 */
function gettitle(){
    global $pagetitle;
    if(isset($pagetitle)){
        echo $pagetitle;
    }else{
        echo 'Default';
    }
}


function redirect($msg,$url = null,$sec=3){
    if($url === null){
        $url  = 'index.php';
        $link = 'Home Page';
    }else{
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=''){
            $url  = $_SERVER['HTTP_REFERER'];
            $link = 'Preivous Page';
        }else{
            $url  = 'index.php';
            $link = 'Home Page';
        }
    }
    echo $msg;
    echo '<div class="alert alert-info">You Will Redirect To ' . $link . ' In ' . $sec . ' Seconed</div>';
    header("refresh:$sec;url=$url");
    exit();
}


function checkitem($item,$table,$value){
    global $con;
    $statement = $con->prepare("SELECT $item FROM $table WHERE $item=?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}


/*
 * count item function
 */
function countitem($item2,$table2){
    global $con;
    $statement2 = $con->prepare("SELECT COUNT($item2) FROM $table2");
    $statement2->execute();
    return $statement2->fetchColumn();
}

function getlatest($item3,$table3,$order,$limit=5){
    global $con;
    $statement3 = $con->prepare("SELECT $item3 FROM $table3 ORDER BY $order DESC LIMIT $limit");
    $statement3->execute();
    $row = $statement3->fetchAll();
    return $row;
}
