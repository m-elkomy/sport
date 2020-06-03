<?php
    ob_start();
    session_start();
    if(isset($_SESSION['username'])){
        $pagetitle = 'Cup';
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){
            $stmt = $con->prepare("SELECT * FROM Cup");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            if(!empty($rows)){
            ?>
            <h2 class="text-center">Manage Team</h2>
            <div class="container">
                <div class="table-responsive text-center main-table">
                    <table class="table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Cup Name</td>
                            <td>Start Date</td>
                            <td>End Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row){
                            echo '<tr>';
                            echo '<td>' . $row['CupID'] . '</td>';
                            echo '<td>' . $row['CupName'] . '</td>';
                            echo '<td>' . $row['StartDate']    . '</td>';
                            echo '<td>' . $row['EndDate']    . '</td>';
                            echo '<td>';
                            echo '<a href="?do=Edit&cupid='.$row['CupID'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                            echo '<a href="?do=Delete&cupid='.$row['CupID'].'" class="confirm btn btn-danger"><i class="fa fa-close"></i> Delete</a>';
                            echo '<a href="cup_team.php?cupid='.$row['CupID'].'" class="btn btn-info"><i class="fa fa-check"></i> Cup Team</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
                <a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Cup</a>
            </div>
        <?php
            }else{
                echo '<div class="container">';
                echo '<div class="message alert alert-info">There Is No Recored To Show</div>';
                echo '<a href="?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add New Cup</a>';
                echo '</div>';
            }
        }elseif($do == 'Add'){?>
            <h2 class="text-center">Add New Cup</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post">
                    <!-- start cup name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Cup Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="text"
                                name="cupname"
                                class="form-control"
                                placeholder="Cup Name"
                                required="required"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end cup name -->
                    <!-- start starting date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Start Date</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="date"
                                    name="startdate"
                                    class="form-control"
                                    placeholder="Starting Date"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end start date -->
                    <!-- start ending date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">End Date</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="date"
                                    name="enddate"
                                    class="form-control"
                                    placeholder="Ending Date"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end Ending date -->
                    <!-- start continent -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Continent</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="continent" name="continent" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM continent");
                                $stmt->execute();
                                $continents = $stmt->fetchAll();
                                foreach ($continents as $cont){
                                    echo '<option value="'.$cont['ID'].'">' . $cont["Name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end continent -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add New Cup"/>
                        </div>
                    </div>
                    <!-- end submit -->
                </form>
            </div>
        <?php
        }elseif($do == 'Insert'){
            //check that user coming to this page using post request
            if($_SERVER['REQUEST_METHOD']=='POST'){
                echo '<h2 class="text-center">Insert Cup</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $cupname = $_POST['cupname'];
                $startdate  = $_POST['startdate'];
                $enddate  = $_POST['enddate'];
                $continentid = $_POST['continent'];
                $formerorrs = array();
                if(strlen($cupname)<5){
                    $formerorrs[] = 'Cup Name Can Not Be Less Than 5 Character';
                }
                if(empty($startdate)){
                    $formerorrs[] = 'Start Date Can Not Be Empty';
                }
                if(empty($continentid)){
                    $formerorrs[] = 'Continent Can Not Be Empty';
                }
                if(empty($enddate)){
                    $formerorrs[] = 'End Date Can Not Be Empty';
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("INSERT INTO Cup(CupName,StartDate,EndDate)VALUES(:zname,:zstart,:zend)");
                    $stmt->execute(array(
                        ':zname' => $cupname,
                        ':zstart' => $startdate,
                        ':zend'   => $enddate));
                    $cupid = $con->lastInsertId();
                    $stmt2 = $con->prepare("INSERT INTO continent_cup(CupID,Con_ID)VALUE (:zcup,:zcon)");
                    $stmt2->execute(array(
                        ':zcup'=>$cupid,
                        ':zcon'=>$continentid));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Cup Inserted</div>';
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
            $cupid = isset($_GET['cupid']) && is_numeric($_GET['cupid']) ? intval($_GET['cupid']) : 0;
            $stmt = $con->prepare("SELECT cup.*,continent_cup.* FROM cup INNER JOIN continent_cup ON cup.CupID= continent_cup.CupID WHERE cup.CupID=? LIMIT 1");
            $stmt->execute(array($cupid));
            $count = $stmt->rowCount();
            $row = $stmt->fetch();
            if($count>0){
        ?>
            <h2 class="text-center">Edit Cup</h2>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="cupid" value="<?php echo $cupid?>"/>
                    <!-- start cup name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Cup Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                type="text"
                                name="cupname"
                                class="form-control"
                                placeholder="Cup Name"
                                required="required"
                                value="<?php echo $row['CupName']?>"
                                autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end cup name -->
                    <!-- start starting date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Start Date</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="date"
                                    name="startdate"
                                    class="form-control"
                                    placeholder="Starting Date"
                                    value="<?php echo $row['StartDate']?>"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end start date -->
                    <!-- start ending date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">End Date</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="date"
                                    name="enddate"
                                    class="form-control"
                                    placeholder="Ending Date"
                                    value="<?php echo $row['EndDate']?>"
                                    required="required"
                                    autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end Ending date -->
                    <!-- start continent -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-md-2">Continent</label>
                        <div class="col-sm-10 col-md-6">
                            <select id="continent" name="continent" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM continent");
                                $stmt->execute();
                                $continents = $stmt->fetchAll();
                                foreach ($continents as $cont){
                                    echo '<option value="'.$cont['ID'].'"';
                                    if($row['Con_ID'] == $cont['ID']){echo 'selected';}
                                    echo '>' . $cont["Name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end continent -->
                    <!-- start submit -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Update Cup"/>
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
                echo '<h2 class="text-center">Update Cup</h2>';
                echo '<div class="container">';
                //receive data fro muser
                $cup_id  = $_POST['cupid'];
                $cupname = $_POST['cupname'];
                $startdate = $_POST['startdate'];
                $enddate   = $_POST['enddate'];
                $continentid = $_POST['continent'];
                $formerorrs = array();
                if(strlen($cupname)<5){
                    $formerorrs[] = 'Team Name Can Not Be Less Than 5 Character';
                }
                if(empty($startdate)){
                    $formerorrs[] = 'Start Date Can Not Be Empty';
                }
                if(empty($enddate)){
                    $formerorrs[] = 'End Date Can Not Be Empty';
                }
                if(empty($continentid)){
                    $formerorrs[] = 'Continent Can Not Be Empty';
                }
                foreach ($formerorrs as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerorrs)){
                    $stmt = $con->prepare("UPDATE Cup INNER JOIN continent_cup ON cup.CupID = continent_cup.CupID SET CupName=?,StartDate=?,EndDate=?,Con_ID=? WHERE cup.CupID=?");
                    $stmt->execute(array($cupname,$startdate,$enddate,$continentid,$cup_id));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Cup Updated</div>';
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
            echo '<h2 class="text-center">Delete Cup</h2>';
            echo '<div class="container">';
            $cupid = isset($_GET['cupid']) && is_numeric($_GET['cupid']) ? intval($_GET['cupid']) : 0;
            $check = checkitem('CupID','Cup',$cupid);
            if($check>0){
                $stmt = $con->prepare("DELETE FROM Cup WHERE CupID=?");
                $stmt->execute(array($cupid));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Cup Deleted</div>';
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
