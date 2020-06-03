<?php
    session_start();
    $nonavbar = '';
    $pagetitle = 'Login';
    if(isset($_SESSION['username'])){
        header('Location:dashboard.php');
    }
    include 'init.php';
    //check that user coming to this page using post request
    if($_SERVER['REQUEST_METHOD']=='POST'){
        //receive data from user
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashedpass = sha1($password);
        //check that this username and password exist in database
        $stmt = $con->prepare("SELECT * FROM Users WHERE Username=? AND Password=? LIMIT 1");
        $stmt->execute(array($username,$hashedpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count>0){
            $_SESSION['username'] = $username;
            $_SESSION['userid']   = $row['UserID'];
            header('Location:dashboard.php');
            exit();
        }
    }
?>
<div class="home">
    <div class="form">
        <h2 class="text-center">Admin Login</h2>
        <hr>
        <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="User Name" autocomplete="off"/>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="new-password"/>
            </div>
            <input type="submit" class="btn btn-primary btn-block" value="Login"/>
        </form>
    </div>
</div>
<?php include $tpl . 'footer.php';?>