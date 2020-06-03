<nav class="navbar navbar-inverse">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myapp-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php"><?php echo lang('CUP PROJECT')?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="myapp-nav">
            <ul class="nav navbar-nav">
                <li><a href="members.php"><?php echo lang('MEMBERS')?></a></li>
                <li><a href="player.php"><?php echo lang('PLAYER')?></a></li>
                <li><a href="team.php"><?php echo lang('TEAM')?></a></li>
                <li><a href="stadium.php"><?php echo lang('STADIUM')?></a></li>
                <li><a href="coach.php"><?php echo lang('COACH')?></a></li>
                <li><a href="cup.php"><?php echo lang('CUP')?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['username']?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="../index.php">Visit Site</a></li>
                        <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['userid']?>"><?php echo lang('EDIT PROFILE')?></a></li>
                        <li><a href="#"><?php echo lang('SETTING')?></a></li>
                        <li><a href="logout.php"><?php echo lang('LOGOUT')?></a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>