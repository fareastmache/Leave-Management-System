<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>View Application - Admin</title>
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

    if($_SESSION["sID"] === session_id()) { 
      $applicationID = $_GET["applicationID"];
      $email = $_SESSION["email"];
      $contact = "";
      $profilePicture = "";
      $department = "";
      $position = "";

      $query1 = "SELECT * FROM admin WHERE email = '$email'";
      $result1 = mysqli_query($connect, $query1) or die (mysqli_error($connect));
  
      if(mysqli_num_rows($result1) === 1) {
        foreach($result1 as $row1) {
          $contact = $row1["contact"];
          $profilePicture = $row1["profilePicture"];
          $department = $row1["department"];
          $position = $row1["position"];
        }
      }

      $query2 = "SELECT * FROM application WHERE applicationID = '$applicationID'";
      $result2 = mysqli_query($connect, $query2) or die (mysqli_error($connect));

      $leaveType = "";
      $startDate = "";
      $endDate = "";
      $totalDays = "";
      $appliedDate = "";
      $reason = "";
      $document = "";
      $applicationStatus = "";
      $adminRemark = "";
      $employeeID = "";

      if(mysqli_num_rows($result2) === 1) {
        foreach($result2 as $row2) {
          $leaveType = $row2["leaveType"];
          $startDate = $row2["startDate"];
          $endDate = $row2["endDate"];
          $totalDays = $row2["totalDays"];
          $appliedDate = $row2["appliedDate"];
          $reason = $row2["reason"];
          $document = $row2["document"];
          $applicationStatus = $row2["status"];
          $adminRemark = $row2["adminRemark"];
          $employeeID = $row2["employeeID"];
        }
      }

      $query3 = "SELECT * FROM employee WHERE employeeID = '$employeeID'";
      $result3 = mysqli_query($connect, $query3) or die (mysqli_error($connect));

      $employeeEmail = "";
      $employeeName = "";
      $employeeProfilePicture = "";
      $employeeContact = "";
      $employeeEmergencyContact = "";
      $employeeAddress = "";
      $employeeZip = "";
      $employeeCity = "";
      $employeeState = "";
      $employeeDepartment = "";
      $employeePosition = "";
      $employeeRegisteredDate = "";
      $employeeStatus = "";

      if(mysqli_num_rows($result3) === 1) {
        foreach($result3 as $row3) {
          $employeeEmail = $row3["email"];
          $employeeName = $row3["name"];
          $employeeProfilePicture = $row3["profilePicture"];
          $employeeContact = $row3["contact"];
          $employeeEmergencyContact = $row3["emergencyContact"];
          $employeeAddress = $row3["address"];
          $employeeZip = $row3["zip"];
          $employeeCity = $row3["city"];
          $employeeState = $row3["state"];
          $employeeDepartment = $row3["department"];
          $employeePosition = $row3["position"];
          $employeeRegisteredDate = $row3["registeredDate"];
          $employeeStatus = $row3["status"];
        }
      }

      if(isset($_POST["submit"])) {
        $action = $_POST["action"];
        $leaveType = $_POST["leaveType"];
        $applicationID = $_POST["aID"];
        $employeeID = $_POST["eID"];
        $totalDays = $_POST["totalDays"];
        $adminRemark = $_POST["adminRemark"]; 

        if($adminRemark === "") {
          $query5 = "UPDATE application SET status = '$action', adminRemark = 'N/A' WHERE applicationID = '$applicationID' AND employeeID = '$employeeID'";
          mysqli_query($connect, $query5) or die (mysqli_error($connect)); 
        }
        else {
          $query5 = "UPDATE application SET status = '$action', adminRemark = '$adminRemark' WHERE applicationID = '$applicationID' AND employeeID = '$employeeID'";
          mysqli_query($connect, $query5) or die (mysqli_error($connect)); 
        }

        if($action === "Approved") {
          $query6= "SELECT * FROM balance WHERE employeeID = '$employeeID' AND leaveType = '$leaveType' limit 1";
          $result6 = mysqli_query($connect, $query6) or die (mysqli_error());

          foreach($result6 as $row6) {
            $daysAvailable = $row6["daysAvailable"];
            $daysTaken = $row6['daysTaken'];
          }

          $totalDays = $totalDays + $daysTaken;
          if($totalDays < $daysAvailable) {
            $query7 = "UPDATE balance SET daysTaken = '$totalDays' WHERE employeeID = '$employeeID' AND leaveType = '$leaveType'";
            mysqli_query($connect, $query7);
          }
          else if($totalDays >= $daysAvailable) {
            $totalDays = $daysAvailable;
            $query8 = "UPDATE balance SET daysTaken = '$totalDays' WHERE employeeID = '$employeeID' AND leaveType = '$leaveType'";
            mysqli_query($connect, $query8);
          }

          $query9 = "UPDATE employee SET status = 'Inactive' WHERE employeeID = '$employeeID'";
          mysqli_query($connect, $query9);
        }

        try {
          // Create the Transport
          $transport = (new Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
            ->setUsername('fypise2022@gmail.com')
            ->setPassword('fyp270222#')
          ;
      
          // Create the Mailer using your created Transport
          $mailer = new Swift_Mailer($transport);
      
          // Create a message
          $body = 'Hello '.$employeeName.', 
                  <p>
                    Please find the details of the leave appliction below: <br>
                    <b>Leave Type: </b>'.$leaveType.' <br>
                    <b>Start Date: </b>'.$startDate.' <br>
                    <b>End Date: </b>'.$endDate.' <br>
                    <b>Total Days: </b>'.$totalDays.' <br>
                    <b>Applied Date: </b>'.$appliedDate.'
                  </p>
                  <p>
                    The leave application you have applied recently has been <b>'.$action.'<b>
                  </p>';
      
          $message = (new Swift_Message('Leave Management System Leave Application'))
            ->setFrom(['fypise2022@gmail.com' => 'Reitech Solution Human Resource'])
            ->setTo([$employeeEmail])
            ->setBody($body)
            ->setContentType('text/html')
          ;
      
          // Send the message
          $mailer->send($message); ?>

        <?php } 
        catch(Exception $e) {
            echo $e->getMessage();
        } ?>
        <script>
          Swal.fire({
            icon: 'success',
            title: 'Application Updated',
          }).then(function() {
              window.location.href = 'adminManagesApplications.php'
          });
        </script>
      <?php } 
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
      <h1>View Application</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="adminDashboard.php">Home</a></li>
          <li class="breadcrumb-item">Applications</li>
          <li class="breadcrumb-item"><a href="adminManagesEmployees.php">Manage Application</a></li>
          <li class="breadcrumb-item active">View Application</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section profile">
      <div class="row">
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
            <img src="data:image/*;charset=utf8;base64,<?php echo base64_encode($employeeProfilePicture); ?>" alt="Profile" class="rounded-circle">
            <h2><?php echo $employeeName?></h2>
            <h3><?php echo $employeeDepartment?></h3>
            <h3><?php echo $employeePosition?></h3>
          </div>
        </div>
      </div>
        <div class="col-xl-8">
          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Application Details</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Employee Profile</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Leave Balance</button>
                </li>
              </ul>
              <div class="tab-content pt-2">
                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <!-- Application Details Form -->
                  <form class="row g-3 needs-validation" method="post" novalidate>
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="leaveType" placeholder="Leave Type" value="<?php echo $leaveType ?>" readonly>
                        <input type="hidden" name="aID" value="<?php echo $applicationID ?>">
                        <input type="hidden" name="eID" value="<?php echo $employeeID ?>">
                        <label for="floatingName">Leave Type</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="date" class="form-control" name="startDate" placeholder="Start Date" value="<?php echo $startDate ?>" readonly>
                        <label for="floatingStartDate">Start Date</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="date" class="form-control" name="endDate" placeholder="End Date" value="<?php echo $endDate ?>" readonly>
                        <label for="floatingEndDate">End Date</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="date" class="form-control" name="appliedDate" placeholder="Applied Date" value="<?php echo $appliedDate ?>" readonly>
                        <label for="floatingAppliedDate">Applied Date</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="totalDays" placeholder="Total Days" value="<?php echo $totalDays ?>" readOnly>
                        <label for="floatingTotalDays">Total Days</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <textarea class="form-control" placeholder="Reason" name="reason" style="height: 100px;" readonly><?php echo $reason ?></textarea>
                        <label for="floatingReason">Reason</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="applicationStatus" placeholder="Status" value="<?php echo $applicationStatus ?>" readOnly>
                        <label for="floatingApplicationStatus">Status</label>
                      </div>
                    </div> 
                    <?php
                        if($applicationStatus === "Pending") { 
                    ?>
                          <div class="col-md-6">
                            <div class="form-floating">
                              <select class="form-select" name="action" aria-label="Action" required>
                                <option value="Approved">Approve</option>
                                <option value="Rejected">Reject</option>
                              </select>
                              <label for="floatingDepartment">Action</label>
                            </div>
                          </div>
                          <div class="card" style="width: 18rem;">
                            <img class="card-img-top" src="data:image/*;charset=utf8;base64,<?php echo base64_encode($document); ?>" onclick="image(this)" id="img1">
                            <div class="card-body">
                              <h5 class="card-title">Document</h5>
                            </div>
                          </div>
                          <div id="myModal" class="modal">
                            <span class="close">&times;</span>
                            <img class="modal-content" id="img01">
                            <div id="caption"></div>
                          </div>
                          <div class="col-12">
                            <div class="form-floating has-validation">
                              <textarea class="form-control" placeholder="Remark" name="adminRemark" style="height: 100px;" required></textarea>
                              <label for="floatingTextarea">Admin Remark</label>
                            </div>
                          </div>
                          <div class="text-center">
                            <button type="submit" class="btn btn-primary" name="submit"><i class='bx bxs-edit'></i> Submit</button>
                            <a href="adminManagesApplications.php"><button type="button" class="btn btn-secondary"><i class='bx bxs-left-arrow-alt'></i> Back</button></a>
                          </div>
                    <?php 
                        }
                        else { 
                      ?>
                          <div class="col-12">
                            <div class="form-floating">
                              <textarea class="form-control" placeholder="Remark" name="adminRemark" style="height: 100px;" readonly><?php echo $adminRemark ?></textarea>
                              <label for="floatingTextarea">Admin Remark</label>
                            </div>
                          </div>
                          <div class="text-center">
                            <a href="adminManagesApplications.php"><button type="button" class="btn btn-secondary"><i class='bx bxs-left-arrow-alt'></i> Back</button></a>
                          </div>
                      <?php 
                        }
                    ?>
                  </form>
                </div><!-- Application Details Form -->
                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                  <!-- Employee Profile Form -->
                  <form class="row g-3">
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo $employeeName ?>" readonly>
                        <label for="floatingName">Name</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $employeeEmail ?>" readonly>
                        <label for="floatingEmail">Email</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="email" class="form-control" name="employeeID" placeholder="employeeID" value="<?php echo $employeeID ?>" readonly>
                        <label for="floatingID">ID</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="tel" class="form-control" name="contact" placeholder="Contact" value="<?php echo $employeeContact ?>" readonly>
                        <label for="floatingContact">Contact</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="tel" class="form-control" name="emergencyContact" placeholder="Emergency Contact" value="<?php echo $employeeEmergencyContact ?>" readonly>
                        <label for="floatingEmergencyContact">Emergency Contact</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="tel" class="form-control" name="employeeStatu" placeholder="Status" value="<?php echo $employeeStatus ?>" readonly>
                        <label for="floatingEmergencyContact">Status</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="date" class="form-control" name="registeredDate" placeholder="Register Date" value="<?php echo $employeeRegisteredDate ?>" readonly>
                        <label for="floatingContact">Registered Date</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="department" placeholder="Department" value="<?php echo $employeeDepartment ?>" readonly>
                        <label for="floatingContact">Department</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="position" placeholder="Position" value="<?php echo $employeePosition ?>" readonly>
                        <label for="floatingPosition">Position</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <textarea class="form-control" placeholder="Address" name="address" style="height: 100px;" readonly><?php echo $employeeAddress ?></textarea>
                        <label for="floatingTextarea">Address</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="city" placeholder="City" value="<?php echo $employeeCity ?>" readonly>
                        <label for="floatingCity">City</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="state" placeholder="State" value="<?php echo $employeeState ?>" readonly>
                        <label for="floatingCity">State</label>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-floating">
                        <input type="text" class="form-control" name="zip" placeholder="Zip" value="<?php echo $employeeZip ?>" readonly>
                        <label for="floatingZip">Zip</label>
                      </div>
                    </div>
                  </form><!-- End Profile Edit Form -->
                </div>
                <div class="tab-pane fade pt-3" id="profile-settings">
                  <!-- View Leave Balance -->
                  <div class="card-body">
                    <table class="table table-borderless datatable">
                      <thead>
                        <tr>
                          <th scope="col">Leave Type</th>
                          <th scope="col">Days Available</th>
                          <th scope="col">Days Taken</th>
                          <th scope="col">Days Remaining</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                        $query4 = "SELECT * FROM balance WHERE employeeID = '$employeeID'";
                        $result4 = mysqli_query($connect, $query4) or die (mysqli_error($connect));

                        if(mysqli_num_rows($result4) > 0) {
                          foreach($result4 as $row4) {
                            $leaveType = $row4["leaveType"];
                            $daysAvailable = $row4["daysAvailable"];
                            $daysTaken = $row4["daysTaken"];
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
                  </div><!-- End View Leave Balance -->
                </div>
              </div><!-- End Bordered Tabs -->
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
    let modal = document.getElementById("myModal");
    let modalImg = document.getElementById("img01");
    let captionText = document.getElementById("caption");
    
    function image(img) {
      modal.style.display = "block";
      modalImg.src = img.src;
      captionText.innerHTML = img.alt;
    }

    let span = document.getElementsByClassName("close")[0];

    span.onclick = function() { 
      modal.style.display = "none";
    }
  </script>
</body>

</html>