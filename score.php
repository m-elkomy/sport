<?php
    $pagetitle = 'Match Score';
    include 'init.php';
$cupid = $_GET['cupid'];
$cupname = $_GET['cupname'];
$matchid = $_GET['matchid'];
$stmt = $con->prepare("SELECT cup_schdule.ID,
       cup_schdule.CupID,
       cup_schdule.Team1ID AS Team1ID,
       team.TeamName AS Team1Name,
       cup_schdule.Team2ID AS Team2ID,
       team.TeamName AS Team2Name,
       cup_schdule.MatchDate,
       cup_schdule.MatchTime,
       player.PlayerID,
       player.PlayerName,
       player.PlayerNumber,
       team.TeamID,
       team.TeamName,
       match_goal.GoalTime,
       match_goal.MatchID,
       match_cards.CardTime,
       match_cards.CardID,
       card_type.Card_Type,
       card_type.CardID,
       match_cards.PlayerID,
       cup.CupName,
       cup.CupID
  FROM (((((sport.match_cards match_cards
            INNER JOIN sport.player player
               ON (match_cards.PlayerID = player.PlayerID))
           INNER JOIN sport.match_goal match_goal
              ON (match_goal.PlayerID = player.PlayerID))
          INNER JOIN sport.cup_schdule cup_schdule
             ON     (match_goal.MatchID = cup_schdule.ID)
                AND (match_cards.MatchID = cup_schdule.ID))
         INNER JOIN sport.team team
            ON     (cup_schdule.Team2ID = team.TeamID)
               AND (cup_schdule.Team1ID = team.TeamID)
               AND (player.TeamID = team.TeamID))
        INNER JOIN sport.card_type card_type
           ON (match_cards.CardID = card_type.CardID))
       INNER JOIN sport.cup cup ON (cup_schdule.CupID = cup.CupID)
 WHERE (cup_schdule.ID = ?) AND (cup.CupID = ?)");
    $stmt->execute(array($cupid,$matchid));
    $rows = $stmt->fetchAll();
?>
<h2 class="text-center"><?php echo $cupname?></h2>
<div class="container">
    <div class="table-responsive text-center main-table">
        <table class="table table-bordered">
            <tr>
                <td>Team 1 Name</td>
                <td>Team 2 Name</td>
                <td>Match Date</td>
                <td>Match Time</td>
                <td>Goals Time</td>
                <td>Cards Time</td>
                <td>Control</td>
            </tr>
            <?php
            foreach ($rows as $row){
                echo '<tr>';
                echo '<td>' . $row['Team1Name']    . '</td>';
                echo '<td>' . $row['Team2Name']    . '</td>';
                echo '<td>' . $row['MatchDate']    . '</td>';
                echo '<td>' . $row['MatchTime']    . '</td>';
                echo '<td>' . $row['GoalTime']    . '</td>';
                echo '<td>' . $row['CardTime']    . '</td>';
                echo '<td>
                        <a href="score.php?cupid='.$row['CupID'].'&matchid='.$row['MatchID'].'" class="btn btn-info">Score</a>
                    </td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>
<?php include $tpl . 'footer.php';?>
