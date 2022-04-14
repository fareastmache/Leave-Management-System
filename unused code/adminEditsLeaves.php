<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Edit Leave - Admin</title>
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
      $leaveID = $_GET["leaveID"];
      $email = $_SESSION["email"];
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

      $query2 = "SELECT * FROM laeve WHERE leaveID = '$leaveID'";
      $result2 = mysqli_query($connect, $query2) or die (mysqli_error($connect));

      $leaveType = "";
      $daysEntitled = "";
      $description = "";

      if(mysqli_num_rows($result2) === 1) {
        foreach($result2 as $row2) {
          $leaveType = $row2["type"];
          $daysEntitled = $row2["daysEntitled"];
          $description = $row2["description"];
        }
      }

      if(isset($_POST["update"])) {
        $leaveType = $_POST["leaveType"]; 
        $daysEntitled = $_POST["daysEntitled"];
        $description = $_POST["description"];

        $query3 = "UPDATE laeve SET type = '$leaveType', daysEntitled = '$daysEntitled', description = '$description' WHERE leaveID = '$leaveID'";
        mysqli_query($connect, $query3) or die (mysqli_error($connect)); 

        $query4 = "SELECT * FROM employee";
        $result4 = mysqli_query($connect, $query4) or die(mysqli_error($connect));

        if(mysqli_num_rows($result4) > 0) {
            foreach($result4 as $row4) {
              $employeeID = $row4["employeeID"];

              $query5 = "UPDATE balance SET daysAvailable = '$daysEntitled' WHERE employeeID = '$employeeID'";
              mysqli_query($connect, $query5) or die(mysqli_error($connect));
            }
          } ?>

        <script>
          Swal.fire({
            icon: 'success',
            title: 'Successfully Update Leave',
          }).then(function() {
            window.location.href = 'adminManagesLeaves.php'
          });
        </script>
      <?php } 
    }
  ?>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
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
        <a class="nav-link collapsed" href="adminManagesEmployees.php">
          <i class="bi bi-people"></i>
          <span>Employees</span>
        </a>
        </li><!-- End Employee Nav -->

      <li class="nav-item">
        <a class="nav-link " href="adminManagesLeaves.php">
          <i class="bi bi-briefcase"></i>
          <span>Leaves</span>
        </a>
      </li><!-- End Leave Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Add Leave</h1>
      <nav>
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="adminDashboard.php">Home</a></li>
          <li class="breadcrumb-item">Leaves</li>
          <li class="breadcrumb-item"><a href="adminManagesLeaves.php">Manage Leave</a></li>
          <li class="breadcrumb-item active">Edit Leave</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Leave Details</h5>

                <!-- Add Employee Form -->
                <form class="row g-3 needs-validation" method="post" novalidate>
                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <!-- <input type="text" class="form-control" name="name" placeholder="Name" pattern="[a-zA-Z ]+" required> -->
                        <input type="text" class="form-control" name="leaveType" placeholder="Leave Type" value="<?php echo $leaveType?>" required>
                        <label for="floatingName">Leave Type</label>
                        <div class="invalid-feedback">Please enter leave type!</div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <!-- <input type="tel" class="form-control" name="contact" placeholder="Contact"  pattern="[0-9]{3}-[0-9]{7}" minlength="11" maxlength="11" required> -->
                        <input type="number" class="form-control" name="daysEntitled" placeholder="Days Entitled" min="1" value="<?php echo $daysEntitled?>" required>
                        <label for="floatingContact">Days Entitled</label>
                        <div class="invalid-feedback">Please enter days entitled!</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="form-floating has-validation">
                        <!-- <textarea class="form-control" placeholder="Address" name="address" style="height: 100px;" pattern="^[\w\d./*]{1,},[^\S][\w\d./* ]{1,},[^\S][\w\d./* ]{1,}$"  minlength="5" required></textarea> -->
                        <textarea class="form-control" placeholder="description" name="description" style="height: 100px;" required><?php echo $description?></textarea>
                        <label for="floatingTextarea">Description</label>
                        <div class="invalid-feedback">Please enter description!</div>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary mb-2" name="update"><i class='ri-add-circle-fill'></i> Update</button>
                      <a href="adminManagesLeaves.php"><button type="button" class="btn btn-secondary mb-2"><i class='bx bxs-left-arrow-alt'></i> Back</button></a>
                    </div>
                </form><!-- End Add Employee Form -->

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