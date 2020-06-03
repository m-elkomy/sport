<?php
    ob_start();
    session_start();
    if(isset($_SESSION['username'])){
        $pagetitle = 'Cup_Scheduel';
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){
            $cupid = $_GET['cupid'];
            $stmt = $con->prepare("SELECT cup_schdule.ID AS MatchID,
                       cup_schdule.CupID AS CupID,
                       cup_schdule.MatchTime AS MatchTime,
                       cup.CupName AS CupName,
                       cup_schdule.Team1ID AS Team1ID,
                       team.TeamName AS Team1Name,
                       cup_schdule.Team2ID AS Team2ID,
                       team_1.TeamName AS Team2Name,
                       cup_schdule.MatchDate AS MatchDate,
                       cup_schdule.StadiumID AS StaduimID,
                       stadium.StadiumName AS StaduimName,
                       cup_schdule.Round AS CupRound
                  FROM (((cup_schdule cup_schdule
                          INNER JOIN team team_1 ON (cup_schdule.Team2ID = team_1.TeamID))
                         INNER JOIN cup cup ON (cup_schdule.CupID = cup.CupID))
                        INNER JOIN team team ON (cup_schdule.Team1ID = team.TeamID))
                       INNER JOIN stadium stadium
                          ON (cup_schdule.StadiumID = stadium.StadiumID)
                where cup_schdule.CupID = ?
                ORDER BY MatchDate ASC");
            $stmt->execute(array($cupid));
            $rows = $stmt->fetchAll();
            if(!empty($rows)){
            ?>
            <h2 class="text-center">Manage Cup Scheduel</h2>
            <div class="container">
                <div class="table-responsive text-center main-table">
                    <table class="table table-bordered">
                        <tr>
                            <td>#Match ID</td>
                            <td>Cup Name</td>
                            <td>Team 1 Name</td>
                            <td>Team 2 Name</td>
                            <td>Stadium Name</td>
                            <td>Match Date</td>
                            <td>Match Time</td>
                            <td>Round</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row){
                            echo '<tr>';
                            echo '<td>' . $row['CupID'] . '</td>';
                            echo '<td>' . $row['CupName'] . '</td>';
                            echo '<td>' . $row['Team1Name']    . '</td>';
                            echo '<td>' . $row['Team2Name']    . '</td>';
                            echo '<td>' . $row['StaduimName']    . '</td>';
                            echo '<td>' . $row['MatchDate']    . '</td>';
                            echo '<td>' . $row['MatchTime']    . '</td>';
                            echo '<td>' . $row['CupRound']    . '</td>';
                            echo '<td>';
                            echo '<a href="?do=Edit&matchid='.$row['MatchID'].'&cupid='.$row['CupID'].'" class="btn btn-xs btn-success"><i class="fa fa-edit"></i> Edit</a>';
                            echo '<a href="?do=Delete&matchid='.$row['MatchID'].'&cupid='.$row['CupID'].'" class="confirm btn-xs btn btn-danger"><i class="fa fa-close"></i> Delete</a><br>';
                            echo '<a href="match_goals.php?matchid='.$row['MatchID'].'&cupid='.$row['CupID'].'" class="btn btn-xs btn-info"><i class="fa fa-check"></i> Goals</a>';
                            echo '<a href="match_cards.php?matchid='.$row['MatchID'].'&cupid='.$row['CupID'].'" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Cards</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
                <a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Match</a>
            </div>
        <?php
            }else{
                echo '<div class="container">';
                echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
                echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Match</a>';
                echo '</div>';
            }
        }elseif($do == 'Add'){?>
            <h2 class="text-center">Add New Cup_Schduel</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post">
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
                                    echo '<option value="'.$cup['CupID'].'">' . $cup["CupName"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end cup id -->
                    <!-- start team one -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Team One</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="TeamoneName" name="TeamoneName" class="form-control"">
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
                    <!-- end team one -->
                    <!-- start team two -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Team Two</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="TeamtwoName" name="TeamtwoName" class="form-control"">
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
                    <!-- end team two -->
                    <!-- start stadium -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Stadium</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="stadium" name="stadium" class="form-control"">
                            <option value="0">....</option>
                            <?php
                            $stmt = $con->prepare("SELECT * FROM stadium");
                            $stmt->execute();
                            $stadiums = $stmt->fetchAll();
                            foreach ($stadiums as $stad){
                                echo '<option value="'.$stad['StadiumID'].'">' . $stad["StadiumName"] . '</option>';
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <!-- end stadium -->
                    <!-- start match date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Match Date</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="date"
                                    name="matchdate"
                                    class="form-control"
                                    placeholder="Match Date"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end match date -->
                    <!-- start match time -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Match Time</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="time"
                                    name="matchtime"
                                    class="form-control"
                                    placeholder="Match Time"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end match time -->
                    <!-- start Round -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Round</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="number"
                                    name="round"
                                    min="0"
                                    class="form-control"
                                    placeholder="Round"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end Round -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add Match"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
        }elseif($do == 'Insert'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Insert Cup_Scheduel</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $CupID      = $_POST['CupID'];
                $matchdate  = $_POST['matchdate'];
                $teamone    = $_POST['TeamoneName'];
                $teamtwo    = $_POST['TeamtwoName'];
                $stadium    = $_POST['stadium'];
                $round      = $_POST['round'];
                $matchtime  = $_POST['matchtime'];
                $formerorrs = array();
                if(empty($CupID)){
                    $formerorrs[] = 'Cup Can Not Be Empty';
                }
                if(empty($matchdate)){
                    $formerorrs[] = 'Match Date Can Not Be Empty';
                }
                if(empty($matchtime)){
                    $formerorrs[] = 'Match Time Can Not Be Empty';
                }
                if(empty($round)){
                    $formerorrs[] = 'Round Can Not Be Empty';
                }
                if($teamone == 0){
                    $formerorrs[] = 'You Must Choose Team One';
                }
                if($teamtwo == 0){
                    $formerorrs[] = 'You Must Choose Team Two';
                }
                if(empty($stadium)){
                    $formerorrs[] = 'Stadium Can Not Be Empty';
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("INSERT INTO cup_schdule(CupID, Team1ID, Team2ID, MatchDate,MatchTime, StadiumID, Round) 
                                            VALUES (:zcup,:zteam1,:zteam2,:zdate,:ztime,:zstad,:zround)");
                    $stmt->execute(array(
                        ':zcup' => $CupID,
                        ':zteam1' => $teamone,
                        ':zteam2' => $teamtwo,
                        ':zdate'  => $matchdate,
                        ':ztime'  => $matchtime,
                        ':zstad'  => $stadium,
                        ':zround' => $round));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Cup _Scheduel Inserted</div>';
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
            $matchid = isset($_GET['matchid']) && is_numeric($_GET['matchid']) ? intval($_GET['matchid']) : 0;
            $stmt = $con->prepare("SELECT cup_schdule.ID AS MatchID,
                       cup_schdule.CupID AS CupID,
                       cup_schdule.MatchTime AS MatchTime,
                       cup.CupName AS CupName,
                       cup_schdule.Team1ID AS Team1ID,
                       team.TeamName AS Team1Name,
                       cup_schdule.Team2ID AS Team2ID,
                       team_1.TeamName AS Team2Name,
                       cup_schdule.MatchDate AS MatchDate,
                       cup_schdule.StadiumID AS StaduimID,
                       stadium.StadiumName AS StaduimName,
                       cup_schdule.Round AS CupRound
                  FROM (((cup_schdule cup_schdule
                          INNER JOIN team team_1 ON (cup_schdule.Team2ID = team_1.TeamID))
                         INNER JOIN cup cup ON (cup_schdule.CupID = cup.CupID))
                        INNER JOIN team team ON (cup_schdule.Team1ID = team.TeamID))
                       INNER JOIN stadium stadium
                          ON (cup_schdule.StadiumID = stadium.StadiumID)
                where cup_schdule.CupID = ?
                ORDER BY MatchDate ASC");
            $stmt->execute(array($matchid));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count>0){
        ?>
            <h2 class="text-center">Edit Cup Scheduel</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="matchid" value="<?php echo $matchid?>"/>
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
                                    if($cup['CupID'] == $row['CupID']){echo 'selected';}
                                    echo '>' . $cup["CupName"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end cup id -->
                    <!-- start team one -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Team One</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="TeamoneName" name="TeamoneName" class="form-control"">
                            <option value="0">....</option>
                            <?php
                            $stmt = $con->prepare("SELECT * FROM Team");
                            $stmt->execute();
                            $teams = $stmt->fetchAll();
                            foreach ($teams as $team){
                                echo '<option value="'.$team['TeamID'].'"';
                                if($team['TeamID'] == $row['Team1ID']){echo 'selected';}
                                echo '>' . $team["TeamName"] . '</option>';
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <!-- end team one -->
                    <!-- start team two -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Team Two</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="TeamtwoName" name="TeamtwoName" class="form-control"">
                            <option value="0">....</option>
                            <?php
                            $stmt = $con->prepare("SELECT * FROM Team");
                            $stmt->execute();
                            $teams = $stmt->fetchAll();
                            foreach ($teams as $team){
                                echo '<option value="'.$team['TeamID'].'"';
                                if($team['TeamID'] == $row['Team2ID']){echo 'selected';}
                                echo '>' . $team["TeamName"] . '</option>';
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <!-- end team two -->
                    <!-- start stadium -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Stadium</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="stadium" name="stadium" class="form-control"">
                            <option value="0">....</option>
                            <?php
                            $stmt = $con->prepare("SELECT * FROM stadium");
                            $stmt->execute();
                            $stadiums = $stmt->fetchAll();
                            foreach ($stadiums as $stad){
                                echo '<option value="'.$stad['StadiumID'].'"';
                                if($stad['StadiumID']== $row['StaduimID']){echo 'selected';}
                                echo '>' . $stad["StadiumName"] . '</option>';
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <!-- end stadium -->
                    <!-- start match date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Match Date</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="date"
                                    name="matchdate"
                                    class="form-control"
                                    placeholder="Match Date"
                                    required="required"
                                    value="<?php echo $row['MatchDate']?>"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end match date -->
                    <!-- start match time -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Match Time</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="time"
                                    name="matchtime"
                                    class="form-control"
                                    placeholder="Match Time"
                                    value="<?php echo $row['MatchTime']?>"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end match time -->
                    <!-- start Round -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Round</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="number"
                                    name="round"
                                    min="0"
                                    class="form-control"
                                    placeholder="Round"
                                    required="required"
                                    value="<?php echo $row['CupRound']?>"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end Round -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Update Cup Scheduel"/>
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
                echo '<h2 class="text-center">Update Cup_Scheduel</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $matchid    = $_POST['matchid'];
                $matchdate  = $_POST['matchdate'];
                $teamone    = $_POST['TeamoneName'];
                $teamtwo    = $_POST['TeamtwoName'];
                $stadium    = $_POST['stadium'];
                $round      = $_POST['round'];
                $matchtime  = $_POST['matchtime'];
                $formerorrs = array();
                if(empty($matchid)){
                    $formerorrs[] = 'Match Can Not Be Empty';
                }
                if(empty($matchdate)){
                    $formerorrs[] = 'Match Date Can Not Be Empty';
                }
                if(empty($matchtime)){
                    $formerorrs[] = 'Match Time Can Not Be Empty';
                }
                if(empty($round)){
                    $formerorrs[] = 'Round Can Not Be Empty';
                }
                if($teamone == 0){
                    $formerorrs[] = 'You Must Choose Team One';
                }
                if($teamtwo == 0){
                    $formerorrs[] = 'You Must Choose Team Two';
                }
                if(empty($stadium)){
                    $formerorrs[] = 'Stadium Can Not Be Empty';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("UPDATE cup_schdule SET CupID=?,Team1ID=?,Team2ID=?,MatchDate=?,MatchTime=?,StadiumID=?,Round=? WHERE CupID=?");
                    $stmt->execute(array($matchid,$teamone,$teamtwo,$matchdate,$matchtime,$stadium,$round,$matchid));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Cup Scheduel Updated</div>';
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
            echo '<h2 class="text-center">Delete Cup_Scheduel</h2>';
            echo '<div class="container">';
            $matchid = isset($_GET['matchid']) && is_numeric($_GET['matchid']) ? intval($_GET['matchid']) : 0;
            $check = checkitem('ID','cup_schdule',$matchid);
            if($check>0){
                $stmt = $con->prepare("DELETE FROM cup_schdule WHERE ID=?");
                $stmt->execute(array($matchid));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Cup Scheduel Deleted</div>';
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
