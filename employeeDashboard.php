<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - Employee</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/company-logo.png" rel="icon">
  <link href="assets/img/company-logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <?php
    include("config.php");
    session_start();

    if(isset($_SESSION["sID"])) { 
      if($_SESSION["login"] === true) {
        ?>
          <script>
            Swal.fire({
              icon: 'success',
              title: 'Logged In',
            })
          </script>
        <?php
        $_SESSION["login"] = false; 
      }

      $email = "";
      $name = ""; 
      $profilePicture = "";
      $department = "";
      $position = "";
      $total = 0;
      $available = 0;
      $taken = 0;
      $t = 0;
      $ID = $_SESSION["userID"];
      
      $query1 = "SELECT * FROM employee WHERE employeeID = '$ID'";
      $result1 = mysqli_query($connect, $query1) or die (mysqli_error($connect));

      if(mysqli_num_rows($result1) === 1) {
        foreach($result1 as $row1) {
          $email = $row1["email"];
          $name = $row1["name"];
          $profilePicture = $row1["profilePicture"];
          $department = $row1["department"];
          $position = $row1["position"];
        }
      }

      $query2 = "SELECT * FROM balance WHERE employeeID = '$ID'";
      $result2 = mysqli_query($connect, $query2) or die (mysqli_error($connect));

      if(mysqli_num_rows($result2) > 0) {
        foreach($result2 as $row2) {
          $daysAvailable = $row2["daysAvailable"];
          $daysTaken = $row2["daysTaken"];
          $t = $daysAvailable - $daysTaken;
          $total = $total + $daysAvailable;
          $available = $available + $daysTaken;
          $taken = $taken + $t;
        }
      }
    }
  ?>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="employeeDashboard.php" class="logo d-flex align-items-center">
        <img src="assets/img/company-logo.png" alt="">
        <span class="d-none d-lg-block">Reitech Solution</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->
    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="data:image/*;charset=utf8;base64,<?php echo base64_encode($profilePicture); ?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $name ?></span>
          </a><!-- End Profile Image Icon -->
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $department ?></h6>
              <span><?php echo $position ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="employeeUpdatesProfile.php">
                <i class="bi bi-gear"></i>
                <span>Account Setting</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="signOut.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>
          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->
      </ul>
    </nav><!-- End Icons Navigation -->
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link " href="employeeDashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="employeeUpdatesProfile.php">
          <i class="bi bi-pen"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Application Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="employeeContactsHR.php">
          <i class="bi bi-people"></i>
          <span>Contact </span>
        </a>
        </li><!-- End Employee Nav -->
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="employeeDashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <!-- Left side columns -->
          <div class="col-lg-8">  
            <div class="row">
              <!-- Total Days Card -->
              <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                  <div class="card-body">
                    <h5 class="card-title">Total</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-briefcase"></i>
                      </div>
                      <div class="ps-3">
                        <h6><?php echo $total ?></h6>
                        <span class="text-muted small pt-2 ps-1">Days</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- End Total Days Card -->
              <!-- Taken Days Card -->
              <div class="col-xxl-4 col-md-6">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <h5 class="card-title">Taken</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-briefcase"></i>
                      </div>
                      <div class="ps-3">
                      <h6><?php echo $available ?></h6>
                        <span class="text-muted small pt-2 ps-1">Days</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- End Taken Days Card -->
              <!-- Available Days Card -->
              <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">
                  <div class="card-body">
                    <h5 class="card-title">Available</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-briefcase"></i>
                      </div>
                      <div class="ps-3">
                      <h6><?php echo $taken ?></h6>
                        <span class="text-muted small pt-2 ps-1">Days</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- End Available Days Card -->
              </div>
          </div><!-- End Left side columns -->
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Leave Balance</h5>
              <!-- Table Leave Balance -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">Leave Type</th>
                    <th scope="col">Total</th>
                    <th scope="col">Taken</th>
                    <th scope="col">Available</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      $query3 = "SELECT * FROM balance WHERE employeeID = '$ID'";
                      $result3 = mysqli_query($connect, $query3) or die (mysqli_error($connect));

                      if(mysqli_num_rows($result3) > 0) {
                        foreach($result3 as $row3) {
                          $leaveType = $row3["leaveType"];
                          $daysAvailable = $row3["daysAvailable"];
                          $daysTaken = $row3["daysTaken"];
                          $daysRemaining = $daysAvailable - $daysTaken;
                          
                          echo "<tr>";
                            echo "<td>".$leaveType."</th>";
                            echo "<td>".$daysAvailable."</td>";
                            echo "<td>".$daysTaken."</td>";
                            echo "<td>".$daysRemaining."</td>";
                          echo "</tr>";
                        }
                      }
                  ?>
                </tbody>
              </table>
              <!-- End Table Leave Balance -->
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Leave Application</h5>
              <div class="row">
                <div class="col-lg-12">
                  <a href="employeeAppliesLeave.php"><button type='button' class='btn btn-success mb-2'><i class='ri-add-circle-fill'></i>Apply Leave</button></a>
                </div>
              </div>    
              <!-- Table Leave Application -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">Leave Type</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Admin Remark</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      $query4 = "SELECT * FROM application WHERE employeeID = '$ID'";
                      $result4 = mysqli_query($connect, $query4) or die (mysqli_error($connect));

                      if(mysqli_num_rows($result4) > 0) {
                        foreach($result4 as $row4) {
                          $leaveType = $row4["leaveType"];
                          $startDate = $row4["startDate"];
                          $endDate = $row4["endDate"];
                          $adminRemark = $row4["adminRemark"];
                          $status = $row4["status"];

                          echo "<tr>";
                            echo "<td>".$leaveType."</th>";
                            echo "<td>".$startDate."</td>";
                            echo "<td>".$endDate."</td>";
                            echo "<td>".$adminRemark."</td>";

                            if($status === "Approved") {
                              echo "<td><span class='badge bg-success'>".$status."</span></td>";
                            }
                            else if($status === "Rejected") {
                              echo "<td><span class='badge bg-danger'>".$status."</span></td>";
                            }
                            else if($status === "Pending") {
                              echo "<td><span class='badge bg-warning'>".$status."</span></td>";
                            }
                          echo "</tr>";
                        }
                      }
                  ?>
                </tbody>
              </table>
              <!-- End Table Leave Application -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Reitech Solution</span></strong>. All Rights Reserved
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
</body>

</html>