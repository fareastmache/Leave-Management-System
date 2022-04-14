<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Edit Employee - Admin</title>
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
      $employeeID = $_GET["employeeID"];
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
      
      $query2 = "SELECT * FROM employee WHERE employeeID = '$employeeID'";
      $result2 = mysqli_query($connect, $query2) or die (mysqli_error($connect));

      $employeeEmail = "";
      $EmployeePassword = "";
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

      if(mysqli_num_rows($result2) === 1) {
        foreach($result2 as $row2) {
          $employeeEmail = $row2["email"];
          $EmployeePassword = $row2["password"];
          $employeeName = $row2["name"];
          $employeeProfilePicture = $row2["profilePicture"];
          $employeeContact = $row2["contact"];
          $employeeEmergencyContact = $row2["emergencyContact"];
          $employeeAddress = $row2["address"];
          $employeeZip = $row2["zip"];
          $employeeCity = $row2["city"];
          $employeeState = $row2["state"];
          $employeeDepartment = $row2["department"];
          $employeePosition = $row2["position"];
          $employeeRegisteredDate = $row2["registeredDate"];
          $employeeStatus = $row2["status"];
        }
      }

      if(isset($_POST["update"])) { 
        $employeeEmail = $_POST["email"];
        $employeePassword = $_POST["password"];
        $employeeName = $_POST["name"];
        $employeeContact = $_POST["contact"];
        $employeeEmergencyContact = $_POST["emergencyContact"];
        $employeeAddress = $_POST["address"];
        $employeeZip = $_POST["zip"];
        $employeeCity = $_POST["city"];
        $employeeState = $_POST["state"];
        $employeeDepartment = $_POST["department"];
        $employeePosition = $_POST["position"];
        $employeeRegisteredDate = $_POST["registeredDate"];
        $employeeStatus = $_POST["status"];

        $fileName = basename($_FILES["profilePicture"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowTypes = array("jpg", "jpeg", "png");
        $image = $_FILES['profilePicture']['tmp_name']; 
        $imgContent = addslashes(file_get_contents($image)); 

        if(empty($imgContent)) {
          $query3 = "UPDATE employee SET email = '$employeeEmail', password = '$employeePassword', name = '$employeeName', contact = '$employeeContact', emergencyContact = '$employeeEmergencyContact', address = '$employeeAddress', zip = '$employeeZip', city = '$employeeCity', state = '$employeeState', department = '$employeeDepartment', position = '$employeePosition', registeredDate = '$employeeRegisteredDate', status = '$employeeStatus' WHERE employeeID = '$employeeID'";
          mysqli_query($connect, $query3) or die (mysqli_error($connect));

          ?> 
            <script>
              Swal.fire({
                icon: 'success',
                title: 'Employee Updated',
              }).then(function() {
                  window.location.href = 'adminManagesEmployees.php'
              });
            </script>
          <?php 
        }
        else {
          if(in_array($fileType, $allowTypes)){   
            $query3 = "UPDATE employee SET email = '$employeeEmail', password = '$employeePassword', name = '$employeeName', profilePicture = '$imgContent', contact = '$employeeContact', emergencyContact = '$employeeEmergencyContact', address = '$employeeAddress', zip = '$employeeZip', city = '$employeeCity', state = '$employeeState', department = '$employeeDepartment', position = '$employeePosition', registeredDate = '$employeeRegisteredDate', status = '$employeeStatus' WHERE employeeID = '$employeeID'";
            mysqli_query($connect, $query3) or die (mysqli_error($connect));

            ?> 
              <script>
                Swal.fire({
                  icon: 'success',
                  title: 'Employee Updated',
                }).then(function() {
                    window.location.href = 'adminManagesEmployees.php'
                });
              </script>
            <?php 
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
      <h1>Edit Employee</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="adminDashboard.php">Home</a></li>
          <li class="breadcrumb-item">Employees</li>
          <li class="breadcrumb-item"><a href="adminManagesEmployees.php">Manage Employee</a></li>
          <li class="breadcrumb-item active">Edit Employee</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">
          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($employeeProfilePicture); ?>" alt="Profile" class="rounded-circle">
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
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Employee Profile</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Leave Balance</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Leave History</button>
                </li>
              </ul>
              <div class="tab-content pt-2">
                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                  <!-- Profile Edit Form -->
                  <form class="row g-3 needs-validation" method="post" enctype="multipart/form-data" novalidate onsubmit="checkValidation()">
                    <div class="col-md-12">
                      <div class="form-floating has-validation">
                        <input type="text" class="form-control" name="name" placeholder="Name" id="name" value="<?php echo $employeeName?>" pattern="[a-zA-Z ]+" required>
                        <label for="floatingName">Name</label>
                        <div class="invalid-feedback"><p id="n"></p></div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-floating has-validation">
                        <input type="email" class="form-control" name="email" placeholder="Email" id="email" value="<?php echo $employeeEmail?>" pattern="[a-z0-9._%+-]+@gmail.com" required>
                        <label for="floatingEmail">Email</label>
                        <div class="invalid-feedback"><p id="e"></p></div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating">
                        <input type="email" class="form-control" name="ID" placeholder="ID" value="<?php echo $employeeID?>" readonly>
                        <label for="floatingEmail">ID</label>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <input type="text" class="form-control" name="password" placeholder="Password" id="password" value="<?php echo $EmployeePassword?>" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{1,}$" minlength="8" required>
                        <label for="floatingPassword">Password</label>
                        <div class="invalid-feedback"><p id="np"></p></div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <input type="tel" class="form-control" name="contact" placeholder="Contact" id="contact" value="<?php echo $employeeContact?>" pattern="[0-9]{3}-[0-9]{7}" minlength="11" maxlength="11" required>
                        <label for="floatingContact">Contact</label>
                        <div class="invalid-feedback"><p id="c"></p></div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <input type="tel" class="form-control" name="emergencyContact" placeholder="Emergency Contact" value="<?php echo $employeeEmergencyContact?>" pattern="[0-9]{3}-[0-9]{7}" minlength="11" maxlength="11" required>
                        <label for="floatingEmergencyContact">Emergency Contact</label>
                        <div class="invalid-feedback"><p id="ec"></p></div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <select class="form-select" name="status" aria-label="Status" required>
                          <option value="<?php echo $employeeStatus?>"><?php echo $employeeStatus?></option>
                          <option value="Active">Active</option>
                          <option value="Inactive">Inactive</option>
                        </select>
                        <label for="floatingContact">Status</label>
                        <div class="invalid-feedback">Please select status!</div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <input type="date" class="form-control" name="registeredDate" placeholder="Register Date" value="<?php echo $employeeRegisteredDate?>" required>
                        <label for="floatingContact">Register Date</label>
                        <div class="invalid-feedback">Please select register date!</div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <label for="inputNumber" class="col-sm-2 col-form-label">Profile Picture Upload</label>
                      <div class="col-sm-10">
                        <input class="form-control" type="file" name="profilePicture" accept="image/*">
                      </div>
                    </div>
                    
                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <select class="form-select" name="department" aria-label="Department" required>
                          <option value="<?php echo $employeeDepartment?>"><?php echo $employeeDepartment?></option>
                          <option value="Accounting">Accounting</option>
                          <option value="Information Technology">Information Technology</option>
                          <option value="Finance">Finance</option>
                          <option value="Marketing">Marketing</option>  
                        </select>
                        <label for="floatingDepartment">Department</label>
                        <div class="invalid-feedback">Please select department!</div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <input type="text" class="form-control" name="position" placeholder="Position" id="position" value="<?php echo $employeePosition?>" pattern="[a-zA-Z ]+" required>
                        <label for="floatingPosition">Position</label>
                        <div class="invalid-feedback"><p id="p"></p></div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="form-floating has-validation">
                        <textarea class="form-control" placeholder="Address" name="address" id="address" style="height: 100px;" pattern="^[\w\d./*]{1,},[^\S][\w\d./* ]{1,},[^\S][\w\d./* ]{1,}$"  minlength="5" required><?php echo $employeeAddress?></textarea>
                        <label for="floatingTextarea">Address</label>
                        <div class="invalid-feedback"><p id="ad"></p></div> 
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="col-md-12">
                        <div class="form-floating has-validation">
                          <input type="text" class="form-control" name="city" placeholder="City" id="city" value="<?php echo $employeeCity?>" pattern="^\S\D*$" minlength="4" required>
                          <label for="floatingCity">City</label>
                          <div class="invalid-feedback"><p id="ct"></p></div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-floating mb-3 has-validation">
                        <select class="form-select" name="state" aria-label="State" required>
                          <option value="<?php echo $employeeState?>"><?php echo $employeeState?></option>
                          <option value="Johor">Johor</option>
                          <option value="Kedah">Kedah</option>
                          <option value="Kelantan">Kelantan</option>
                          <option value="Melaka">Melaka</option>
                          <option value="Negeri Sembilan">Negeri Sembilan</option>
                          <option value="Pahang">Pahang</option>
                          <option value="Penang">Penang</option>
                          <option value="Perak">Perak</option>
                          <option value="Perlis">Perlis</option>
                          <option value="Selangor">Selangor</option>
                          <option value="Terengganu">Terengganu</option>
                          <option value="Sabah">Sabah</option>
                          <option value="Sarawak">Sarawak</option>
                          <option value="Kuala Lumpur">Kuala Lumpur</option>
                          <option value="Labuan">Labuan</option>
                          <option value="Putrajaya">Putrajaya</option>
                        </select>
                        <label for="floatingSelect">State</label>
                        <div class="invalid-feedback">Please select state!</div>
                      </div>
                    </div>

                    <div class="col-md-2">
                      <div class="form-floating has-validation">
                        <input type="text" class="form-control" name="zip" placeholder="Zip" id="zip" value="<?php echo $employeeZip?>" pattern="^[0-9]{0,}$" minlength="5" maxlength="5" required>
                        <label for="floatingZip">Zip</label>
                        <div class="invalid-feedback"><p id="z"></p></div>
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" name="update"><i class='bx bxs-edit'></i> Update</button>
                      <a href="adminManagesEmployees.php"><button type="button" class="btn btn-secondary"><i class='bx bxs-left-arrow-alt'></i> Back</button></a>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
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
                <div class="tab-pane fade pt-3" id="profile-settings">
                  <!-- View Leave History -->
                  <div class="card-body">
                    <table class="table table-borderless datatable">
                      <thead>
                        <tr>
                          <th scope="col">Leave Type</th>
                          <th scope="col">Start Date</th>
                          <th scope="col">End Date</th>
                          <th scope="col">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                        $query5 = "SELECT * FROM application WHERE employeeID = '$employeeID'";
                        $result5 = mysqli_query($connect, $query5) or die (mysqli_error($connect));

                        if(mysqli_num_rows($result5) > 0) {
                          foreach($result5 as $row5) {
                            $leaveType = $row5["leaveType"];
                            $startDate = $row5["startDate"];
                            $endDate = $row5["endDate"];
                            $status = $row5["status"];

                            echo "<tr>";
                              echo "<td>".$leaveType."</th>";
                              echo "<td>".$startDate."</td>";
                              echo "<td>".$endDate."</td>";
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
                  </div><!-- End View Leave History -->
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
    function checkValidation() {
      var a = document.getElementById("name");
      var b = document.getElementById("email");
      var c = document.getElementById("password");
      var d = document.getElementById("contact");
      var e = document.getElementById("emergencyContact");
      var f = document.getElementById("position");
      var g = document.getElementById("address");
      var h = document.getElementById("city");
      var i = document.getElementById("zip");

      //Check Name
      if(a.validity.valueMissing) {
        document.getElementById("n").innerHTML = "Please enter name!";
      }
      else if(a.validity.patternMismatch){
        document.getElementById("n").innerHTML = "Name must be letters only!";
      }

      //Check Email
      if(b.validity.valueMissing) {
        document.getElementById("e").innerHTML = "Please enter email!";
      }
      else if(b.validity.patternMismatch){
        document.getElementById("e").innerHTML = "Please use the correct format! Example: xxxx@gmail.com";
      }

      //Check Password
      if(c.validity.valueMissing) {
        document.getElementById("np").innerHTML = "Please enter password!";
      }
      else if(c.validity.patternMismatch){
        document.getElementById("np").innerHTML = "Password must contain at least a digit, a lowercase letter, an uppercase letter and a symbol!";
      }
      else if(c.validity.tooShort){
        document.getElementById("np").innerHTML = "Password must at least 8 characters!";
      }

      //Check Contact
      if(d.validity.valueMissing || e.validity.valueMissing) {
        document.getElementById("c").innerHTML = "Please enter contact!";
        document.getElementById("ec").innerHTML = "Please enter emergency contact!";
      }
      else if(d.validity.patternMismatch || e.validity.patternMismatch) {
        document.getElementById("c").innerHTML = "Please use the correct format! Example: xxx-xxxxxxx";
        document.getElementById("ec").innerHTML = "Please use the correct format! Example: xxx-xxxxxxx";
      }
      else if(d.validity.tooLong || e.validity.tooLong) {
        document.getElementById("c").innerHTML = "Phone number entered exceeds 11 digits!";
        document.getElementById("ec").innerHTML = "Phone number entered exceeds 11 digits!";
      }
      else if(d.validity.tooShort || e.validity.tooShort ) {
        document.getElementById("c").innerHTML = "Phone number entered subceeds 11 digits!";
        document.getElementById("ec").innerHTML = "Phone number entered subceeds 11 digits!";
      }

      //Check Position
      if(f.validity.valueMissing) {
        document.getElementById("p").innerHTML = "Please enter position!";
      }
      else if(f.validity.patternMismatch){
        document.getElementById("p").innerHTML = "Position must be letters only!";
      }

      //Check Address
      if(g.validity.valueMissing) {
        document.getElementById("ad").innerHTML = "Please enter address!";
      }
      else if(g.validity.patternMismatch){
        document.getElementById("ad").innerHTML = "Please use the correct format! Example: No.1, Street, Area";
      }

      //Check City
      if(h.validity.valueMissing) {
        document.getElementById("ct").innerHTML = "Please enter city!";
      }
      else if(h.validity.patternMismatch){
        document.getElementById("ct").innerHTML = "City must be letters only!";
      }

      //Check Zip
      if(i.validity.valueMissing) {
        document.getElementById("z").innerHTML = "Please enter zip!";
      }
      else if(i.validity.patternMismatch) {
        document.getElementById("z").innerHTML = "Zip must be digit only";
      }
      else if(i.validity.tooLong) {
        document.getElementById("z").innerHTML = "Zip entered exceeds 5 digits!";
      }
      else if(i.validity.tooShort) {
        document.getElementById("z").innerHTML = "Zip entered subceeds 5 digits!";
      }
    }  
  </script>
</body>

</html>