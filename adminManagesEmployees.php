<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Manage Employee - Admin</title>
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

      $query2 = "SELECT * FROM employee";
      $result2 = mysqli_query($connect, $query2) or die (mysqli_error($connect));
      $employeeCount = mysqli_num_rows($result2);

      $query3 = "SELECT * FROM employee WHERE status = 'Active'";
      $result3 = mysqli_query($connect, $query3) or die (mysqli_error($connect));
      $employeeCountA = mysqli_num_rows($result3);

      $query4 = "SELECT * FROM employee WHERE status = 'Inactive'";
      $result4 = mysqli_query($connect, $query4) or die (mysqli_error($connect));
      $employeeCountI = mysqli_num_rows($result4);
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
            <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($profilePicture); ?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $department ?></span>
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
        <a class="nav-link collapsed" href="adminManagesApplications.php">
          <i class="bi bi-pen"></i>
          <span>Applications</span>
        </a>
      </li><!-- End Application Nav -->
      <li class="nav-item">
        <a class="nav-link " href="adminManagesEmployees.php">
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
      <h1>Manage Employee</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="adminDashboard.php">Home</a></li>
          <li class="breadcrumb-item">Employees</li>
          <li class="breadcrumb-item active">Manage Employee</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <!-- Left side columns -->
          <div class="col-lg-8">
            <div class="row">
              <!-- Employees Registerd Card -->
              <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                  <div class="card-body">
                    <h5 class="card-title">Employees</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                      </div>
                      <div class="ps-3">
                        <h6><?php echo $employeeCount ?></h6>
                        <span class="text-muted small pt-2 ps-1">Registered</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- End Employees Registered Card -->
              <!-- Employees Active Card -->
              <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">
                  <div class="card-body">
                    <h5 class="card-title">Employees</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                      </div>
                      <div class="ps-3">
                        <h6><?php echo $employeeCountA ?></h6>
                        <span class="text-muted small pt-2 ps-1">Active</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- End Employees Card -->
              <!-- Employeed Inactive Card -->
              <div class="col-xxl-4 col-md-6">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <h5 class="card-title">Employees</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                      </div>
                      <div class="ps-3">
                        <h6><?php echo $employeeCountI ?></h6>
                        <span class="text-muted small pt-2 ps-1">Inactive</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- End Employees Inactive Card -->
            </div>
          </div><!-- End Left side columns -->
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">List of Employees</h5>
              <div class="row">
                <div class="col-lg-12">
                  <a href="adminAddsEmployees.php"><button type='button' class='btn btn-success mb-2'><i class='ri-add-circle-fill'></i> Add Employee</button></a>
                </div>
              </div>    
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Department</th>
                    <th scope="col">Position</th>
                    <th scope="col">Status</th>
                    <th scope="col">Registered Date</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $query5 = "SELECT * FROM employee";
                    $result5 = mysqli_query($connect, $query5) or die (mysqli_error($connect));

                    if(mysqli_num_rows($result5) > 0) {
                      foreach($result5 as $row5) {
                        $employeeID = $row5["employeeID"];
                        $employeeName = $row5["name"];
                        $employeeDepartment = $row5["department"];
                        $employeePosition = $row5["position"];
                        $employeeStatus = $row5["status"];
                        $employeeRegisteredDate = $row5["registeredDate"];
                        
                        echo "<tr>";
                            echo "<td>".$employeeName."</td>";
                            echo "<td>".$employeeDepartment."</td>";
                            echo "<td>".$employeePosition."</th>";
                            if($employeeStatus === "Active") {
                              echo "<td><span class='badge bg-success'>".$employeeStatus."</span></td>";
                            }
                            else if($employeeStatus === "Inactive") {
                              echo "<td><span class='badge bg-danger'>".$employeeStatus."</span></td>";
                            }
                            echo "<td>".$employeeRegisteredDate."</td>";
                            echo "<td>
                                    <a href='adminEditsEmployees.php?employeeID=".$employeeID."'><button type='button' class='btn btn-primary mb-2'><i class='bx bxs-edit'></i> Edit</button></a>
                                    <a href='adminDeletesEmployees.php?employeeID=".$employeeID."'><button type='button' class='btn btn-danger mb-2' onclick='del(event)'<i class='bx bx-x'></i> Delete</button></a>
                                  </td>";
                        echo "</tr>";
                      }
                    }
                ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->
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
          text: "Do you really want to delete this employee? This process cannot be undone",
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