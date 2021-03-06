<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width,initial-scale=1"/>
        <title><?php gettitle()?></title>
        <link rel="stylesheet" href="<?php echo $css?>bootstrap.min.css"/>
        <link rel="stylesheet" href="<?php echo $css?>font-awesome.min.css"/>
        <link rel="stylesheet" href="<?php echo $css?>backend.css"/>
        <!--[if lt IE 9]>
        <script src="<?php echo $js?>html5shiv.min.js"></script>
        <script src="<?php echo $js?>respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
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
                <a class="navbar-brand" href="index.php"><?php echo lang('CUP PROJECT')?></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="myapp-nav">
                <ul class="nav navbar-nav">
                    <li><a href="africa.php">Africa</a></li>
                    <li><a href="asian.php">Asia</a></li>
                    <li><a href="america.php">America</a></li>
                    <li><a href="euorope.php">Europe</a></li>
                    <li><a href="world.php">World</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
