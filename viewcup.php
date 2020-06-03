<?php
    $pagetitle = 'View Cup';
    include 'init.php';
$cupid = $_GET['cupid'];
$cupname = $_GET['cupname'];
$stmt = $con->prepare("SELECT cup_team.* ,cup.*,team.* FROM cup_team INNER JOIN cup ON cup_team.CupID = cup.CupID INNER JOIN team ON cup_team.TeamID=team.TeamID WHERE  cup_team.CupID=?");
    $stmt->execute(array($cupid));
    $rows = $stmt->fetchAll();
?>
<h2 class="text-center"><?php echo $cupname?></h2>
<div class="container">
    <div class="table-responsive text-center main-table">
        <table class="table table-bordered">
            <tr>
                <td>Start Date</td>
                <td>End Date</td>
                <td>Team</td>
                <td>Control</td>
            </tr>
            <?php
            foreach ($rows as $row){
                echo '<tr>';
                echo '<td>' . $row['StartDate']    . '</td>';
                echo '<td>' . $row['EndDate']    . '</td>';
                echo '<td>' . $row['TeamName']    . '</td>';
                echo '<td>
                        <a href="viewscheduel.php?cupid='.$row['CupID'].'&cupname='. $row['CupName'] .'" class="btn btn-info">Scheduel</a>
                    </td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>
<?php include $tpl . 'footer.php';?>
