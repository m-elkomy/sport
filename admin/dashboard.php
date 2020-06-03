<?php
    session_start();
    if(isset($_SESSION['username'])){
        $pagetitle = 'Dashboard';
        include 'init.php';
        $limituser = 5;
        $latestuser = getlatest('*','users','UserID',$limituser);
        $limitteam = 5;
        $latestteam = getlatest('*','team','TeamID',$limitteam);
        $limitcup= 5;
        $latestcup = getlatest('*','cup','CupID',$limitcup);
        $limitcoach= 5;
        $latestcoach = getlatest('*','coach','CoachID',$limitcoach);
            ?>

        <div class="container text-center main-states">
            <h2 >Dashboard</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="state st-member">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Member
                            <span><a href="members.php"><?php echo countitem('UserID','Users'); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="state st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Total Team
                            <span><a href="team.php"><?php echo countitem('TeamID','Team'); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="state st-item">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Cups
                            <span><a href="cup.php"><?php echo countitem('CupID','cup'); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="state st-comment">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total Coach
                            <span><a href="coach.php"><?php echo countitem('CoachID','coach'); ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container latest">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-user"></i>
                            Latest <?php echo $limituser ?> Members
                            <span class="toggle-info pull-right"><i class="fa fa-minus"></i></span></div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-user">
                                <?php
                                    foreach ($latestuser as $user){
                                        echo '<li>' . $user['UserName'] . '</li>';
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-group"></i>
                            Latest <?php echo $limituser ?> Teams
                            <span class="toggle-info pull-right"><i class="fa fa-minus"></i></span></div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-user">
                                <?php
                                foreach ($latestteam as $team){
                                    echo '<li>' . $team['TeamName'] . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-cube"></i>
                            Latest <?php echo $limituser ?> Cups
                            <span class="toggle-info pull-right"><i class="fa fa-minus"></i></span></div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-user">
                                <?php
                                foreach ($latestcup as $cup){
                                    echo '<li>' . $cup['CupName'] . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-users"></i>
                            Latest <?php echo $limituser ?> Coach
                            <span class="toggle-info pull-right"><i class="fa fa-minus"></i></span></div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-user">
                                <?php
                                foreach ($latestcoach as $coach){
                                    echo '<li>' . $coach['CoachName'] . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <?php
        include $tpl . 'footer.php';
    }else{
        header('Location:index.php');
        exit();
    }
?>
