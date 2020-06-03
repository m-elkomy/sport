<?php
ob_start();
session_start();
if(isset($_SESSION['username'])){
    $pagetitle = 'Members';
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){
        $stmt = $con->prepare("SELECT * FROM Users");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(!empty($rows)){
        ?>
        <h2 class="text-center">Manage Members</h2>
        <div class="container">
            <div class="table-responsive text-center main-table">
                <table class="table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>User Name</td>
                        <td>Full Name</td>
                        <td>Email</td>
                        <td>RegStatus</td>
                        <td>Control</td>
                    </tr>
                    <?php
                        foreach ($rows as $row){
                            echo '<tr>';
                                echo '<td>' . $row['UserID'] . '</td>';
                                echo '<td>' . $row['UserName'] . '</td>';
                                echo '<td>' . $row['FullName'] . '</td>';
                                echo '<td>' . $row['Email']    . '</td>';
                                echo '<td>' . $row['RegStatus'] . '</td>';
                                echo '<td>';
                                    echo '<a href="?do=Edit&userid='.$row['UserID'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                                    echo '<a href="?do=Delete&userid='.$row['UserID'].'" class="confirm btn btn-danger"><i class="fa fa-close"></i> Delete</a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
            </div>
            <a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>
        </div>
    <?php
        }else{
            echo '<div class="container">';
            echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
            echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>';
            echo '</div>';
        }
    }elseif($do == 'Add'){?>
        <h2 class="text-center">Add New Member</h2>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="post">
                <!-- start user name -->
                <div class="form-group form-group-lg">
                    <label class="control-label col-sm-2">User Name</label>
                    <div class="col-md-6 col-sm-10">
                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            required="required"
                            pattern=".{5,}"
                            title="User Name Must Be Large Than 5 Character"
                            placeholder="User Name To Enter Control Panel"
                            autocomplete="off"/>
                    </div>
                </div>
                <!-- end user name -->
                <!-- start password -->
                <div class="form-group form-group-lg">
                    <label class="control-label col-sm-2">Password</label>
                    <div class="col-md-6 col-sm-10">
                        <input
                            type="password"
                            name="password"
                            class="password form-control"
                            placeholder="password Must Be Strong And Easy To Remember"
                            autocomplete="new-password"/>
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>
                <!-- end password -->
                <!-- start full name -->
                <div class="form-group form-group-lg">
                    <label class="control-label col-sm-2">Full Name</label>
                    <div class="col-md-6 col-sm-10">
                        <input
                            type="text"
                            name="fullname"
                            class="form-control"
                            placeholder="Full Name Shown In Control Panel"
                            required="required"
                            pattern=".{10,}"
                            title="Full Name Must Be Large Than 10 Character"
                            autocomplete="off"/>
                    </div>
                </div>
                <!-- end full name -->
                <!-- start email -->
                <div class="form-group form-group-lg">
                    <label class="control-label col-sm-2">Email</label>
                    <div class="col-md-6 col-sm-10">
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required="required"
                            placeholder="Email To Contact"
                            autocomplete="off"/>
                    </div>
                </div>
                <!-- end email -->
                <!-- start submit -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" class="btn btn-primary btn-lg" value="Insert Member"/>
                    </div>
                </div>
                <!-- end submit -->
            </form>
        </div>
    <?php
    }elseif($do == 'Insert'){
        //check that user coming to this page using post request
        if($_SERVER['REQUEST_METHOD']=='POST'){
            echo '<h2 class="text-center">Insert Member</h2>';
            echo '<div class="container">';
                $username = $_POST['username'];
                $password = sha1($_POST['password']);
                $email    = $_POST['email'];
                $fullname = $_POST['fullname'];

                $formerrors = array();
            if(strlen($username)<5){
                $formerrors[] = 'User Name Can Not Be Less Than 4 Character';
            }
            if(strlen($fullname)<10){
                $formerrors[] = 'Full Name Can Not Be Less Than 10 Characters';
            }
            if(empty($email)){
                $formerrors[] = 'Email Can Not Be Empty';
            }
            foreach ($formerrors as $error){
                $msg = '<div class="alert alert-danger">' . $error . '</div>';
                redirect($msg,'back');
            }
            if(empty($formerrors)){
                $stmt = $con->prepare("SELECT * FROM Users WHERE UserName=?");
                $stmt->execute(array($username));
                $count = $stmt->rowCount();
                if($count==0){
                $stmt = $con->prepare("INSERT INTO Users(UserName,Password,Email,FullName,RegStatus)VALUE(:zuser,:zpass,:zmail,:zfull,now())");
                $stmt->execute(array(
                    ':zuser'  => $username,
                    ':zpass'  => $password,
                    ':zmail'  => $email,
                    ':zfull'  => $fullname,
                ));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Member Inserted</div>';
                redirect($msg,'back');
                }else{
                    $msg = '<div class="alert alert-danger">Sorry This UserName Is Exist</div>';
                    redirect($msg,'back');
                }
            }
            echo '</div>';
        }else{
            echo '<div class="container">';
            echo '<div class="alert alert-danger">Soryy You Can Not Browse This Page Directly</div>';
            echo '</div>';
        }
    }elseif($do == 'Edit'){
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $con->prepare("SELECT * FROM Users WHERE UserID=? LIMIT 1");
        $stmt->execute(array($userid));
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        if($count>0){
        ?>
        <h2 class="text-center">Edit Member</h2>
        <div class="container">
            <form class="form-horizontal" action="?do=Update" method="post">
                <input type="hidden" name="userid" value="<?php echo $userid?>"/>
                <!-- start user name -->
                <div class="form-group form-group-lg">
                    <label class="control-label col-sm-2">User Name</label>
                    <div class="col-md-6 col-sm-10">
                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            required="required"
                            pattern=".{5,}"
                            title="User Name Must Be Large Than 5 Character"
                            placeholder="User Name To Enter To Dashboard"
                            value="<?php echo $row['UserName']?>"
                            autocomplete="off"/>
                    </div>
                </div>
                <!-- end user name -->
                <!-- start password -->
                <div class="form-group form-group-lg">
                    <label class="control-label col-sm-2">Password</label>
                    <div class="col-md-6 col-sm-10">
                        <input
                            type="password"
                            name="newpass"
                            class="form-control"
                            placeholder="Leave This Blank If You Do Not Want To Update Password"
                            autocomplete="new-password"/>
                        <input type="hidden" name="oldpass" value="<?php echo $row['Password']?>"/>
                    </div>
                </div>
                <!-- end password -->
                <!-- start full name -->
                <div class="form-group form-group-lg">
                    <label class="control-label col-sm-2">Full Name</label>
                    <div class="col-md-6 col-sm-10">
                        <input
                            type="text"
                            name="fullname"
                            class="form-control"
                            placeholder="Full Name Showin In Control Panel"
                            required="required"
                            pattern=".{10,}"
                            title="Full Name Must Be Large Than 10 Character"
                            value="<?php echo $row['FullName']?>"
                            autocomplete="off"/>
                    </div>
                </div>
                <!-- end full name -->
                <!-- start email -->
                <div class="form-group form-group-lg">
                    <label class="control-label col-sm-2">Email</label>
                    <div class="col-md-6 col-sm-10">
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required="required"
                            placeholder="Email To Contact"
                            value="<?php echo $row['Email']?>"
                            autocomplete="off"/>
                    </div>
                </div>
                <!-- end email -->
                <!-- start submit -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" class="btn btn-primary btn-lg" value="Update Member"/>
                    </div>
                </div>
                <!-- end submit -->
            </form>
        </div>
    <?php
        }else{
            echo '<div class="container">';
            $msg = '<div class="alert alert-danger">Sorry This User ID Is Not Exist</div>';
            redirect($msg,'back');
            echo '</div>';
        }
    }elseif($do == 'Update'){
        //check that user coming to this page using post request
        if($_SERVER['REQUEST_METHOD']=='POST'){
            echo '<h2 class="text-center">Update Member</h2>';
            echo '<div class="container">';
            //recieve data from form
            $u_id     = $_POST['userid'];
            $username = $_POST['username'];
            $fullname = $_POST['fullname'];
            $email    = $_POST['email'];
            $pass = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']);

            $formerrors = array();
            if(strlen($username)<5){
                $formerrors[] = 'User Name Can Not Be Less Than 4 Character';
            }
            if(strlen($fullname)<10){
                $formerrors[] = 'Full Name Can Not Be Less Than 10 Characters';
            }
            if(empty($email)){
                $formerrors[] = 'Email Can Not Be Empty';
            }
            foreach ($formerrors as $error){
                $msg = '<div class="alert alert-danger">' . $error . '</div>';
                redirect($msg,'back');
            }
            if(empty($formerrors)){
                $stmt = $con->prepare("SELECT * FROM Users WHERE UserName=? AND UserID!=?");
                $stmt->execute(array($username,$u_id));
                $count = $stmt->rowCount();
                if($count == 0){
                $stmt = $con->prepare("UPDATE users SET UserName=?,Password=?,Email=?,FullName=? WHERE UserID=?");
                $stmt->execute(array($username,$pass,$email,$fullname,$u_id));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Member Updated</div>';
                redirect($msg,'back');
                }else{
                    $msg = '<div class="alert alert-danger">Sorry This User Name Is Exist</div>';
                    redirect($msg,'back');
                }
            }
            echo '</div>';
        }else{
            echo '<div class="container">';
            $msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directrly</div>';
            redirect($msg,'back');
            echo '</div>';
        }
    }elseif($do == 'Delete'){
        echo '<h2 class="text-center">Delete Member</h2>';
        echo '<div class="container">';
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check = checkitem('UserID','Users',$userid);
        if($check>0){
            $stmt = $con->prepare("DELETE FROM Users WHERE UserID=:zuser");
            $stmt->bindParam(':zuser',$userid);
            $stmt->execute();
            $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Member Deleted</div>';
            redirect($msg,'back');
        }else{
            $msg ='<div class="alert alert-danger">Sorry This User ID Do Not Exist</div>';
            redirect($msg,'back');
        }
        echo '</div>';
    }

    include $tpl . 'footer.php';
}else{
    header('Location:index.php');
    exit();
}
ob_end_flush();
?>
