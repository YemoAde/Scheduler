<?php
    session_start();
    require_once('functions.php');
    $group = new Group();

    if(isset($_POST['group-add'])) {
        $id = NULL;
        $group_details = array(
            ':group_name' => trim($_POST['group_name'])
            );
        $id = $group->add_group($group_details);
        if($id != NULL){
            $list = $group->get_all('report_group');
            foreach ($list as $row) {
                $position = $row['_id'];
                if(isset($_POST[$position])){
                    $group->grant_access(array(':group_id'=>$id, ':report_group_id'=>$row['_id']));
                }
            }
        }
    }

    if (isset($_POST['group-delete'])) {

        $group->delete_group($_POST['delete_select']);

    }

    


?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Report System - Manage Permissions</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!--  Social tags      -->
    <meta name="keywords" content="">
    <meta name="description" content="">
    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="assets/css/material-dashboard.css" rel="stylesheet" />
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
</head>

<body>
    <div class="wrapper">
        <div class="sidebar" data-active-color="blue" data-background-color="black" data-image="assets/img/sidebar-1.jpg">
            <div class="logo">
                <a href="#" class="simple-text">
                    Report Scheduler
                </a>
            </div>
            <div class="logo logo-mini">
                <a href="#" class="simple-text">
                    Rs
                </a>
            </div>
            <div class="sidebar-wrapper">
                <div class="user">
                    <div class="photo">
                        <img src="assets/img/faces/avatar.jpg" />
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                            <?php echo '<h6>'.$_SESSION['email'].'</h6>'; ?>
                        </a>
                    </div>
                </div>
                <ul class="nav">
                    <li>
                        <a href="dashboard.php">
                            <i class="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <?php
                        if(!$group->isAdmin()) {
                    ?>
                    <li>
                        <a data-toggle="collapse" href="#schedule">
                            <i class="material-icons">schedule</i>
                            <p>Schedules
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="schedule">
                            <ul class="nav">
                                <li>
                                    <a href="schedule-request.php">Request New</a>
                                </li>
                                <li>
                                    <a href="schedule-manage.php">Manage</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php
                    }
                        if($group->isAdmin()) {
                    ?>
                    <li>
                        <a data-toggle="collapse" href="#users">
                            <i class="material-icons">persons</i>
                            <p>Users
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="users">
                            <ul class="nav">
                                <li>
                                    <a href="user-add.php">Add User</a>
                                </li>
                                <li>
                                    <a href="user-manage.php">Manage Users</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="active">
                        <a data-toggle="collapse" href="#settings">
                            <i class="material-icons">settings</i>
                            <p>Settings
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="settings">
                            <ul class="nav">
                                <li class="active">
                                    <a href="permission.php">Manage Permission</a>
                                </li>
                                <li>
                                    <a href="groups.php">Manage Report Groups</a>
                                </li>
                                <li>
                                    <a href="reports.php">Manage Reports</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <?php
                        }
                    ?>
                    <li>
                        <a href="logout.php">
                            <i class="material-icons">power_settings_new</i>
                            <p>Logout
                            </p>
                        </a>
                    </li>
                   
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <nav class="navbar navbar-transparent navbar-absolute">
                <div class="container-fluid">
                    <div class="navbar-minimize">
                        <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
                            <i class="material-icons visible-on-sidebar-regular">more_vert</i>
                            <i class="material-icons visible-on-sidebar-mini">view_list</i>
                        </button>
                    </div>
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#"> Manage Permission </a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">dashboard</i>
                                    <p class="hidden-lg hidden-md">Dashboard</p>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">notifications</i>
                                    <span class="notification">5</span>
                                    <p class="hidden-lg hidden-md">
                                        Notifications
                                        <b class="caret"></b>
                                    </p>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#">Mike John responded to your email</a>
                                    </li>
                                    <li>
                                        <a href="#">You have 5 new tasks</a>
                                    </li>
                                    <li>
                                        <a href="#">You're now friend with Andrew</a>
                                    </li>
                                    <li>
                                        <a href="#">Another Notification</a>
                                    </li>
                                    <li>
                                        <a href="#">Another One</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="material-icons">person</i>
                                    <p class="hidden-lg hidden-md">Profile</p>
                                </a>
                            </li>
                            <li class="separator hidden-lg hidden-md"></li>
                        </ul>
                        <form class="navbar-form navbar-right" role="search">
                            <div class="form-group form-search is-empty">
                                <input type="text" class="form-control" placeholder="Search">
                                <span class="material-input"></span>
                            </div>
                            <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                <i class="material-icons">search</i>
                                <div class="ripple-container"></div>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
            <div class="content">
                <div class="container-fluid">
                    <?php
                        if (isset($_GET['success'])) {

                        ?>
                            <div class="alert alert-success">
                                <div class="container-fluid">
                                  <div class="alert-icon">
                                    <i class="material-icons">error_outline</i>
                                  </div>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="material-icons">clear</i></span>
                                  </button>
                                  <b>Done !
                                </div>
                            </div>
                        <?php
                        }
                    ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <form id="RegisterValidation" action="" method="POST">
                                    <div class="card-header card-header-icon" data-background-color="rose">
                                        <i class="material-icons">add</i>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title">New Group</h4>
                                        <div class="form-group label-floating">
                                            <label class="control-label">
                                                Group Name
                                                <small>*</small>
                                            </label>
                                            <input class="form-control" name="group_name" type="text" required="true" />
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-2 label-on-left">Report Groups</label>
                                            <div class="col-sm-4 col-sm-offset-1 checkbox-radios">
                                                <?php
                                                    $list = $group->get_all('report_group');
                                                    foreach ($list as $row) {

                                                         echo '<div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="'. $row['_id'] .'">'. $row['report_group_name'] .'
                                                            </label>
                                                        </div>';
                                                    }
                                                ?>
                                            </div>
                                            </div>
                                        <div class="form-footer text-right">
                                            <button type="submit" name="group-add" class="btn btn-rose btn-fill">Add Group</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card" style="min-height: 340px">
                                <form action="" method="POST">
                                    <div class="card-header card-header-icon" data-background-color="rose">
                                        <i class="material-icons">cancel</i>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title">Delete Group</h4>
                                        
                                        <div class="form-group label-floating">
                                            <label class="control-label">
                                                Group Name
                                                <small>*</small>
                                            </label>
                                            <select class="selectpicker" data-style="btn btn-default" data-size="7" name="delete_select">
                                                <option disabled >Select group</option>
                                                <?php
                                                    $list = $group->get_all('groups');
                                                    foreach ($list as $row) {
                                                         echo '<option value="'.$row['_id'].'">'.$row['group_name'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-footer text-right">
                                            <button type="submit" name="group-delete" class="btn btn-rose btn-fill">Remove Group</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <p class="copyright pull-right">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        <a href="#">Baraba</a>, Final year project
                    </p>
                </div>
            </footer>
        </div>
    </div>
</body>
<!--   Core JS Files   -->
<script src="assets/js/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/material.min.js" type="text/javascript"></script>
<script src="assets/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<!-- Forms Validations Plugin -->
<script src="assets/js/jquery.validate.min.js"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
<script src="assets/js/moment.min.js"></script>
<!--  Charts Plugin -->
<script src="assets/js/chartist.min.js"></script>
<!--  Plugin for the Wizard -->
<script src="assets/js/jquery.bootstrap-wizard.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/bootstrap-notify.js"></script>
<!--   Sharrre Library    -->
<script src="assets/js/jquery.sharrre.js"></script>
<!-- DateTimePicker Plugin -->
<script src="assets/js/bootstrap-datetimepicker.js"></script>
<!-- Vector Map plugin -->
<script src="assets/js/jquery-jvectormap.js"></script>
<!-- Sliders Plugin -->
<script src="assets/js/nouislider.min.js"></script>
<!--  Google Maps Plugin    -->
<script src="https://maps.googleapis.com/maps/api/js"></script>
<!-- Select Plugin -->
<script src="assets/js/jquery.select-bootstrap.js"></script>
<!--  DataTables.net Plugin    -->
<script src="assets/js/jquery.datatables.js"></script>
<!-- Sweet Alert 2 plugin -->
<script src="assets/js/sweetalert2.js"></script>
<!--    Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="assets/js/jasny-bootstrap.min.js"></script>
<!--  Full Calendar Plugin    -->
<script src="assets/js/fullcalendar.min.js"></script>
<!-- TagsInput Plugin -->
<script src="assets/js/jquery.tagsinput.js"></script>
<!-- Material Dashboard javascript methods -->
<script src="assets/js/material-dashboard.js"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>
<script type="text/javascript"> 

    setInterval(Update, 120000)

    function Update() {
        $.ajax({
        url: "cronjob.php", 
        type: "GET",
            success: function (data) {
                if(data == "1"){
                   //Page will be updated
                }
                else{
                    
                }
            }
        });
    }

</script>
<script type="text/javascript">
    $(document).ready(function() {

        // Javascript method's body can be found in assets/js/demos.js
        demo.initDashboardPageCharts();

        demo.initVectorMap();
    });
</script>

</html>