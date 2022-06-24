<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Application - Employee</title>
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

    require_once "vendor/autoload.php";

    if(isset($_SESSION["sID"])) { 
      $ID = $_SESSION["userID"];
      $adminEmail = "fypise2022@gmail.com";
      $email = "";
      $password = "";
      $name = "";
      $profilePicture = "";
      $department = "";
      $position = "";
      $document = "";

      $query1 = "SELECT * FROM employee WHERE employeeID = '$ID'";
      $result1 = mysqli_query($connect, $query1) or die (mysqli_error($connect));

      if(mysqli_num_rows($result1) === 1) {
        foreach($result1 as $row1) {
          $email = $row1["email"];
          $password = $row1["password"];
          $name = $row1["name"];
          $profilePicture = $row1["profilePicture"];
          $department = $row1["department"];
          $position = $row1["position"];
        }
      }

      if(isset($_POST["submit"])) {
        $leaveType = $_POST["leaveType"];
        $startDate = $_POST["startDate"];
        $endDate = $_POST["endDate"];
        $totalDays = $_POST["daysRequested"];
        $reason = $_POST["reason"];
        $appliedDate = date("y-m-d");
        $status = "Pending";
        $adminRemark = "N/A";

        $fileName = basename($_FILES["document"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowTypes = array("jpg", "jpeg", "png");

        if(in_array($fileType, $allowTypes)) { 
          $image = $_FILES["document"]["tmp_name"]; 
          $imgContent = addslashes(file_get_contents($image));

          $query2 = "SELECT * FROM balance WHERE employeeID = '$ID' AND leaveType = '$leaveType'";
          $result2 = mysqli_query($connect, $query2) or die (mysqli_error($connect));

          foreach($result2 as $row2) {
              $daysAvailable = $row2["daysAvailable"];
              $daysTaken = $row2["daysTaken"];
          }

          $total = $daysTaken + $totalDays;
          $leaveBalance = $daysAvailable - $daysTaken;

          if($totalDays > $daysAvailable) {
            ?>
              <script>
                Swal.fire({
                  icon: 'error',
                title: 'Total days requested exceed maximum leave days',
                })
              </script>
            <?php
          }   
          else if($total > $daysAvailable) {
            ?>
              <script>
                Swal.fire({
                  icon: 'error',
                  title: 'Total days reached maximum leave days',
                })
              </script>
            <?php
          }
          else {
            $query3 = "SELECT startDate, endDate FROM application WHERE '$startDate' BETWEEN startDate AND endDate AND employeeID = '$ID'";
            $result3 = mysqli_query($connect, $query3) or die(mysqli_error($connect));

            $query4 = "SELECT startDate, endDate FROM application WHERE '$endDate' BETWEEN startDate AND endDate AND employeeID = '$ID'";
            $result4 = mysqli_query($connect, $query4) or die(mysqli_error($connect));

            if(mysqli_num_rows($result3) === 0 && mysqli_num_rows($result4) === 0) {
              $query5 = "INSERT INTO application VALUES ('', '$leaveType', '$startDate', '$endDate', '$totalDays', '$appliedDate', '$reason', '$imgContent', '$status', '$adminRemark', '$ID')";
              mysqli_query($connect, $query5) or die (mysqli_error($connect));

              try {
                // Create the Transport
                $transport = (new Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
                  ->setUsername($email)
                  ->setPassword($password)
                ;
            
                // Create the Mailer using your created Transport
                $mailer = new Swift_Mailer($transport);
            
                // Create a message
                $body = ' 
                        <p>
                          Please find the details of the leave appliction below: <br>
                          <b>Leave Type: </b>'.$leaveType.' <br>
                          <b>Start Date: </b>'.$startDate.' <br>
                          <b>End Date: </b>'.$endDate.' <br>
                          <b>Total Days: </b>'.$totalDays.' <br>
                          <b>Applied Date: </b>'.$appliedDate.'
                        </p>
                        <p>
                          <b>The leave application have been applied recently!</b>
                        </p>';
            
                $message = (new Swift_Message('Leave Application'))
                  ->setFrom([$email => $name])
                  ->setTo([$adminEmail])
                  ->setBody($body)
                  ->setContentType('text/html')
                ;
            
                // Send the message
                $mailer->send($message); ?>
      
              <?php 
              } 
              catch(Exception $e) {
                  echo $e->getMessage();
              } 
              ?>
                <script>
                  Swal.fire({
                    icon: 'success',
                    title: 'Leave Application Submitted',
                  }).then(function() {
                      window.location.href = 'employeeAppliesLeave.php'
                  });
                </script>
              <?php
            }
            else {
              ?>
                <script>
                  Swal.fire({
                    icon: 'error',
                    title: 'Date selected has been applied previously',
                  }).then(function() {
                      window.location.href = 'employeeAppliesLeave.php'
                  });
                </script>
              <?php
            }
          }
        }
        else {
          ?> 
            <script>
              Swal.fire({
                icon: 'error',
                title: 'Sorry, only JPG, JPEG, PNG files are allowed to upload',
              })
            </script>
          <?php 
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
    <h1>Apply Leave</h1>
    <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="employeeDashboard.php">Home</a></li>
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item active">Apply Leave</li>
        </ol>
    </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                <h5 class="card-title">Application Details</h5>
                    <!-- Apply Leave Form -->
                    <form class="row g-3 needs-validation" method="post"  enctype="multipart/form-data" novalidate>
                        <div class="col-md-12">
                          <div class="form-floating mb-3 has-validation">
                            <select class="form-select" name="leaveType" aria-label="leaveType" required>
                            <?php            
                              $query2 = "SELECT * FROM laeve";
                              $result2 = mysqli_query($connect, $query2) or die (mysqli_error($connect));
                              if(mysqli_num_rows($result2) > 0) {
                                foreach($result2 as $row2) {
                                  $leaveType = $row2["type"];
                                  echo "<option value='".$leaveType."'>".$leaveType."</option>";
                                }
                              }
                            ?>
                            </select>
                            <label for="floatingSelect">State</label>
                            <div class="invalid-feedback">Please select leave type!</div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating has-validation">
                            <input type="date" class="form-control" name="startDate" id="startDate" placeholder="Start Date" onchange="totalDays()" required>
                            <label for="floatingContact">Start Date</label>
                            <div class="invalid-feedback">Please select start date!</div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating has-validation">
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="End Date" onchange="totalDays()" required>
                            <label for="floatingContact">End Date</label>
                            <div class="invalid-feedback">Please select end date!</div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-floating has-validation">
                            <input type="text" class="form-control" name="daysRequested" id="daysRequested" placeholder="Total Days" readOnly>
                            <label for="floatingContact">Total Days</label>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <label for="document" class="col-sm-2 col-form-label">Upload Document</label>
                          <div class="col-sm-10">
                            <input type="file" name="document" class="form-control" accept="image/*" required>
                          </div>
                        </div>  
                        <div class="col-12">
                          <div class="form-floating has-validation">
                            <textarea class="form-control" placeholder="Reason(s)" name="reason" style="height: 100px;" required></textarea>
                            <label for="floatingTextarea">Reason</label>
                            <div class="invalid-feedback">Please enter reason!</div>
                          </div>
                        </div>
                        <div class="text-center">
                        <button type="submit" class="btn btn-primary mb-2" name="submit"><i class='ri-add-circle-fill'></i> Submit</button>
                        <a href="employeeDashboard.php"><button type="button" class="btn btn-secondary mb-2"><i class='bx bxs-left-arrow-alt'></i> Back</button></a>
                        </div>
                    </form><!-- End Apply Leave Form -->
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
      function totalDays() {
       let start_date = document.getElementById("startDate");	   
       let end_date = document.getElementById("endDate");	   		
       let start_day = new Date(start_date.value);
       let end_day = new Date(end_date.value);
       let milliseconds_per_day = 1000 * 60 * 60 * 24;
       let millis_between = end_day.getTime() - start_day.getTime();
       let days = millis_between / milliseconds_per_day;
       let total_days = (Math.floor(days)) + 1;
       let combined = total_days;
       let days_requested = document.getElementById("daysRequested");
       days_requested.value = combined;
        if(days_requested.value <= 0) {
            Swal.fire({
              icon: 'error',
              title: 'Invalid Date',
            }).then(function() {
                window.location.href = 'employeeAppliesLeave.php'
            });
        }
      }
    </script>
</body>

</html>