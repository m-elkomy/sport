<?php
    ob_start();
    session_start();
    if(isset($_SESSION['username'])){
        $pagetitle = 'Player';
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){
            $stmt = $con->prepare("SELECT Player.*,Team.* FROM Player INNER JOIN Team ON Player.TeamID=Team.TeamID");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            if(!empty($rows)){
            ?>
            <h2 class="text-center">Manage Player</h2>
            <div class="container">
                <div class="table-responsive text-center main-table">
                    <table class="table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Player Name</td>
                            <td>Player Number</td>
                            <td>Team Name</td>
                            <td>Player DOB</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row){
                            echo '<tr>';
                            echo '<td>' . $row['PlayerID'] . '</td>';
                            echo '<td>' . $row['PlayerName'] . '</td>';
                            echo '<td>' . $row['PlayerNumber'] . '</td>';
                            echo '<td>' . $row['TeamName']    . '</td>';
                            echo '<td>' . $row['DOB'] . '</td>';
                            echo '<td>';
                            echo '<a href="?do=Edit&playerid='.$row['PlayerID'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                            echo '<a href="?do=Delete&playerid='.$row['PlayerID'].'" class="confirm btn btn-danger"><i class="fa fa-close"></i> Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
                <a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Player</a>
            </div>
        <?php
            }else{
                echo '<div class="container">';
                echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
                echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Player</a>';
                echo '</div>';
            }
        }elseif($do == 'Add'){?>
            <h2 class="text-center">Add New Player</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post">
                    <!-- start player name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Player Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="text"
                                name="playername"
                                class="form-control"
                                placeholder="Player Name"
                                required="required"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end player name -->
                    <!-- start player number -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Player Number</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="number"
                                name="playernum"
                                class="form-control"
                                placeholder="Player Number"
                                required="required"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end player number -->
                    <!-- start player dob -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Player DOB</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="date"
                                name="DOB"
                                class="form-control"
                                placeholder="Player Date OF Birth"
                                required="required"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end player dob -->
                    <!-- start player team -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Team</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="team" class="form-control">
                                <option value="0">....</option>
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM Team");
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll();
                                    foreach ($rows as $row){
                                        echo '<option value="'.$row['TeamID'].'">' . $row["TeamName"] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end player team -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add New Player"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
        }elseif($do == 'Insert'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Insert Player</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $playername = $_POST['playername'];
                $playernum  = $_POST['playernum'];
                $playerdob  = $_POST['DOB'];
                $teamname   = $_POST['team'];
                $formerorrs = array();
                if(strlen($playername)<5){
                    $formerorrs[] = 'Player Name Can Not Be Less Than 5 Character';
                }
                if(intval($playername)){
                    $formerorrs[] = 'Player Number Must Be Integer Value';
                }
                if(empty($playerdob)){
                    $formerorrs[] = 'Player Date Of Birth Can Not Be Empty';
                }
                if($teamname == 0){
                    $formerorrs[] = 'You Must Choose Team';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt1 = $con->prepare("DELETE FROM player WHERE PlayerName=? AND PlayerNumber=? AND DOB=?");
                    $stmt1->execute(array($playername,$playernum,$playerdob));
                    if($stmt1){
                    $stmt = $con->prepare("INSERT INTO player(PlayerName,PlayerNumber,DOB,TeamID)VALUES(:zname,:znum,:zdob,:zteam)");
                    $stmt->execute(array(
                        ':zname' => $playername,
                        ':znum'  => $playernum,
                        ':zdob'  => $playerdob,
                        ':zteam' => $teamname
                    ));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Player Inserted</div>';
                    redirect($msg,'back');
                }else{
                        $msg = '<div class="alert alert-danger">Sorry</div>';
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
            $playerid = isset($_GET['playerid']) && is_numeric($_GET['playerid']) ? intval($_GET['playerid']) : 0;
            $stmt = $con->prepare("SELECT * FROM Player WHERE PlayerID=? LIMIT 1");
            $stmt->execute(array($playerid));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count>0){
        ?>
            <h2 class="text-center">Edit Player</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="playerid" value="<?php echo $playerid?>"/>
                    <!-- start player name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Player Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="text"
                                name="playername"
                                class="form-control"
                                placeholder="Player Name"
                                required="required"
                                value="<?php echo $row['PlayerName']?>"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end player name -->
                    <!-- start player number -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Player Number</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="number"
                                name="playernum"
                                class="form-control"
                                placeholder="Player Number"
                                value="<?php echo $row['PlayerNumber']?>"
                                required="required"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end player number -->
                    <!-- start player dob -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Player DOB</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="date"
                                name="DOB"
                                class="form-control"
                                placeholder="Player Date OF Birth"
                                required="required"
                                value="<?php echo $row['DOB']?>"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end player dob -->
                    <!-- start player team -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Team</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="team" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM Team");
                                $stmt->execute();
                                $teams = $stmt->fetchAll();
                                foreach ($teams as $team){
                                    echo '<option value="'.$team['TeamID'].'"';
                                    if($row['TeamID'] == $team['TeamID']){echo 'selected';}
                                    echo '>' . $team['TeamName'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end player team -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Update Player"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
            }else{
                echo '<div class="container">';
                $msg = '<div class="alert alert-danger">Sorry This Player ID Is Not Exist</div>';
                redirect($msg,'back');
                echo '</div>';
            }
        }elseif($do == 'Update'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Update Player</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $player_id  = $_POST['playerid'];
                $playername = $_POST['playername'];
                $playernum  = $_POST['playernum'];
                $playerdob  = $_POST['DOB'];
                $teamname   = $_POST['team'];
                $formerorrs = array();
                if(strlen($playername)<5){
                    $formerorrs[] = 'Player Name Can Not Be Less Than 5 Character';
                }
                if(intval($playername)){
                    $formerorrs[] = 'Player Number Must Be Integer Value';
                }
                if(empty($playerdob)){
                    $formerorrs[] = 'Player Date Of Birth Can Not Be Empty';
                }
                if($teamname == 0){
                    $formerorrs[] = 'You Must Choose Team';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("UPDATE Player SET PlayerName=?,PlayerNumber=?,DOB=?,TeamID=? WHERE PlayerID=?");
                    $stmt->execute(array($playername,$playernum,$playerdob,$teamname,$player_id));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Player Updated</div>';
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
            echo '<h2 class="text-center">Delete Member</h2>';
            echo '<div class="container">';
            $playerid = isset($_GET['playerid']) && is_numeric($_GET['playerid']) ? intval($_GET['playerid']) : 0;
            $check = checkitem('PlayerID','Player',$playerid);
            if($check>0){
                $stmt = $con->prepare("DELETE FROM Player WHERE PlayerID=?");
                $stmt->execute(array($playerid));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Deleted</div>';
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
