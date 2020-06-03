<?php
    ob_start();
    session_start();
    if(isset($_SESSION['username'])){
        $pagetitle = 'Coach';
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){
            $stmt = $con->prepare("SELECT * FROM Coach ");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            if(!empty($rows)) {
                ?>
                <h2 class="text-center">Manage Team</h2>
                <div class="container">
                    <div class="table-responsive text-center main-table">
                        <table class="table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Coach Name</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {
                                echo '<tr>';
                                echo '<td>' . $row['CoachID'] . '</td>';
                                echo '<td>' . $row['CoachName'] . '</td>';
                                echo '<td>';
                                echo '<a href="?do=Edit&coachid=' . $row['CoachID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                                echo '<a href="?do=Delete&coachid=' . $row['CoachID'] . '" class="confirm btn btn-danger"><i class="fa fa-close"></i> Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                    <a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Coach</a>
                </div>
        <?php
            }else{
                echo '<div class="container">';
                echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
                echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Member</a>';
                echo '</div>';
            }
        }elseif($do == 'Add'){?>
            <h2 class="text-center">Add New Coach</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post">
                    <!-- start coach name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Coach Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="text"
                                name="coachname"
                                class="form-control"
                                placeholder="Coach Name"
                                required="required"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end coach name -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add New Coach"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
        }elseif($do == 'Insert'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Insert Coach</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $coachname = $_POST['coachname'];
                $formerorrs = array();
                if(empty($coachname)){
                    $formerorrs[] = 'Coach Name Can Not Be Empty';
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("INSERT INTO coach(CoachName)VALUES(:zname)");
                    $stmt->execute(array(':zname' => $coachname));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Coach Inserted</div>';
                    redirect($msg,'back');
                }
                echo '</div>';
            }else{
                echo '<div class="container">';
                $msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
                redirect($msg,'back');
                echo '</div>';
            }
        }elseif($do == 'Edit'){
            $coachid = isset($_GET['coachid']) && is_numeric($_GET['coachid']) ? intval($_GET['coachid']) : 0;
            $stmt = $con->prepare("SELECT * FROM coach WHERE CoachID=? LIMIT 1");
            $stmt->execute(array($coachid));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count>0){
        ?>
            <h2 class="text-center">Edit Coach</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="coachid" value="<?php echo $coachid?>"/>
                    <!-- start coach name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Coach Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="text"
                                name="coachname"
                                class="form-control"
                                placeholder="Coach Name"
                                required="required"
                                value="<?php echo $row['CoachName']?>"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end coach name -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Update Coach"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
            }else{
                echo '<div class="container">';
                $msg = '<div class="alert alert-danger">Sorry This Coach ID Is Not Exist</div>';
                redirect($msg,'back');
                echo '</div>';
            }
        }elseif($do == 'Update'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Update Coach</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $coach_id  = $_POST['coachid'];
                $coachname = $_POST['coachname'];
                $formerorrs = array();
                if(strlen($coachname)<5){
                    $formerorrs[] = 'Coach Name Can Not Be Less Than 5 Character';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("UPDATE coach SET CoachName=? WHERE CoachID=?");
                    $stmt->execute(array($coachname,$coach_id));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Coach Updated</div>';
                    redirect($msg,'back');
                }
                echo '</div>';
            }else{
                echo '<div class="container">';
                $msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
                redirect($msg,'back');
                echo '</div>';
            }
        }elseif($do == 'Delete'){
            echo '<h2 class="text-center">Delete Coach</h2>';
            echo '<div class="container">';
            $coachid = isset($_GET['coachid']) && is_numeric($_GET['coachid']) ? intval($_GET['coachid']) : 0;
            $check = checkitem('CoachID','Coach',$coachid);
            if($check>0){
                $stmt = $con->prepare("DELETE FROM Coach WHERE CoachID=?");
                $stmt->execute(array($coachid));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Coach Deleted</div>';
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
