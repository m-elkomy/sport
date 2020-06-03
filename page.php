<?php
ob_start();
session_start();
if(isset($_SESSION['username'])){
    $pagetitle = '';
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){
        echo 'manage';
    }elseif($do == 'Add'){
        echo 'Add page';
    }elseif($do == 'Insert'){
        echo 'Insert Page';
    }elseif($do == 'Edit'){
        echo 'Edit page';
    }elseif($do == 'Update'){
        echo 'Update page';
    }elseif($do == 'Delete'){
        echo 'Delete page';
    }

    include $tpl . 'footer.php';
}else{
    header('Location:index.php');
    exit();
}
ob_end_flush();
?>
