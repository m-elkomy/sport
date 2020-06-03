<?php
    ob_start();
    session_start();
    if(isset($_SESSION['username'])){
        $pagetitle = 'Cup_Teams';
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){
            $cupid = $_GET['cupid'];
            $stmt = $con->prepare("SELECT cup_team.* ,cup.*,team.* FROM cup_team INNER JOIN cup ON cup_team.CupID = cup.CupID INNER JOIN team ON cup_team.TeamID=team.TeamID WHERE  cup_team.CupID=?");
            $stmt->execute(array($cupid));
            $rows = $stmt->fetchAll();
            if(!empty($rows)){
            ?>
            <h2 class="text-center">Manage Cup_Teams</h2>
            <div class="container">
                <div class="table-responsive text-center main-table">
                    <table class="table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Cup Name</td>
                            <td>Start Date</td>
                            <td>End Date</td>
                            <td>Team</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row){
                            echo '<tr>';
                            echo '<td>' . $row['CupID'] . '</td>';
                            echo '<td>' . $row['CupName'] . '</td>';
                            echo '<td>' . $row['StartDate']    . '</td>';
                            echo '<td>' . $row['EndDate']    . '</td>';
                            echo '<td>' . $row['TeamName']    . '</td>';
                            echo '<td>';
                            echo '<a href="?do=Edit&cupid='.$row['CupID'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                            echo '<a href="?do=Delete&cupid='.$row['CupID'].'" class="confirm btn btn-danger"><i class="fa fa-close"></i> Delete</a>';
                            echo '<a href="cup_scheduel.php?cupid='.$row['CupID'].'" class="btn btn-info"><i class="fa fa-check"></i> Scheduel</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
                <a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Cup Team</a>
            </div>
        <?php
            }else{
                echo '<div class="container">';
                echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
                echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Cup Team</a>';
                echo '</div>';
            }
        }elseif($do == 'Add'){?>
            <h2 class="text-center">Add New Cup_Teams</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post">
                    <!-- start cup id -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Cup</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="CupID" name="CupID" class="form-control" onChange="NewCup()">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM Cup");
                                $stmt->execute();
                                $cups = $stmt->fetchAll();
                                foreach ($cups as $cup){
                                    echo '<option value="'.$cup['CupID'].'">' . $cup["CupName"] . '</option>';
                                }
                                ?>
                                <option value="-1">New Cup</option>
                            </select>
                        </div>
                    </div>
                    <script type="text/javascript">
                        function NewCup(){
                            var CupID = document.getElementById("CupID").value;
                            var NewCoachField = '<div class="form-group form-group-lg">\
                                <label class="control-label col-md-2">Cup Name</label>\
                            <div class="col-sm-10 col-md-6">\
                                <input type="text" id="CupName" name="CupName" value="" class="form-control" placeholder="Cup Name" required="required" autocomplete="off"/>\
                                </div>\
                                </div>';
                            if(CupID == '-1'){
                                document.getElementById("NewCoachProcessArea").innerHTML = NewCoachField;
                            }else{
                                document.getElementById("NewCoachProcessArea").innerHTML = '';
                            }


                        }
                    </script>
                    <div id="NewCoachProcessArea">
                    </div>
                    <!-- end cup id -->
                    <!-- start starting date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Start Date</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="date"
                                    name="startdate"
                                    class="form-control"
                                    placeholder="Starting Date"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end start date -->
                    <!-- start ending date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">End Date</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="date"
                                    name="enddate"
                                    class="form-control"
                                    placeholder="Ending Date"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end Ending date -->
                    <!-- start  team -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Team</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="TeamName" name="TeamName" class="form-control"">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM Team");
                                $stmt->execute();
                                $teams = $stmt->fetchAll();
                                foreach ($teams as $team){
                                    echo '<option value="'.$team['TeamID'].'">' . $team["TeamName"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end  team -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add New Cup Team"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
        }elseif($do == 'Insert'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Insert Cup_Teams</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $CupID      = $_POST['CupID'];
                $startdate  = $_POST['startdate'];
                $enddate    = $_POST['enddate'];
                $teamname   = $_POST['TeamName'];
                $formerorrs = array();
                if(empty($CupID)){
                    $formerorrs[] = 'Cup Can Not Be Empty';
                }
                if(empty($startdate)){
                    $formerorrs[] = 'Start Date Can Not Be Empty';
                }
                if(empty($enddate)){
                    $formerorrs[] = 'End Date Can Not Be Empty';
                }
                if(empty($formerorrs)){
                    if($CupID == '-1'){
                        $CupName = $_POST['CupName'];
                        $stmt2 = $con->prepare("INSERT INTO Cup(CupName,StartDate,EndDate)VALUES(:zname,:zstart,:zend)");
                        $stmt2->execute(array(
                                ':zname'=>$CupName,
                                ':zstart'=> $startdate,
                                ':zend'  => $enddate
                        ));
                        $CupID = $con->lastInsertId();
                    }

                    $stmt = $con->prepare("INSERT INTO cup_team(CupID,TeamID)VALUES(:zcup,:zteam)");
                    $stmt->execute(array(
                        ':zcup' => $CupID,
                        ':zteam' => $teamname ));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Cup_Team Inserted</div>';
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
            $cupid = isset($_GET['cupid']) && is_numeric($_GET['cupid']) ? intval($_GET['cupid']) : 0;
            $stmt = $con->prepare("SELECT cup.*,cup_team.* FROM cup INNER JOIN cup_team ON cup.CupID = cup_team.CupID WHERE cup.CupID=? LIMIT 1");
            $stmt->execute(array($cupid));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count>0){
        ?>
            <h2 class="text-center">Edit Cup</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="cupid" value="<?php echo $cupid?>"/>
                    <!-- start cup id -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Cup</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="CupID" name="CupID" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM Cup");
                                $stmt->execute();
                                $cups = $stmt->fetchAll();
                                foreach ($cups as $cup){
                                    echo '<option value="'.$cup['CupID'].'"';
                                    if($row['CupID'] == $cup['CupID']){echo 'selected';}
                                    echo '>' . $cup["CupName"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end cup id -->
                    <!-- start starting date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Start Date</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="date"
                                    name="startdate"
                                    class="form-control"
                                    placeholder="Starting Date"
                                    value="<?php echo $row['StartDate']?>"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end start date -->
                    <!-- start ending date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">End Date</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="date"
                                    name="enddate"
                                    class="form-control"
                                    placeholder="Ending Date"
                                    value="<?php echo $row['EndDate']?>"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end Ending date -->
                    <!-- start  team -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Team</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="TeamName" name="TeamName" class="form-control"">
                            <option value="0">....</option>
                            <?php
                            $stmt = $con->prepare("SELECT * FROM Team");
                            $stmt->execute();
                            $teams = $stmt->fetchAll();
                            foreach ($teams as $team){
                                echo '<option value="'.$team['TeamID'].'"';
                                if($row['TeamID'] == $team['TeamID']){echo 'selected';}
                                echo '>' . $team["TeamName"] . '</option>';
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <!-- end  team -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Update Cup_Team"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
            }else{
                echo '<div class="container">';
                $msg = '<div class="alert alert-danger">Sorry This Cup ID Is Not Exist</div>';
                redirect($msg,'back');
                echo '</div>';
            }
        }elseif($do == 'Update'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Update Cup_Team</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $cup_id     = $_POST['cupid'];
                $startdate  = $_POST['startdate'];
                $enddate    = $_POST['enddate'];
                $teamname   = $_POST['TeamName'];
                $formerorrs = array();
                if(empty($startdate)){
                    $formerorrs[] = 'Start Date Can Not Be Empty';
                }
                if(empty($enddate)){
                    $formerorrs[] = 'End Date Can Not Be Empty';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("UPDATE Cup INNER JOIN cup_team ON cup.CupID = cup_team.CupID SET cup.StartDate=?,cup.EndDate=?,cup_team.TeamID=? WHERE cup.CupID=?");
                    $stmt->execute(array($startdate,$enddate,$teamname,$cup_id));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Cup Updated</div>';
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
            echo '<h2 class="text-center">Delete Cup_Team</h2>';
            echo '<div class="container">';
            $cupid = isset($_GET['cupid']) && is_numeric($_GET['cupid']) ? intval($_GET['cupid']) : 0;
            $check = checkitem('CupID','Cup',$cupid);
            if($check>0){
                $stmt = $con->prepare("DELETE FROM Cup WHERE CupID=?");
                $stmt->execute(array($cupid));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Cup Deleted</div>';
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
