<?php
    $pagetitle = 'View Cup';
    include 'init.php';
$cupid = $_GET['cupid'];
$cupname = $_GET['cupname'];
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
?>
<h2 class="text-center"><?php echo $cupname?></h2>
<div class="container">
    <div class="table-responsive text-center main-table">
        <table class="table table-bordered">
            <tr>
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
                echo '<td>' . $row['Team1Name']    . '</td>';
                echo '<td>' . $row['Team2Name']    . '</td>';
                echo '<td>' . $row['StaduimName']    . '</td>';
                echo '<td>' . $row['MatchDate']    . '</td>';
                echo '<td>' . $row['MatchTime']    . '</td>';
                echo '<td>' . $row['CupRound']    . '</td>';
                echo '<td>
                        <a href="score.php?cupid='.$row['CupID'].'&cupname='.$row['CupName'].'&matchid='.$row['MatchID'].'" class="btn btn-info">Score</a>
                    </td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>
<?php include $tpl . 'footer.php';?>
