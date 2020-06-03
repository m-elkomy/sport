<?php
    ob_start();
    session_start();
    if(isset($_SESSION['username'])){
        $pagetitle = 'Match Cards';
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){
            $cupid = $_GET['cupid'];
            $matchid = $_GET['matchid'];
            $stmt = $con->prepare("SELECT match_cards.MatchCardID,
                       match_cards.CardTime,
                       cup_schdule.ID,
                       player.PlayerName,
                       card_type.Card_Type
                  FROM ((sport.match_cards match_cards
                         INNER JOIN sport.card_type card_type
                            ON (match_cards.CardID = card_type.CardID))
                        INNER JOIN sport.cup_schdule cup_schdule
                           ON (match_cards.MatchID = cup_schdule.ID))
                       INNER JOIN sport.player player
                          ON (match_cards.PlayerID = player.PlayerID)
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
                            <td>#Match ID</td>
                            <td>Player Name</td>
                            <td>Card Time</td>
                            <td>Card Type</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row){
                            echo '<tr>';
                            echo '<td>' . $row['ID'] . '</td>';
                            echo '<td>' . $row['PlayerName'] . '</td>';
                            echo '<td>' . $row['CardTime']    . '</td>';
                            echo '<td>' . $row['Card_Type']    . '</td>';
                            echo '<td>';
                            echo '<a href="?do=Edit&cardid='.$row['MatchCardID'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                            echo '<a href="?do=Delete&cardid='.$row['MatchCardID'].'" class="confirm btn btn-danger"><i class="fa fa-close"></i> Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
                <a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Match Card</a>
            </div>
        <?php
            }else{
                echo '<div class="container">';
                echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
                echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Match Card</a>';
                echo '</div>';
            }
        }elseif($do == 'Add'){?>
            <h2 class="text-center">Add New Match Card</h2>
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
                    <!-- start card type -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Card Type</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="cardtype" name="cardtype" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM card_type");
                                $stmt->execute();
                                $card = $stmt->fetchAll();
                                foreach ($card as $card){
                                    echo '<option value="'.$card['CardID'].'">' . $card['Card_Type'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end card type -->
                    <!-- start card time -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Card Time</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="time"
                                    name="cardtime"
                                    class="form-control"
                                    placeholder="Goal Time"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end card time -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add Match Card"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
        }elseif($do == 'Insert'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Insert Match Card</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $matchid      = $_POST['matchid'];
                $playerid     = $_POST['playerid'];
                $cardtime     = $_POST['cardtime'];
                $cardtype     = $_POST['cardtype'];
                $formerorrs = array();
                if(empty($matchid)){
                    $formerorrs[] = 'Match ID Can Not Be Empty';
                }
                if(empty($playerid)){
                    $formerorrs[] = 'Player Can Not Be Empty';
                }
                if(empty($cardtime)){
                    $formerorrs[] = 'Goal Time Can Not Be Empty';
                }
                if(empty($cardtype)){
                    $formerorrs[] = 'Card Type Can Not Be Empty';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("INSERT INTO match_cards(MatchID, PlayerID, CardTime,CardID) 
                                            VALUES (:zmatch,:zplayer,:zgoal,:zcard)");
                    $stmt->execute(array(
                        ':zmatch'  => $matchid,
                        ':zplayer' => $playerid,
                        ':zgoal'   => $cardtime,
                        ':zcard'   => $cardtype));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Match Card Inserted</div>';
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
            $cardid = isset($_GET['cardid']) && is_numeric($_GET['cardid']) ? intval($_GET['cardid']) : 0;
            $stmt = $con->prepare("SELECT match_cards.MatchCardID,
                           match_cards.CardTime,
                           cup_schdule.ID,
                           player.PlayerName,
                           player.PlayerID,
                           card_type.Card_Type,
                           card_type.CardID
                      FROM ((sport.match_cards match_cards
                             INNER JOIN sport.card_type card_type
                                ON (match_cards.CardID = card_type.CardID))
                            INNER JOIN sport.cup_schdule cup_schdule
                               ON (match_cards.MatchID = cup_schdule.ID))
                           INNER JOIN sport.player player
                              ON (match_cards.PlayerID = player.PlayerID)
                     WHERE (match_cards.MatchCardID = ?)");
            $stmt->execute(array($cardid));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count>0){
        ?>
            <h2 class="text-center">Edit Match Card</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="cardid" value="<?php echo $cardid?>"/>
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
                                    if($row['ID'] == $cup['ID']){echo 'selected';}
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
                    <!-- start card type -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Card Type</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="cardtype" name="cardtype" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM card_type");
                                $stmt->execute();
                                $card = $stmt->fetchAll();
                                foreach ($card as $card){
                                    echo '<option value="'.$card['CardID'].'"';
                                    if($row['CardID'] == $card['CardID']){echo 'selected';}
                                    echo '>' . $card['Card_Type'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end card type -->
                    <!-- start card time -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Card Time</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="time"
                                    name="cardtime"
                                    class="form-control"
                                    placeholder="Card Time"
                                    value="<?php echo $row['CardTime']?>"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end card time -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Update Match Card"/>
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
                echo '<h2 class="text-center">Update Match Card</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $cardid     = $_POST['cardid'];
                $matchid    = $_POST['matchid'];
                $playerid   = $_POST['playerid'];
                $cardtime   = $_POST['cardtime'];
                $cardtype   = $_POST['cardtype'];
                $formerorrs = array();
                if(empty($matchid)){
                    $formerorrs[] = 'Match ID Can Not Be Empty';
                }
                if(empty($playerid)){
                    $formerorrs[] = 'Player Can Not Be Empty';
                }
                if(empty($cardtime)){
                    $formerorrs[] = 'Goal Time Can Not Be Empty';
                }
                if(empty($cardtype)){
                    $formerorrs[] = 'Card Type Can Not Be Empty';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("UPDATE match_cards SET MatchID=?,PlayerID=?,CardTime=?,CardID=? WHERE MatchCardID=?");
                    $stmt->execute(array($matchid,$playerid,$cardtime,$cardtype,$cardid));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Match Card Updated</div>';
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
            echo '<h2 class="text-center">Delete Match Cards</h2>';
            echo '<div class="container">';
            $cardid = isset($_GET['cardid']) && is_numeric($_GET['cardid']) ? intval($_GET['cardid']) : 0;
            $check = checkitem('MatchCardID','match_cards',$cardid);
            if($check>0){
                $stmt = $con->prepare("DELETE FROM match_cards WHERE MatchCardID=?");
                $stmt->execute(array($cardid));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Match Card Deleted</div>';
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
