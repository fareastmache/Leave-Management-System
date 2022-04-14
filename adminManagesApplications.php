<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Manage Application - Admin</title>
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
    
    if($_SESSION["sID"] === session_id()) {
      $email = $_SESSION["email"];
      $_SESSION["loggedIn"] = false;
      $contact = "";
      $department = "";
      $position = "";
      $profilePicture = "";
      
      $query1 = "SELECT * FROM admin WHERE email = '$email'";
      $result1 = mysqli_query($connect, $query1) or die (mysqli_error($connect));

      if(mysqli_num_rows($result1) === 1) {
          foreach($result1 as $row1) {
          $contact = $row1["contact"];
          $department = $row1["department"];
          $position = $row1["position"];
          $profilePicture = $row1["profilePicture"];
        }
      }

      $query2 = "SELECT * FROM application WHERE status = 'Approved'";
      $result2 = mysqli_query($connect, $query2) or die (mysqli_error($connect));
      $applicationCount = mysqli_num_rows($result2);

      $query3 = "SELECT * FROM application WHERE status = 'Pending'";
      $result3 = mysqli_query($connect, $query3) or die (mysqli_error($connect));
      $applicationCountP = mysqli_num_rows($result3);

      $query4 = "SELECT * FROM application WHERE status = 'Rejected'";
      $result4 = mysqli_query($connect, $query4) or die (mysqli_error($connect));
      $applicationCountR = mysqli_num_rows($result4);
    }
  ?>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="adminDashboard.php" class="logo d-flex align-items-center">
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
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $department ?></span>
          </a><!-- End Profile Iamge Icon -->
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $department ?></h6>
              <span><?php echo $position ?></span>
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
        <a class="nav-link collapsed" href="adminDashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
      <li class="nav-item">
        <a class="nav-link " href="adminManagesApplications.php">
          <i class="bi bi-pen"></i>
          <span>Applications</span>
        </a>
      </li><!-- End Application Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="adminManagesEmployees.php">
          <i class="bi bi-people"></i>
          <span>Employees</span>
        </a>
        </li><!-- End Employee Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="adminManagesLeaves.php">
          <i class="bi bi-briefcase"></i>
          <span>Leaves</span>
        </a>
      </li><!-- End Leave Nav -->
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Manage Application</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="adminDashboard.php">Home</a></li>
          <li class="breadcrumb-item">Applications</li>
          <li class="breadcrumb-item active">Manage Application</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">
            <!-- Approved Leaves Card -->
            <div class="col-xxl-4 col-xl-12">
              <div class="card info-card revenue-card">
                <div class="card-body">
                  <h5 class="card-title">Application</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-pen"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $applicationCount ?></h6>
                      <span class="text-muted small pt-2 ps-1">Approved</span>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Approved Leaves Card -->
            <!-- Pending Leaves Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Application</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-pen"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $applicationCountP ?></h6>
                      <span class="text-muted small pt-2 ps-1">Pending</span>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Pending Leaves Card -->
            <!-- Rejected Leaves Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title">Application</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-pen"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $applicationCountR ?></h6>
                      <span class="text-muted small pt-2 ps-1">Rejected</span>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Leaves Card -->
          </div>
        </div><!-- End Left side columns -->
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">List of Applications</h5>
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">Employee</th>
                    <th scope="col">Leave Type</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $query5 = "SELECT * FROM application";
                    $result5 = mysqli_query($connect, $query5) or die (mysqli_error($connect));

                    if(mysqli_num_rows($result5) > 0) {
                      foreach($result5 as $row5) {
                        $applicationID = $row5["applicationID"];
                        $leaveType = $row5["leaveType"];
                        $startDate = $row5["startDate"];
                        $endDate = $row5["endDate"];
                        $applicationStatus = $row5["status"];
                        $employeeID = $row5["employeeID"];

                        $query6 = "SELECT * FROM employee WHERE employeeID = '$employeeID'";
                        $result6 = mysqli_query($connect, $query6) or die (mysqli_error($connect));

                        if(mysqli_num_rows($result6) === 1) {
                            foreach($result6 as $row6) {
                                $employeeName = $row6["name"];
                            }
                        }
                        
                        echo "<tr>";
                            echo "<td>".$employeeName."</th>";
                            echo "<td>".$leaveType."</td>";
                            echo "<td>".$startDate."</td>";
                            echo "<td>".$endDate."</td>";
                            if($applicationStatus === "Approved") {
                              echo "<td><span class='badge bg-success'>".$applicationStatus."</span></td>";
                            }
                            else if($applicationStatus === "Rejected") {
                              echo "<td><span class='badge bg-danger'>".$applicationStatus."</span></td>";
                            }
                            else {
                                echo "<td><span class='badge bg-warning'>".$applicationStatus."</span></td>";
                            }
                            echo "<td>
                                    <a href='adminViewsApplications.php?applicationID=".$applicationID."'><button type='button' class='btn btn-primary mb-2'><i class='bx bxs-edit'></i> View</button></a>
                                    <a href='adminDeletesApplications.php?applicationID=".$applicationID."'><button type='button' class='btn btn-danger mb-2' onclick='del(event)'<i class='bx bx-x'></i> Delete</button></a>
                                  </td>";
                        echo "</tr>";
                      }
                    }
                ?>
                </tbody>
              </table>
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

  <script>
    function del(event){
      event.preventDefault();   
      Swal.fire({
          title: 'Are you sure?',
          text: "Do you really want to delete this application? This process cannot be undone",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = event.target.offsetParent.children[1].href;
          }
      })
    }
  </script>	
</body>

</html>