<?php
    ob_start();
    session_start();
    if(isset($_SESSION['username'])){
        $pagetitle = 'Match Goals';
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){
            $cupid = $_GET['cupid'];
            $matchid = $_GET['matchid'];
            $stmt = $con->prepare("SELECT cup_schdule.ID,
                                   player.PlayerName,
                                   player.PlayerNumber,
                                   match_goal.MatchGoalID,
                                   match_goal.MatchID,
                                   match_goal.PlayerID,
                                   match_goal.GoalTime
                              FROM (sport.match_goal match_goal
                                    INNER JOIN sport.player player
                                       ON (match_goal.PlayerID = player.PlayerID))
                                   INNER JOIN sport.cup_schdule cup_schdule
                                      ON (match_goal.MatchID = cup_schdule.ID)
                                      WHERE cup_schdule.CupID=?");
            $stmt->execute(array($cupid));
            $rows = $stmt->fetchAll();
            if(!empty($rows)){
            ?>
            <h2 class="text-center">Manage Match Goals</h2>
            <div class="container">
                <div class="table-responsive text-center main-table">
                    <table class="table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Player Name</td>
                            <td>Player Number</td>
                            <td>Goal Time</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row){
                            echo '<tr>';
                            echo '<td>' . $row['ID'] . '</td>';
                            echo '<td>' . $row['PlayerName'] . '</td>';
                            echo '<td>' . $row['PlayerNumber']    . '</td>';
                            echo '<td>' . $row['GoalTime']    . '</td>';
                            echo '<td>';
                            echo '<a href="?do=Edit&goalid='.$row['MatchGoalID'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                            echo '<a href="?do=Delete&goalid='.$row['MatchGoalID'].'" class="confirm btn btn-danger"><i class="fa fa-close"></i> Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
                <a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Match Goal</a>
            </div>
        <?php
            }else{
                echo '<div class="container">';
                echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
                echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Match Goal</a>';
                echo '</div>';
            }
        }elseif($do == 'Add'){?>
            <h2 class="text-center">Add New Match Goal</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post">
                    <!-- start match id -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Match</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="matchid" name="matchid" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM cup_schdule");
                                $stmt->execute();
                                $cups = $stmt->fetchAll();
                                foreach ($cups as $cup){
                                    echo '<option value="'.$cup['ID'].'">' . $cup['ID'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end match id -->
                    <!-- start player -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Player</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="playerid" name="playerid" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM player");
                                $stmt->execute();
                                $players = $stmt->fetchAll();
                                foreach ($players as $player){
                                    echo '<option value="'.$player['PlayerID'].'">' . $player['PlayerName'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end player -->
                    <!-- start goal time -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Goal Time</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="time"
                                    name="goaltime"
                                    class="form-control"
                                    placeholder="Goal Time"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end goal time-->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add Match Goal"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
        }elseif($do == 'Insert'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Insert Match Goals</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $matchid      = $_POST['matchid'];
                $playerid     = $_POST['playerid'];
                $goaltime     = $_POST['goaltime'];
                $formerorrs = array();
                if(empty($matchid)){
                    $formerorrs[] = 'Match ID Can Not Be Empty';
                }
                if(empty($playerid)){
                    $formerorrs[] = 'Player Can Not Be Empty';
                }
                if(empty($goaltime)){
                    $formerorrs[] = 'Goal Time Can Not Be Empty';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("INSERT INTO match_goal(MatchID, PlayerID, GoalTime) 
                                            VALUES (:zmatch,:zplayer,:zgoal)");
                    $stmt->execute(array(
                        ':zmatch'  => $matchid,
                        ':zplayer' => $playerid,
                        ':zgoal'   => $goaltime,));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Match Goal Inserted</div>';
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
            $goalid = isset($_GET['goalid']) && is_numeric($_GET['goalid']) ? intval($_GET['goalid']) : 0;
            $stmt = $con->prepare("SELECT cup_schdule.ID,
                                   player.PlayerName,
                                   player.PlayerNumber,
                                   match_goal.MatchGoalID,
                                   match_goal.MatchID,
                                   match_goal.PlayerID,
                                   match_goal.GoalTime
                              FROM (sport.match_goal match_goal
                                    INNER JOIN sport.player player
                                       ON (match_goal.PlayerID = player.PlayerID))
                                   INNER JOIN sport.cup_schdule cup_schdule
                                      ON (match_goal.MatchID = cup_schdule.ID)
                                      WHERE (match_goal.MatchGoalID = ?)");
            $stmt->execute(array($goalid));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count>0){
        ?>
            <h2 class="text-center">Edit Match Goal</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="goalid" value="<?php echo $goalid?>"/>
                    <!-- start match id -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Match</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="matchid" name="matchid" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM cup_schdule");
                                $stmt->execute();
                                $cups = $stmt->fetchAll();
                                foreach ($cups as $cup){
                                    echo '<option value="'.$cup['ID'].'"';
                                    if($cup['ID'] == $row['ID']){echo 'selected';}
                                    echo '>' . $cup['ID'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end match id -->
                    <!-- start player -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Player</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="playerid" name="playerid" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM player");
                                $stmt->execute();
                                $players = $stmt->fetchAll();
                                foreach ($players as $player){
                                    echo '<option value="'.$player['PlayerID'].'"';
                                    if($player['PlayerID'] == $row['PlayerID']){echo 'selected';}
                                    echo '>' . $player['PlayerName'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end player -->
                    <!-- start goal time -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Goal Time</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="time"
                                    name="goaltime"
                                    class="form-control"
                                    placeholder="Goal Time"
                                    value="<?php echo $row['GoalTime']?>"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end goal time -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Update Match Goal"/>
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
                echo '<h2 class="text-center">Update Match Goal</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $goalid     = $_POST['goalid'];
                $matchid    = $_POST['matchid'];
                $playerid   = $_POST['playerid'];
                $goaltime   = $_POST['goaltime'];
                $formerorrs = array();
                if(empty($matchid)){
                    $formerorrs[] = 'Match ID Can Not Be Empty';
                }
                if(empty($playerid)){
                    $formerorrs[] = 'Player Can Not Be Empty';
                }
                if(empty($goaltime)){
                    $formerorrs[] = 'Goal Time Can Not Be Empty';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("UPDATE match_goal SET MatchID=?,PlayerID=?,GoalTime=? WHERE MatchGoalID=?");
                    $stmt->execute(array($matchid,$playerid,$goaltime,$goalid));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Match Goal Updated</div>';
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
            echo '<h2 class="text-center">Delete Match Goal</h2>';
            echo '<div class="container">';
            $goalid = isset($_GET['goalid']) && is_numeric($_GET['goalid']) ? intval($_GET['goalid']) : 0;
            $check = checkitem('MatchGoalID','match_goal',$goalid);
            if($check>0){
                $stmt = $con->prepare("DELETE FROM match_goal WHERE MatchGoalID=?");
                $stmt->execute(array($goalid));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Match Goal Deleted</div>';
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
