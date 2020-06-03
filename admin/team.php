<?php
    ob_start();
    session_start();
    if(isset($_SESSION['username'])){
        $pagetitle = 'Team';
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){
            $stmt = $con->prepare("SELECT Team.* ,Coach.* FROM Team INNER JOIN Coach ON Team.CoachID=Coach.CoachID ");
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
                                <td>Team Name</td>
                                <td>Coach Name</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {
                                echo '<tr>';
                                echo '<td>' . $row['TeamID'] . '</td>';
                                echo '<td>' . $row['TeamName'] . '</td>';
                                echo '<td>' . $row['CoachName'] . '</td>';
                                echo '<td>';
                                echo '<a href="?do=Edit&teamid=' . $row['TeamID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                                echo '<a href="?do=Delete&teamid=' . $row['TeamID'] . '" class="confirm btn btn-danger"><i class="fa fa-close"></i> Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>
                    <a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Team</a>
                </div>
        <?php
            }else{
                echo '<div class="container">';
                echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
                echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Team</a>';
                echo '</div>';
            }
        }elseif($do == 'Add'){?>
            <h2 class="text-center">Add New Team</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post">
                    <!-- start team name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Team Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="text"
                                name="teamname"
                                class="form-control"
                                placeholder="Team Name"
                                required="required"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end Team name -->
                    <!-- start coach Name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Coach</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="CoachID" name="CoachID" class="form-control" onChange="NewCoach()">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM coach");
                                $stmt->execute();
                                $coachs = $stmt->fetchAll();
                                foreach ($coachs as $coach){
                                    echo '<option value="'.$coach['CoachID'].'">' . $coach["CoachName"] . '</option>';
                                }
                                ?>
                                <option value="-1">New Coach</option>
                            </select>
                        </div>
                    </div>
                    <script type="text/javascript">
                        function NewCoach(){
                            var CoachID = document.getElementById("CoachID").value;
                            var NewCoachField = '<div class="form-group form-group-lg">\
                                <label class="control-label col-md-2">Coach Name</label>\
                            <div class="col-sm-10 col-md-6">\
                                <input type="text" id="CoachName" name="CoachName" value="" class="form-control" placeholder="Coach Name" required="required" autocomplete="off"/>\
                                </div>\
                                </div>';
                            if(CoachID == '-1'){
                                document.getElementById("NewCoachProcessArea").innerHTML = NewCoachField;
                            }else{
                                document.getElementById("NewCoachProcessArea").innerHTML = '';
                            }


                        }
                    </script>
                    <div id="NewCoachProcessArea">
                    </div>
                    <!-- end coach Name -->
                    <!-- start continent -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Continent</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="continent" name="continent" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM continent");
                                $stmt->execute();
                                $continents = $stmt->fetchAll();
                                foreach ($continents as $cont){
                                    echo '<option value="'.$cont['ID'].'">' . $cont["Name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- start continent -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add New Team"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
        }elseif($do == 'Insert'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Insert Team</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $teamname    = $_POST['teamname'];
                $CoachID     = $_POST['CoachID'];
                $continentid = $_POST['continent'];
                $formerorrs = array();
                if(strlen($teamname)<5){
                    $formerorrs[] = 'Team Name Can Not Be Less Than 5 Character';
                }
                if(empty($continentid)){
                    $formerorrs[] = 'Continent Can Not Be Empty';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    if($CoachID == '-1'){
                        $CoachName = $_POST['CoachName'];
                        $stmt2 = $con->prepare("INSERT INTO coach(CoachName)VALUES(:zname)");
                        $stmt2->execute(array(':zname'=>$CoachName));
                        $CoachID = $con->lastInsertId();
                    }
                    $stmt1 = $con->prepare("SELECT * FROM team WHERE TeamName=?");
                    $stmt1->execute(array($teamname));
                    $count = $stmt1->rowCount();
                    if($count == 0){
                    $stmt = $con->prepare("INSERT INTO team(TeamName,CoachID)VALUES(:zname,:zcoach)");
                    $stmt->execute(array(
                        ':zname' => $teamname,
                        ':zcoach' => $CoachID ));
                    $teamid = $con->lastInsertId();
                    $stmt2 = $con->prepare("INSERT INTO continent_team(T_ID,C_ID)VALUE (:zteam,:zcon)");
                    $stmt2->execute(array(
                            ':zteam'=>$teamid,
                            ':zcon'=>$continentid));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Team Inserted</div>';
                    redirect($msg,'back');
                }else{
                        $msg = '<div class="alert alert-danger">Sorry This Team Name Is Exist</div>';
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
            $teamid = isset($_GET['teamid']) && is_numeric($_GET['teamid']) ? intval($_GET['teamid']) : 0;
            $stmt = $con->prepare("SELECT team.*,continent_team.* FROM team INNER JOIN continent_team ON team.TeamID = continent_team.T_ID WHERE TeamID=? LIMIT 1");
            $stmt->execute(array($teamid));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count>0){
        ?>
            <h2 class="text-center">Edit Team</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="teamid" value="<?php echo $teamid?>"/>
                    <!-- start team name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Team Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="text"
                                name="teamname"
                                class="form-control"
                                placeholder="Team Name"
                                required="required"
                                value="<?php echo $row['TeamName']?>"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end team name -->
                    <!-- start continent -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Continent</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="continent" name="continent" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM continent");
                                $stmt->execute();
                                $continents = $stmt->fetchAll();
                                foreach ($continents as $cont){
                                    echo '<option value="'.$cont['ID'].'"';
                                    if($row['C_ID'] == $cont['ID']){echo 'selected';}
                                    echo '>' . $cont["Name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end continent -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Update Team"/>
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
                echo '<h2 class="text-center">Update Team</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $team_id  = $_POST['teamid'];
                $teamname = $_POST['teamname'];
                $continentid = $_POST['continent'];
                $formerorrs = array();
                if(strlen($teamname)<5){
                    $formerorrs[] = 'Team Name Can Not Be Less Than 5 Character';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt2 = $con->prepare("SELECT * FROM Team WHERE TeamName=? AND TeamID!=?");
                    $stmt2->execute(array($teamname,$team_id));
                    $count = $stmt2->rowCount();
                    if($count == 0){
                    $stmt = $con->prepare("UPDATE team INNER JOIN continent_team ON team.TeamID=continent_team.T_ID SET TeamName=?,C_ID=? WHERE TeamID=?");
                    $stmt->execute(array($teamname,$continentid,$team_id));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Team Updated</div>';
                    redirect($msg,'back');
                }else{
                        $msg = '<div class="alert alert-danger">Sorry This Team Name Is Exist</div>';
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
            echo '<h2 class="text-center">Delete Team</h2>';
            echo '<div class="container">';
            $teamid = isset($_GET['teamid']) && is_numeric($_GET['teamid']) ? intval($_GET['teamid']) : 0;
            $check = checkitem('TeamID','Team',$teamid);
            if($check>0){
                $stmt = $con->prepare("DELETE FROM Team WHERE TeamID=?");
                $stmt->execute(array($teamid));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Team Deleted</div>';
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
