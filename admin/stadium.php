<?php
    ob_start();
    session_start();
    if(isset($_SESSION['username'])){
        $pagetitle = 'Stadium';
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){
            $stmt = $con->prepare("SELECT * FROM Stadium");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            if(!empty($rows)) {
                ?>
                <h2 class="text-center">Manage Stadium</h2>
                <div class="container">
                    <div class="table-responsive text-center main-table">
                        <table class="table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Stadium Name</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {
                                echo '<tr>';
                                echo '<td>' . $row['StadiumID'] . '</td>';
                                echo '<td>' . $row['StadiumName'] . '</td>';
                                echo '<td>';
                                echo '<a href="?do=Edit&stadiumid=' . $row['StadiumID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                                echo '<a href="?do=Delete&stadiumid=' . $row['StadiumID'] . '" class="confirm btn btn-danger"><i class="fa fa-close"></i> Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                    <a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Stadium</a>
                </div>
        <?php
            }else{
                echo '<div class="container">';
                echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
                echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Stadium</a>';
                echo '</div>';
            }
        }elseif($do == 'Add'){?>
            <h2 class="text-center">Add New Stadium</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post">
                    <!-- start stadium name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Stadium Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="text"
                                name="stadiumname"
                                class="form-control"
                                placeholder="Stadium Name"
                                required="required"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end stadium name -->

                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add New Stadium"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
        }elseif($do == 'Insert'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Insert Stadium</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $stadiumname    = $_POST['stadiumname'];
                $formerorrs = array();
                if(strlen($stadiumname)<5){
                    $formerorrs[] = 'Stadium Name Can Not Be Less Than 5 Character';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt1 = $con->prepare("SELECT * FROM stadium WHERE StadiumName=?");
                    $stmt1->execute(array($stadiumname));
                    $count = $stmt1->rowCount();
                    if($count == 0){
                    $stmt = $con->prepare("INSERT INTO stadium(StadiumName)VALUES(:zname)");
                    $stmt->execute(array(
                        ':zname' => $stadiumname, ));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Stadium Inserted</div>';
                    redirect($msg,'back');
                }else{
                        $msg = '<div class="alert alert-danger">Sorry This Stadium Name Is Exist</div>';
                        redirect($msg,'back');

                    }
                }
                echo '</div>';
            }else{
                echo '<div class="container">';
                $msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
                redirect($msg,'back');
                echo '</div>';
            }
        }elseif($do == 'Edit'){
            $stadiumid = isset($_GET['stadiumid']) && is_numeric($_GET['stadiumid']) ? intval($_GET['stadiumid']) : 0;
            $stmt = $con->prepare("SELECT * FROM Stadium WHERE StadiumID=? LIMIT 1 ");
            $stmt->execute(array($stadiumid));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count>0){
        ?>
            <h2 class="text-center">Edit Stadium</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="stadiumid" value="<?php echo $stadiumid?>"/>
                    <!-- start stadium name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Stadium Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="text"
                                    name="stadiumname"
                                    class="form-control"
                                    placeholder="Stadium Name"
                                    value="<?php echo $row['StadiumName']?>"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end stadium name -->

                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Update Stadium"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
            }else{
                echo '<div class="container">';
                $msg = '<div class="alert alert-danger">Sorry This Team ID Is Not Exist</div>';
                redirect($msg,'back');
                echo '</div>';
            }
        }elseif($do == 'Update'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Update Stadium</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $stadiumid   = $_POST['stadiumid'];
                $stadiumname = $_POST['stadiumname'];
                $formerorrs = array();
                if(strlen($stadiumname)<5){
                    $formerorrs[] = 'Stadium Can Not Be Less Than 5 Character';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt2 = $con->prepare("SELECT * FROM Stadium WHERE StadiumName=? AND StadiumID!=?");
                    $stmt2->execute(array($stadiumname,$stadiumid));
                    $count = $stmt2->rowCount();
                    if($count == 0){
                    $stmt = $con->prepare("UPDATE stadium SET StadiumName = ? WHERE StadiumID=?");
                    $stmt->execute(array($stadiumname,$stadiumid));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Stadium Updated</div>';
                    redirect($msg,'back');
                }else{
                        $msg = '<div class="alert alert-danger">Sorry This Stadium Name Is Exist</div>';
                        redirect($msg,'back');
                    }
                }
                echo '</div>';
            }else{
                echo '<div class="container">';
                $msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
                redirect($msg,'back');
                echo '</div>';
            }
        }elseif($do == 'Delete'){
            echo '<h2 class="text-center">Delete Stadium</h2>';
            echo '<div class="container">';
            $stadiumid = isset($_GET['stadiumid']) && is_numeric($_GET['stadiumid']) ? intval($_GET['stadiumid']) : 0;
            $check = checkitem('StadiumID','stadium',$stadiumid);
            if($check>0){
                $stmt = $con->prepare("DELETE FROM stadium WHERE StadiumID=?");
                $stmt->execute(array($stadiumid));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Stadium Deleted</div>';
                redirect($msg,'back');
            }else{
                $msg = '<div class="alert alert-danger">Sorry There Is No Such ID</div>';
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
