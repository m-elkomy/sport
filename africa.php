<?php
    $pagetitle = 'African';
    include 'init.php';
$stmt = $con->prepare("SELECT continent_cup.ID,
       continent.Name,
       team.TeamName,
       cup.CupID,
       cup.CupName,
       cup.EndDate,
       cup.StartDate
  FROM (((sport.continent_cup continent_cup
          INNER JOIN sport.cup cup ON (continent_cup.CupID = cup.CupID))
         INNER JOIN sport.continent continent
            ON (continent_cup.Con_ID = continent.ID))
        INNER JOIN sport.continent_team continent_team
           ON (continent_team.C_ID = continent.ID))
       INNER JOIN sport.team team ON (continent_team.T_ID = team.TeamID)
 WHERE (continent_cup.Con_ID = 2)");
$stmt->execute();
$rows = $stmt->fetchAll();
if(!empty($rows)){
?>
<h2 class="text-center">African Cups</h2>
<div class="container">
    <div class="table-responsive text-center main-table">
        <table class="table table-bordered">
            <tr>
                <td>Cup Name</td>
                <td>Start Date</td>
                <td>End Date</td>
                <td>Control</td>
            </tr>
            <?php
            foreach ($rows as $row){
                echo '<tr>';
                echo '<td>' . $row['CupName'] . '</td>';
                echo '<td>' . $row['StartDate'] . '</td>';
                echo '<td>' . $row['EndDate']    . '</td>';
                echo '<td>
                        <a href="viewcup.php?cupid='.$row['CupID'].'&cupname='.$row['CupName'].'" class="btn btn-info">Teams</a>
                    </td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>
<?php
}else{
    echo '<div class="container">';
    echo '<div class="alert alert-info">There Is No Cup To View</div>';
    echo '</div>';
}
    include $tpl . 'footer.php';?>
