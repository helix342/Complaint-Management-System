<?php
include("db.php");
session_start();
$_session['eo_id'] = 12345;
$eo_id = $_session['eo_id'];

$sql = "
SELECT cd.*, faculty_details.faculty_name, faculty_details.department, faculty_details.faculty_contact, faculty_details.faculty_mail
FROM complaints_detail cd
JOIN faculty_details ON cd.faculty_id = faculty_details.faculty_id
WHERE cd.status = '4'
";
$sql1 = "
SELECT cd.*, faculty_details.faculty_name, faculty_details.department, faculty_details.faculty_contact, faculty_details.faculty_mail
FROM complaints_detail cd
JOIN faculty_details ON cd.faculty_id = faculty_details.faculty_id
WHERE cd.status IN (2, 6, 7, 10, 11, 15, 17, 18, 13, 22)
";
$sql2 = "
SELECT cd.*, faculty_details.faculty_name, faculty_details.department, faculty_details.faculty_contact, faculty_details.faculty_mail
FROM complaints_detail cd
JOIN faculty_details ON cd.faculty_id = faculty_details.faculty_id
WHERE cd.status = '16'
";
$sql3 = "
SELECT cd.*, faculty_details.faculty_name, faculty_details.department, faculty_details.faculty_contact, faculty_details.faculty_mail
FROM complaints_detail cd
JOIN faculty_details ON cd.faculty_id = faculty_details.faculty_id
WHERE cd.status IN (3, 5, 19, 20, 23)
";
$result = mysqli_query($conn, $sql);
$result1 = mysqli_query($conn, $sql1);
$result2 = mysqli_query($conn, $sql2);
$result3 = mysqli_query($conn, $sql3);
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>MIC-MKCE</title>
    <!-- Custom CSS -->
    <link href="assets/libs/flot/css/float-chart.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="css/dboardstyles.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>

    <style>
        .table-container {
            -ms-overflow-style: none;
        }

        .table-container {
            overflow: auto;
            width: 100%;
            height: 100%;

        }

        .fixed-size-table {
            width: 100%;
            table-layout: fixed;
        }

        .fixed-size-table th,
        .fixed-size-table td {
            width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-tabs .nav-link {
            color: #0033cc;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%);
            color: white;
        }

        .modal-content {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-radius: 15px 15px 0px 0px;
        }

        .modal-header .close {
            font-size: 1.5rem;
            color: white;
            opacity: 1;
            transition: transform 0.3s ease;
        }

        .modal-header .close:hover {
            transform: rotate(90deg);
            color: #ff8080;
        }
    </style>

</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper">
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <a class="navbar-brand" href="https://www.mkce.ac.in">
                        <!-- Logo icon -->
                        <b class="logo-icon p-l-10" style="padding-left:0px; border-left:0px;">
                            <img src="assets/images/logo-icon.png" width="50px" alt="homepage" class="light-logo" />

                        </b>

                        <span class="logo-text">

                            <img src="assets/images/logo-text.png" alt="homepage" class="light-logo" />

                        </span>
                    </a>
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
                </div>
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block"><a
                                class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                    src="assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31"></a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                                <a class="dropdown-item" href="javascript:void(0)"><i
                                        class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                                <div class="dropdown-divider"></div>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="p-t-30 in">
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link active" href="eo.php" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span
                                    class="hide-menu">Complaints</span></a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">Feedback Approval</h4>
                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="eo.php">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Feedback Corner</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form class="zmdi-format-valign-top">
                                <div class="card-body">
                                    <h4 class="card-title">Complaint Details</h4>
                                    <div class="card">
                                        <ul class="nav nav-tabs mb-3" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active show" data-toggle="tab" href="#dashboard"
                                                    role="tab" aria-selected="true"><span class="hidden-sm-up"></span>
                                                    <span class="hidden-xs-down"><i
                                                            class="mdi mdi-view-grid"></i><b>&nbsp Dashboard</b></span></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#pending"
                                                    role="tab" aria-selected="false"><span class="hidden-sm-up"></span>
                                                    <div id="navref1">
                                                        <span class="hidden-xs-down">
                                                            <i class="fas fa-clock"></i>
                                                            <b>&nbsp Pending ( <?php $query2 = "SELECT COUNT(*) as pending FROM complaints_detail WHERE  status ='4'";
                                                                                $output2 = mysqli_query($conn, $query2);
                                                                                $row2 = mysqli_fetch_assoc($output2);
                                                                                $pendingCount = $row2['pending'];
                                                                                echo $pendingCount;
                                                                                ?> ) </b>
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#approved" role="tab"
                                                    aria-selected="false"><span class="hidden-sm-up"></span>
                                                    <div id="navref2">
                                                        <span class="hidden-xs-down">
                                                            <i class="fas fa-check"></i><b>&nbsp Approved ( <?php $query2 = "SELECT COUNT(*) as approved FROM complaints_detail WHERE (status ='2' or status ='22' or status='7' or status='10' or status='11' or status='13' or status='6' or status='15' or status='17' or status='18')";
                                                                                                            $output2 = mysqli_query($conn, $query2);
                                                                                                            $row2 = mysqli_fetch_assoc($output2);
                                                                                                            $pendingCount = $row2['approved'];
                                                                                                            echo $pendingCount;
                                                                                                            ?> )</b>
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#completed" role="tab"
                                                    aria-selected="false"><span class="hidden-sm-up"></span>
                                                    <div id="navref3">
                                                        <span class="hidden-xs-down">
                                                            <i class="mdi mdi-check-all"></i><b>&nbsp Completed ( <?php $query2 = "SELECT COUNT(*) as completed FROM complaints_detail WHERE  status ='16'";
                                                                                                                    $output2 = mysqli_query($conn, $query2);
                                                                                                                    $row2 = mysqli_fetch_assoc($output2);
                                                                                                                    $pendingCount = $row2['completed'];
                                                                                                                    echo $pendingCount;
                                                                                                                    ?> )</b>
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#rejected" role="tab"
                                                    aria-selected="false"><span class="hidden-sm-up"></span>
                                                    <div id="navref4">
                                                        <span class="hidden-xs-down">
                                                            <i class="mdi mdi-close-circle"></i><b>&nbsp Rejected ( <?php $query2 = "SELECT COUNT(*) as rejected FROM complaints_detail WHERE (status ='5' or status ='19' or status='20' or status='3' or status='23')";
                                                                                                                    $output2 = mysqli_query($conn, $query2);
                                                                                                                    $row2 = mysqli_fetch_assoc($output2);
                                                                                                                    $pendingCount = $row2['rejected'];
                                                                                                                    echo $pendingCount;
                                                                                                                    ?> )</b>
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                        <!-------------------------dashboard------------------------------>
                                        <div class="tab-content tabcontent-border">
                                            <div class="tab-pane p-20 active show" id="dashboard" role="tabpanel">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <!-- <div class="card-header"> -->
                                                        <h4 class="card-title m-b-0"><b></b></h4><br>

                                                        <br>
                                                        <div class="row">
                                                            <!-- Pending -->
                                                            <div class="col-12 col-md-3" style="margin-bottom: 40px">
                                                                <div class="cir">
                                                                    <div class="bo">
                                                                        <div class="content1">
                                                                            <div class="stats-box text-center p-3" style="background-color:orange;">
                                                                                <i class="fas fa-clock"></i>
                                                                                <h1 class="font-light text-white">
                                                                                    <?php $query2 = "SELECT COUNT(*) as pending FROM complaints_detail WHERE  status ='4'";
                                                                                    $output2 = mysqli_query($conn, $query2);
                                                                                    $row2 = mysqli_fetch_assoc($output2);
                                                                                    $pendingCount = $row2['pending'];
                                                                                    echo $pendingCount;
                                                                                    ?>
                                                                                </h1>
                                                                                <small class="font-light">Pending</small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Faculty infra Pending -->
                                                            <div class="col-12 col-md-3">
                                                                <div class="cir">
                                                                    <div class="bo">
                                                                        <div class="content1">
                                                                            <div class="stats-box text-center p-3" style="background-color:red;">
                                                                                <i class="fas fa-exclamation"></i>
                                                                                <h1 class="font-light text-white">
                                                                                    <?php $query2 = "SELECT COUNT(*) as pending FROM complaints_detail WHERE  status ='2'";
                                                                                    $output2 = mysqli_query($conn, $query2);
                                                                                    $row2 = mysqli_fetch_assoc($output2);
                                                                                    $pendingCount = $row2['pending'];
                                                                                    echo $pendingCount;
                                                                                    ?>
                                                                                </h1>
                                                                                <small class="font-light">HOD Pending</small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Approved -->
                                                            <div class="col-12 col-md-3">
                                                                <div class="cir">
                                                                    <div class="bo">
                                                                        <div class="content1">
                                                                            <div class="stats-box text-center p-3" style="background-color:rgb(14, 86, 239);">
                                                                                <i class="fas fa-check"></i>
                                                                                <h1 class="font-light text-white">
                                                                                    <?php $query2 = "SELECT COUNT(*) as approved FROM complaints_detail WHERE (status ='20' or status ='6' or status='7' or status='10' or status='11' or status='13' or status='14' or status='15' or status='17' or status='18')";
                                                                                    $output2 = mysqli_query($conn, $query2);
                                                                                    $row2 = mysqli_fetch_assoc($output2);
                                                                                    $pendingCount = $row2['approved'];
                                                                                    echo $pendingCount;
                                                                                    ?>
                                                                                </h1>
                                                                                <small class="font-light">Approved</small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Completed -->
                                                            <div class="col-12 col-md-3">
                                                                <div class="cir">
                                                                    <div class="bo">
                                                                        <div class="content1">
                                                                            <div class="stats-box text-center p-3" style="background-color:rgb(70, 160, 70);">
                                                                                <i class="mdi mdi-check-all"></i>
                                                                                <h1 class="font-light text-white">
                                                                                    <?php $query2 = "SELECT COUNT(*) as completed FROM complaints_detail WHERE status ='16'";
                                                                                    $output2 = mysqli_query($conn, $query2);
                                                                                    $row2 = mysqli_fetch_assoc($output2);
                                                                                    $pendingCount = $row2['completed'];
                                                                                    echo $pendingCount;
                                                                                    ?>
                                                                                </h1>
                                                                                <small class="font-light">Completed</small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-------------------------pending tab---------------------------->
                                            <div class="tab-pane p-20" id="pending" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4>
                                                                    Raise Complaint
                                                                    <button type="button" style="float:right; font-size:20px;"
                                                                        class="btn btn-info mdi mdi-plus-box-outline" data-toggle="modal" data-target="#cmodal"></button><br>
                                                                </h4>
                                                            </div>

                                                            <div class="card-body">
                                                                <div class="table-container">
                                                                    <table id="myTable1" class="table table-bordered table-striped fixed-size-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="pending status text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 40px;">
                                                                                    <b>S.No</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 80px;">
                                                                                    <b>Date Registered</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 70px;">
                                                                                    <b>Faculty Name</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Problem Description</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Image</b>
                                                                                </th>

                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Action</b>
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $id = 1;
                                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                            ?>
                                                                                <tr>
                                                                                    <td class="text-center">
                                                                                        <?php echo $id; ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <?php echo $row['date_of_reg']; ?>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                class="btn btn-link faculty" id="facultyinfo"
                                                                                                data-value="<?php echo $row['fac_id']; ?>"
                                                                                                data-toggle="modal" value="<?php echo $row['id']; ?>"
                                                                                                data-target="#facultymodal" style="text-decoration:none;"><?php echo $row['faculty_name']; ?>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                data-problemid
                                                                                                class="btn btndesc" id="seeproblem"
                                                                                                data-toggle="modal" value='<?php echo $row['id']; ?>'
                                                                                                data-target="#probdesc">
                                                                                                <i class="fas fa-solid fa-eye" style="font-size: 20px;"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                class="btn showImage"
                                                                                                value="<?php echo $row['id']; ?>"
                                                                                                data-toggle="modal"
                                                                                                data-target="#imageModal1"
                                                                                                data-task-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                                                <i class="fas fa-image" style="font-size: 20px;"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>

                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                value="<?php echo $row['id']; ?>"
                                                                                                id="detail_id"
                                                                                                class="btn btn-success btnapprovefac">
                                                                                                <i class="fas fa-check"></i>
                                                                                            </button>
                                                                                            <button type="button"
                                                                                                value="<?php echo $row['id']; ?>"
                                                                                                class="btn btn-danger btnrejectfac"
                                                                                                data-toggle="modal"
                                                                                                data-target="#rejectmodal">
                                                                                                <i class="fas fa-times"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>
                                                                                <?php
                                                                                $id++;
                                                                            }
                                                                                ?>
                                                                                </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--------------approved tab-------------------->
                                            <div class="tab-pane p-20" id="approved" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="table-container">
                                                                    <table id="myTable2" class="table table-bordered table-striped fixed-size-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="pending status text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 40px;">
                                                                                    <b>S.No</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 80px;">
                                                                                    <b>Date Registered</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 70px;">
                                                                                    <b>Faculty Name</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Problem Description</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Image</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Status</b>
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $id = 1;
                                                                            while ($row = mysqli_fetch_assoc($result1)) {
                                                                            ?>
                                                                                <tr>
                                                                                    <td class="text-center">
                                                                                        <?php echo $id; ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <?php echo $row['date_of_reg']; ?>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                class="btn btn-link faculty" id="facultyinfo"
                                                                                                data-value="<?php echo $row['fac_id']; ?>"
                                                                                                data-toggle="modal" value="<?php echo $row['id']; ?>"
                                                                                                data-target="#facultymodal" style="text-decoration:none;"><?php echo $row['faculty_name']; ?></button>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                value='<?php echo $row['id']; ?>'
                                                                                                class="btn btndesc"
                                                                                                data-toggle="modal" id="seeproblem"
                                                                                                data-target="#probdesc">
                                                                                                <i class="fas fa-solid fa-eye" style="font-size: 20px;"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                class="btn showImage"
                                                                                                value="<?php echo $row['id']; ?>"
                                                                                                data-toggle="modal"
                                                                                                data-target="#imageModal1"
                                                                                                data-task-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                                                <i class="fas fa-image" style="font-size: 20px;"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>

                                                                                    <td>
                                                                                        <center>
                                                                                            <?php
                                                                                            $statusMessages = [
                                                                                                1 => 'Pending',
                                                                                                2 => 'Approved by infra',
                                                                                                3 => 'Rejected by infra',
                                                                                                4 => 'Forwaded to Estate Officer',
                                                                                                6 => 'Sent to principal for approval',
                                                                                                7 => 'Assigned to worker',
                                                                                                10 => 'Work in progress',
                                                                                                11 => 'Waiting for approval',
                                                                                                13 => 'Sent to infra for completion',
                                                                                                14 => 'Waiting to be Reassigned',
                                                                                                15 => 'Work is Reassigned',
                                                                                                17 => 'Work in Progress',
                                                                                                18 => 'Waiting for Approval',
                                                                                                22 => 'Accepted by EO',
                                                                                                23 => 'Rejected by EO',
                                                                                            ];

                                                                                            $status = $row['status'];
                                                                                            $statusMessage = $statusMessages[$status] ?? 'Unknown status';
                                                                                            ?>
                                                                                            <button type="button" class="btn btn-secondary">
                                                                                                <?php echo $statusMessage; ?>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php
                                                                                $id++;
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-----------completed tab----------->
                                            <div class="tab-pane p-20" id="completed" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="table-container">
                                                                    <table id="myTable3" class="table table-bordered table-striped fixed-size-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="pending status text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 40px;">
                                                                                    <b>S.No</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 80px;">
                                                                                    <b>Date Registered</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 70px;">
                                                                                    <b>Faculty Name</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Problem Description</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Before Image</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>After Image</b>
                                                                                </th>

                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $id = 1;
                                                                            while ($row = mysqli_fetch_assoc($result2)) {
                                                                            ?>
                                                                                <tr>
                                                                                    <td class="text-center">
                                                                                        <?php echo $id; ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <?php echo $row['date_of_reg']; ?>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                class="btn btn-link faculty" id="facultyinfo"
                                                                                                data-value="<?php echo $row['fac_id']; ?>"
                                                                                                data-toggle="modal" value="<?php echo $row['id']; ?>"
                                                                                                data-target="#facultymodal" style="text-decoration:none;"><?php echo $row['faculty_name']; ?></button>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                value="<?php echo $row['id']; ?>"
                                                                                                class="btn btndesc"
                                                                                                data-toggle="modal" id="seeproblem"
                                                                                                data-target="#probdesc">
                                                                                                <i class="fas fa-solid fa-eye" style="font-size: 20px;"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                class="btn showImage"
                                                                                                value="<?php echo $row['id']; ?>"
                                                                                                data-toggle="modal"
                                                                                                data-target="#imageModal1"
                                                                                                data-task-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                                                <i class="fas fa-image" style="font-size: 20px;"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                value="<?php echo htmlspecialchars($row['id']); ?>"
                                                                                                class="btn viewafterimgcomp"
                                                                                                data-toggle="modal"
                                                                                                data-target="#aftercomp"
                                                                                                data-imgs-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                                                <i class="fas fa-image" style="font-size: 20px;"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>

                                                                                </tr>
                                                                            <?php
                                                                                $id++;
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!----------rejected tab------->
                                            <div class="tab-pane p-20" id="rejected" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="table-container">
                                                                    <table id="myTable4" class="table table-bordered table-striped fixed-size-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="pending status text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 40px;">
                                                                                    <b>S.No</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 80px;">
                                                                                    <b>Date Registered</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white; width: 70px;">
                                                                                    <b>Faculty Name</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Problem Description</b>
                                                                                </th>
                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Image</b>
                                                                                </th>

                                                                                <th class="text-center"
                                                                                    style="background-color: #7460ee; background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                                                                                    <b>Status</b>
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $id = 1;
                                                                            while ($row = mysqli_fetch_assoc($result3)) {
                                                                            ?>
                                                                                <tr>
                                                                                    <td class="text-center">
                                                                                        <?php echo $id; ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <?php echo $row['date_of_reg']; ?>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                class="btn btn-link faculty" id="facultyinfo"
                                                                                                data-value="<?php echo $row['fac_id']; ?>"
                                                                                                data-toggle="modal" value="<?php echo $row['id']; ?>"
                                                                                                data-target="#facultymodal" style="text-decoration:none;"><?php echo $row['faculty_name']; ?></button>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                value="<?php echo $row['id']; ?>"
                                                                                                class="btn btndesc"
                                                                                                data-toggle="modal" id="seeproblem"
                                                                                                data-target="#probdesc">
                                                                                                <i class="fas fa-solid fa-eye" style="font-size: 20px;"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button"
                                                                                                value="<?php echo $row['id']; ?>"
                                                                                                class="btn showImage"
                                                                                                data-toggle="modal"
                                                                                                data-target="#imageModal1"
                                                                                                data-task-id='<?php echo htmlspecialchars($row['id']); ?>'>
                                                                                                <i class="fas fa-image" style="font-size: 20px;"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <button type="button" value="<?php echo $row['id']; ?>" class="btn btn-danger btnrejfeedfac" data-toggle="modal"
                                                                                                data-target="#problemrejected" id="rejectedfeedback">
                                                                                                <i class="fas fa-solid fa-comment" style="font-size: 20px; width:40px;"></i>
                                                                                            </button>
                                                                                        </center>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php
                                                                                $id++;
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer text-center">
            <b>
                2024 © M.Kumarasamy College of Engineering All Rights Reserved.<br>
                Developed and Maintained by Technology Innovation Hub.
            </b>
        </footer>
    </div>

    <!------------Rejected Feedback modal----->
    <div class="modal fade" id="rejectmodal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Reason for rejection</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="rejectdetails">
                    <div class="modal-body" style="font-size:larger;">
                        <textarea class="form-control"
                            placeholder="Enter Reason"
                            name="rejfeedfac"
                            style="width:460px;height: 180px; resize:none" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--faculty info modal-->
    <div class="modal fade" id="facultymodal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title" id="exampleModalLabel">Faculty Information</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 15px; font-size: 1.1em; color: #333; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                    <ol class="list-group list-group-numbered" style="margin-bottom: 0;">
                        <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">INFRA Name</div>
                                <b><span id="ifaculty_name" style="color: #555;"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Faculty Name</div>
                                <b><span id="faculty_name" style="color: #555;"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Faculty Department</div>
                                <b><span id="faculty_dept" style="color: #555;"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Faculty Designation</div>
                                <b><span id="faculty_desg" style="color: #555;"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Mobile Number</div>
                                <b><span id="ifaculty_contact" style="color: #555;"></span></b>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">E-mail</div>
                                <b><span id="ifaculty_mail" style="color: #555;"></span></b>
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!---view problem description modal-->
    <div class="modal fade" id="probdesc" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Problem Description</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addnewdetails">
                    <div class="modal-body" style="padding: 15px; font-size: 1.1em; color: #333; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        <ol class="list-group list-group-numbered" style="margin-bottom: 0;">
                            <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Problem ID</div>
                                    <b><span id="id" style="color: #555;"></span></b>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Type of Problem</div>
                                    <b><span id="type_of_problem" style="color: #555;"></span></b>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Block</div>
                                    <b><span id="block_venue" style="color: #555;"></span></b>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Venue Name</div>
                                    <b><span id="venue_name" style="color: #555;"></span></b>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start" style="padding: 10px; background-color: #fff;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold" style="font-size: 1.2em; font-weight: 600; color: #007bff;">Problem Description</div>
                                    <b><span id="pd" style="color: #555;padding-top:5px;"></span></b>
                                </div>
                            </li>
                        </ol>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Raise Complaint Modal -->
    <div id="cmodal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title">Raise Complaint</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addcomp" enctype="multipart/form-data" onsubmit="handleSubmit(event)">
                    <div class="modal-body">
                       

                      

                        <div class="mb-3">
                            <label for="block" class="form-label">Block <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="block_venue" placeholder="Eg:RK-206" required>
                        </div>
                        <div class="mb-3">
                            <label for="venue" class="form-label">Venue <span style="color: red;">*</span></label>
                            <select id="dropdown" class="form-control" name="venue_name" onchange="checkIfOthers()">
                                <option>Select</option>
                                <option value="class">Class Room</option>
                                <option value="department">Department</option>
                                <option value="lab">Lab</option>
                                <option value="staff_room">Staff Room</option>
                                <option id="oth" value="Other">Others</option>
                            </select>
                        </div>

                        <div id="othersInput" style="display: none;">
                            <label class="form-label" for="otherValue">Please specify: <span style="color: red;">*</span></label>
                            <input class="form-control" type="text" id="otherValue" name="otherValue">
                        </div>

                        <div class="mb-3">
                            <label for="type_of_problem" class="form-label">Type of Problem <span style="color: red;">*</span></label>
                            <select class="form-control" name="type_of_problem">
                                <option>Select</option>
                                <option value="elecrtical">ELECTRICAL</option>
                                <option value="civil">CIVIL</option>
                                <option value="itkm">IT INFRA</option>
                                <option value="transport">TRANSPORT</option>
                                <option value="house">HOUSE KEEPING</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Problem Description <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="problem_description" placeholder="Enter Description" required>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Image <span style="color: red;">*</span> </label>
                            <input type="file" class="form-control" name="images" id="images" onchange="validateSize(this)" required>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" class="form-control" name="date_of_reg" id="date_of_reg" required>
                        </div>
                    </div>
                    <input type="hidden" name="status" value="2">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Modal-->
    <div id="imageModal1" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title">View Image</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Image Preview" style="max-width: 100%; display: none;" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-----------After Image Modal------------>
    <div id="aftercomp" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title">View Image</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="afterimgcomp" src="" alt="Image Preview" style="max-width: 100%; display: none;" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="raisemodal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background:linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%);background-color:#7460ee;">
                                                    <h5 class="modal-title" id="exampleModalLabel">Raise Complaint</h5>
                                                    <button class="spbutton" type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                </div>
                                                <div>
                                                    <form id="addnewuser" enctype="multipart/form-data" onsubmit="handleSubmit(event)">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <input type="hidden" id="hidden_faculty_id" value="<?php echo $_SESSION['faculty_id']; ?>">
                                                                <input type="hidden" class="form-control" name="faculty_id" id="faculty_id" value="<?php echo $_SESSION['faculty_id']; ?>" readonly>
                                                            </div>
                                                            

                                                            <div class="mb-3">
                                                                <label for="block" class="form-label">Block <span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="block_venue" placeholder="Eg:RK-206" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="venue" class="form-label">Venue <span style="color: red;">*</span></label>
                                                                <select id="dropdown" class="form-control" name="venue_name" onchange="checkIfOthers()"
                                                                    style="width: 100%; height:36px;">
                                                                    <option>Select</option>
                                                                    <option value="class">Class Room</option>
                                                                    <option value="department">Department</option>
                                                                    <option value="lab">Lab</option>
                                                                    <option value="staff_room">Staff Room</option>
                                                                    <option id="oth" value="Other">Others</option>
                                                                </select>
                                                            </div>

                            <div id="othersInput" style="display: none;">
                                <label class="form-label" for="otherValue">Please specify: <span style="color: red;">*</span></label>
                                <input class="form-control" type="text" id="otherValue" name="otherValue"> <br>
                            </div>


    <!------------Rejected Reason modal-------------->
    <div class="modal fade" id="problemrejected" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(to bottom right, #cc66ff 1%, #0033cc 100%); color: white;">
                    <h5 class="modal-title" id="exampleModalLabel">Reason for Rejection</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addnewdetails">
                    <div class="modal-body" style="font-size:larger;">
                        <textarea type="text" id="pdrej2" value="" style="width:460px;height: 180px; resize: none;" disabled>
                        </textarea>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="dist/js/waves.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
    <script src="assets/extra-libs/multicheck/jquery.multicheck.js"></script>
    <script src="assets/extra-libs/DataTables/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Set Today date in Raise Complaint-->
    <script>
        var today = new Date().toISOString().split('T')[0];
        var dateInput = document.getElementById('date_of_reg');
        dateInput.setAttribute('min', today);
        dateInput.setAttribute('max', today);
        dateInput.value = today;
    </script>

    <!--file size and type -->
    <script>
        function validateSize(input) {
            const filesize = input.files[0].size / 1024; // Size in KB
            var ext = input.value.split(".");
            ext = ext[ext.length - 1].toLowerCase();
            var arrayExtensions = ["jpg", "jpeg", "png"];
            if (arrayExtensions.lastIndexOf(ext) == -1) {
                swal("Invalid Image Format, Only .jpeg, .jpg, .png format allowed", "", "error");
                $(input).val('');
            } else if (filesize > 2048) {
                swal("File is too large, Maximum 2 MB is allowed", "", "error");
                $(input).val('');
            }
        }
    </script>

    <script>

        //Tool Tip
        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.btnrejectfac').tooltip({
                placement: 'top',
                title: 'Reject'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.btnrejfeedfac').tooltip({
                placement: 'top',
                title: 'Rejected Reason'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.btndesc').tooltip({
                placement: 'top',
                title: 'Problem Description'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.viewafterimgcomp').tooltip({
                placement: 'top',
                title: 'After Image'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.btnraisecomp').tooltip({
                placement: 'top',
                title: 'Raise Complaint'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.btnapprovefac').tooltip({
                placement: 'top',
                title: 'Accept'
            });
        });

        $(function() {
            // Initialize the tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // You can also set options manually if needed
            $('.showImage').tooltip({
                placement: 'top',
                title: 'Before Image'
            });
        });

        alertify.set('notifier', 'position', 'top-right');
        $(document).ready(function() {
            $('#myTable1').DataTable();
            $('#myTable2').DataTable();
            $('#myTable3').DataTable();
            $('#myTable4').DataTable();
        });

        //Reject Button with Feedback
        $('#rejectdetails').on('submit', function(e) {
            e.preventDefault();

            if (confirm('Are you sure you want to reject this complaint?')) {
                var formdata1 = new FormData(this);
                var reject_idfac = $('.btnrejectfac').val();
                formdata1.append("reject_idfac", reject_idfac);
                $.ajax({
                    type: "POST",
                    url: "hodbackend.php",
                    data: formdata1,
                    processData: false,
                    contentType: false,

                    success: function(response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 200) {
                            $('#rejectmodal').modal('hide');
                            $('#rejectdetails')[0].reset();
                            $('#myTable1').load(location.href + " #myTable1");
                            $('#myTable4').load(location.href + " #myTable4");
                            $('#myTable1').DataTable().destroy();
                            $('#myTable4').DataTable().destroy();
                            $("#myTable1").load(location.href + " #myTable1 > *", function() {
                                $('#myTable1').DataTable();
                            });
                            $("#myTable4").load(location.href + " #myTable4 > *", function() {
                                $('#myTable4').DataTable();
                            });
                            $('#navref1').load(location.href + " #navref1");
                            $('#navref4').load(location.href + " #navref4");

                        } else if (res.status == 500) {
                            alertify.error('Complaint Rejected!');
                            $('#rejectmodal').modal('hide');
                            $('#rejectdetails')[0].reset();
                            console.error("Error:", res.message);
                            alert("Something Went wrong.! try again")
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error);
                        alert("An error occurred: " + error);
                    }
                });
            }
        });

        //approve button
        $(document).on('click', '.btnapprovefac', function(e) {
            e.preventDefault();

            var approveidfac = $(this).val();

            alertify.confirm('Confirmation', 'Are you sure you want to approve this complaint?',
                function() {
                    $.ajax({
                        type: "POST",
                        url: "hodbackend.php",
                        data: {
                            'approvefacbtn': true,
                            'approvefac': approveidfac
                        },
                        success: function(response) {
                            var res = jQuery.parseJSON(response);
                            if (res.status == 500) {
                                alertify.error(res.message);
                            } else {
                                alertify.success('Complaint Approved successfully!');
                                $('#myTable1').DataTable().destroy();
                                $('#myTable2').DataTable().destroy();
                                $('#myTable3').DataTable().destroy();
                                $("#myTable1").load(location.href + " #myTable1 > *", function() {
                                    $('#myTable1').DataTable();
                                });
                                $("#myTable2").load(location.href + " #myTable2 > *", function() {
                                    $('#myTable2').DataTable();
                                });
                                $("#myTable3").load(location.href + " #myTable3 > *", function() {
                                    $('#myTable3').DataTable();
                                });
                                $('#navref1').load(location.href + " #navref1");
                                $('#navref2').load(location.href + " #navref2");
                                $('#navref3').load(location.href + " #navref3");
                                $('#navref4').load(location.href + " #navref4");
                            }
                        }
                    });
                },
                function() {
                    alertify.error('Approval canceled');
                });
        });

        // problem description
        $(document).on('click', '#seeproblem', function(e) {
            e.preventDefault();
            var user_id = $(this).val();
            console.log(user_id)
            $.ajax({
                type: "POST",
                url: "hodbackend.php",
                data: {
                    'seedetails': true,
                    'user_id': user_id
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res)
                    if (res.status == 500) {
                        alert(res.message);
                    } else {
                        $("#id").text(res.data.id);
                        $("#type_of_problem").text(res.data.type_of_problem);
                        $("#block_venue").text(res.data.block_venue);
                        $("#venue_name").text(res.data.venue_name);
                        $('#pd').text(res.data.problem_description);
                        $('#probdesc').modal('show');
                    }
                }
            });
        });

        //Image Modal Ajax
        $(document).on('click', '.showImage', function() {
            var task_id = $(this).data('task-id');
            $('#task_id').val(task_id);

            $.ajax({
                type: "POST",
                url: "hodbackend.php",
                data: {
                    'get_image': true,
                    'task_id': task_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        $('#modalImage').attr('src', response.data.images).show();
                    } else {
                        $('#modalImage').hide();
                        alert(response.message);
                    }
                    $('#imageModal1').modal('show');
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while retrieving the image.');
                }
            });
        });

        //After Image Modal
        $(document).on('click', '.viewafterimgcomp', function() {
            var task_id = $(this).data('imgs-id');
            $('#task_id').val(task_id);

            // Fetch the image from the server
            $.ajax({
                type: "POST",
                url: "hodbackend.php",
                data: {
                    'after_image': true,
                    'task_id': task_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        $('#afterimgcomp').attr('src', response.data.after_photo).show();
                    } else {
                        $('afterimgcomp').hide();
                        alert(response.message);
                    }
                    $('#viewimgaftercomp').modal('show');
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while retrieving the image.');
                }
            });
        });

        // faculty info
        $(document).on('click', '#facultyinfo', function(e) {
            e.preventDefault();
            var user_id = $(this).val();
            var fac_id = $("#facultyinfo").data("value");

            console.log(user_id);
            console.log(fac_id);
            $.ajax({
                type: "POST",
                url: "hodbackend.php",
                data: {
                    'facultydetails': true,
                    'user_id': user_id,
                    'fac_id': fac_id
                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res)
                    if (res.status == 500) {
                        alert(res.message);
                    } else {
                        $("#id").val(res.data.id);
                        $("#ifaculty_name").text(res.data.faculty_name);
                        $("#ifaculty_mail").text(res.data.faculty_mail);
                        $("#ifaculty_contact").text(res.data.faculty_contact);
                        $('#faculty_name').text(res.data1.name);
                        $('#faculty_id').text(res.data1.id);
                        $('#faculty_dept').text(res.data1.dept);
                        $('#faculty_desg').text(res.data1.design);
                        $('#facultymodal').modal('show');
                    }
                }
            });
        });

        //Rejected Tab Feedback
        $(document).on('click', '#rejectedfeedback', function(e) {
            e.preventDefault();
            var user_idrej = $(this).val();
            console.log(user_idrej)
            $.ajax({
                type: "POST",
                url: "hodbackend.php",
                data: {
                    'seefeedback': true,
                    'user_idrej': user_idrej

                },
                success: function(response) {
                    var res = jQuery.parseJSON(response);
                    console.log(res)
                    if (res.status == 500) {
                        alert(res.message);
                    } else {
                        $('#pdrej2').val(res.data.feedback);
                        $('#problemrejected').modal('show');
                    }
                }
            });
        });

        $(document).on('submit', '#addcomp', function(e) {
            e.preventDefault(); // Prevent form from submitting normally
            var formData = new FormData(this);
            formData.append("eo",true);
            $.ajax({
                type: "POST",
                url: "eo_backend.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.status === 200) {
                        swal("Complaint Submitted!", "", "success");
                        $('#cmodal').modal('hide');
                        $('#addcomp')[0].reset(); // Reset the form
                        $('#navref1').load(location.href + " #navref1");
                        $('#navref2').load(location.href + " #navref2");
                        $('#navref3').load(location.href + " #navref3");
                        $('#dashref').load(location.href + " #dashref");

                        $('#user').DataTable().destroy();
                        $("#user").load(location.href + " #user > *", function() {
                            $('#user').DataTable();
                        });
                    } else {
                        console.error("Error:", res.message);
                        alert("Something went wrong! Try again.");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                    alert("Failed to process response. Please try again.");
                }
            });
        });


        function checkIfOthers() {
            const dropdown = document.getElementById('dropdown');
            const othersInput = document.getElementById('othersInput');

            // Show the input field if "Others" is selected
            if (dropdown.value === 'Other') {
                othersInput.style.display = 'block';
            } else {
                othersInput.style.display = 'none';
            }
        }

        function handleSubmit(event) {
            event.preventDefault(); // Prevent form submission for demo purposes
            const dropdown = document.getElementById('dropdown');
            const selectedValue = dropdown.value;
            let finalValue;

            // Get the appropriate value based on the dropdown selection
            if (selectedValue === 'Other') {
                finalValue = document.getElementById('otherValue').value;
            } else {
                finalValue = selectedValue;
            }

            console.log("Selected Category:", finalValue);
            // You can then send this data to the backend or process it further
            $("#oth").val(finalValue);
        }
        
    </script>
</body>

</html>