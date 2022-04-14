<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Profile - Employee</title>
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
      $ID = $_SESSION["userID"];
      $email = "";
      $name = "";
      $profilePicture = "";
      $contact = "";
      $emergencyContact = "";
      $address = "";
      $zip = "";
      $city = "";
      $state = "";
      $department = "";
      $position = "";
      $status = "";

      $query1 = "SELECT * FROM employee WHERE employeeID = '$ID'";
      $result1 = mysqli_query($connect, $query1) or die (mysqli_error($connect));

      if(mysqli_num_rows($result1) === 1) {
        foreach($result1 as $row1) {
          $email = $row1["email"];
          $name = $row1["name"];
          $profilePicture = $row1["profilePicture"];
          $contact = $row1["contact"];
          $emergencyContact = $row1["emergencyContact"];
          $address = $row1["address"];
          $zip = $row1["zip"];
          $city = $row1["city"];
          $state = $row1["state"];
          $department = $row1["department"];
          $position = $row1["position"];
          $status = $row1["status"];
        }
      }

      if(isset($_POST["update"])) { 
        $updateEmail = $_POST["email"];
        $updateName = $_POST["name"];
        $updateContact = $_POST["contact"];
        $updateEmergencyContact = $_POST["emergencyContact"];
        $updateAddress = $_POST["address"];
        $updateZip = $_POST["zip"];
        $updateCity = $_POST["city"];
        $updateState = $_POST["state"];
        
        $fileName = basename($_FILES["profilePicture"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowTypes = array("jpg", "jpeg", "png");
        $image = $_FILES['profilePicture']['tmp_name']; 
        $imgContent = addslashes(file_get_contents($image)); 

        if(empty($imgContent)) {
          $query2 = "UPDATE employee SET email = '$updateEmail', name = '$updateName', contact = '$updateContact', emergencyContact = '$updateEmergencyContact', address = '$updateAddress', zip = '$updateZip', city = '$updateCity', state = '$updateState' WHERE employeeID = '$ID'";
          mysqli_query($connect, $query2) or die (mysqli_error($connect));

          ?> 
            <script>
              Swal.fire({
                icon: 'success',
                title: 'Profile Updated',
              }).then(function() {
                window.location.href = 'employeeUpdatesProfile.php';
              });
            </script>
          <?php 
        }
        else {  
          if(in_array($fileType, $allowTypes)) {
            $query2 = "UPDATE employee SET email = '$updateEmail', name = '$updateName', profilePicture = '$imgContent', contact = '$updateContact', emergencyContact = '$updateEmergencyContact', address = '$updateAddress', zip = '$updateZip', city = '$updateCity', state = '$updateState' WHERE employeeID = '$ID'";
            mysqli_query($connect, $query2) or die (mysqli_error($connect));

            ?> 
              <script>
                Swal.fire({
                  icon: 'success',
                  title: 'Profile Updated',
                }).then(function() {
                  window.location.href = 'employeeUpdatesProfile.php';
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

      if(isset($_POST["change"])) { 
        $newPassword = $_POST["newPassword"];
        $renewPassword = $_POST["re-enterNewPassword"];
        $dbPassword = "";

        $query3 = "SELECT * FROM employee WHERE employeeID = '$ID'";
        $result3 = mysqli_query($connect, $query3) or die (mysqli_error($connect)); 

        if(mysqli_num_rows($result3) === 1) {
          foreach($result3 as $row3) {
            $dbPassword = $row3["password"];
          }
        }

        if(strcmp($newPassword, $renewPassword) === 0) {
          if(strcmp($newPassword, $dbPassword) === 0) { ?>
            <script>
              Swal.fire({
                icon: 'error',
                title: 'Warning',
                text: 'New password cant be same with previous password',
              })
            </script>
          <?php }
          else {
            $query4 = "UPDATE employee SET password = '$newPassword' WHERE employeeID = '$ID'";
            mysqli_query($connect, $query4) or die (mysqli_error($connect)); ?>
             
            <script>
              swal.fire({
                icon: 'success',
                title: 'Password Reset',
              }).then(function() {
                window.location.href = 'employeeUpdatesProfile.php';
              });
            </script>
          <?php }
        }
        else { 
          ?>
          <script>
            Swal.fire({
              icon: 'error',
              title: 'Warning',
              text: 'Password entered is not match',
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
        <a class="nav-link collapsed" href="employeeDashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
      <li class="nav-item">
        <a class="nav-link " href="employeeUpdatesProfile.php">
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
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="employeeDashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">
          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($profilePicture); ?>" alt="Profile" class="rounded-circle">
              <h2><?php echo $name?></h2>
              <h3><?php echo $department?></h3>
              <h3><?php echo $position?></h3>
            </div>
          </div>
        </div>
        <div class="col-xl-8">
          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Update Profile</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>
              </ul>
              <div class="tab-content pt-2">
                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <!-- Profile Edit Form -->
                  <form class="row g-3 needs-validation" method="post" enctype="multipart/form-data" novalidate onsubmit="checkValidation1()">
                    <div class="col-md-12">
                      <div class="form-floating has-validation">
                        <input type="text" class="form-control" name="name" placeholder="Name" id="name" value="<?php echo $name ?>" pattern="[a-zA-Z ]+" required>
                        <label for="floatingName">Name</label>
                        <div class="invalid-feedback"><p id="n"></p></div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-floating has-validation">
                        <input type="email" class="form-control" name="email" placeholder="Email" id="email" value="<?php echo $email ?>" pattern="[a-z0-9._%+-]+@gmail.com" required>
                        <label for="floatingEmail">Email</label>
                        <div class="invalid-feedback"><p id="e"></p></div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <input type="tel" class="form-control" name="contact" placeholder="Contact" id="contact" value="<?php echo $contact ?>"  pattern="[0-9]{3}-[0-9]{7}" minlength="11" maxlength="11" required>
                        <label for="floatingContact">Contact</label>
                        <div class="invalid-feedback"><p id="c"></p></div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <input type="tel" class="form-control" name="emergencyContact" placeholder="Emergency Contact" id="emergencyContact" value="<?php echo $emergencyContact ?>" pattern="[0-9]{3}-[0-9]{7}" minlength="11" maxlength="11" required>
                        <label for="floatingEmergencyContact">Emergency Contact</label>
                        <div class="invalid-feedback"><p id="ec"></p></div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-floating has-validation">
                        <input type="tel" class="form-control" name="status" placeholder="Status" value="<?php echo $status ?>" readonly>
                        <label for="floatingStatus">Status</label>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <label for="profilePicture" class="col-sm-2 col-form-label">Upload Profile Picture</label>
                      <div class="col-sm-10">
                        <input class="form-control" type="file" name="profilePicture" accept="image/*">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating has-validation">
                        <textarea class="form-control" name="address" placeholder="Address" id="address" style="height: 100px;" pattern="^[\w\d./*]{1,},[^\S][\w\d./* ]{1,},[^\S][\w\d./* ]{1,}$"  minlength="5" required><?php echo $address ?></textarea>
                        <label for="floatingTextarea">Address</label>
                        <div class="invalid-feedback"><p id="ad"></p></div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="col-md-12">
                        <div class="form-floating has-validation">
                          <input type="text" class="form-control" name="city" placeholder="City" id="city" value="<?php echo $city ?>" pattern="[a-zA-Z ]+" required>
                          <label for="floatingCity">City</label>
                          <div class="invalid-feedback"><p id="ct"></p></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3 has-validation">
                        <select class="form-select" name="state" aria-label="State" required>
                          <option value="<?php echo $state ?>"><?php echo $state ?></option>
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
                        <input type="text" class="form-control" name="zip" placeholder="Zip" id="zip" value="<?php echo $zip ?>" pattern="^[0-9]{1,}$" minlength="5" maxlength="5" required>
                        <label for="floatingZip">Zip</label>
                        <div class="invalid-feedback"><p id="z"></p></div>
                      </div>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" name="update"><i class='bx bxs-edit'></i> Update</button>
                      <a href="employeeDashboard.php"><button type="button" class="btn btn-secondary"><i class='bx bxs-left-arrow-alt'></i> Back</button></a>
                    </div>
                  </form><!-- End Profile Edit Form -->
                </div>
                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form class="row g-3 needs-validation" method="post" novalidate onsubmit="checkValidation2()">
                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="password" name="newPassword" class="form-control" id="newPassword" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{1,}$" minlength="8" required>
                        <div class="invalid-feedback"><p id="np"></p></div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="re-enterNewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="password" name="re-enterNewPassword" class="form-control" id="re-enterNewPassword" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{1,}$" minlength="8" required>
                        <div class="invalid-feedback">Please re-enter new password!</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <input type="checkbox" class="form-check-input" onclick="showPassword()">
                      <label class="form-check-label" for="rememberMe">Show password</label>
                    </div>  
                    <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="change"><i class='bx bxs-edit'></i> change</button>
                      <a href="employeeDashboard.php"><button type="button" class="btn btn-secondary"><i class='bx bxs-left-arrow-alt'></i> Back</button></a>
                    </div>
                  </form><!-- End Change Password Form -->
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

  <!-- Function -->
  <script>
    function checkValidation1() {
      var a = document.getElementById("name");
      var b = document.getElementById("email");
      var c = document.getElementById("contact");
      var d = document.getElementById("emergencyContact");
      var e = document.getElementById("address");
      var f = document.getElementById("city");
      var g = document.getElementById("zip");

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

      //Check Contact
      if(c.validity.valueMissing || d.validity.valueMissing) {
        document.getElementById("c").innerHTML = "Please enter contact!";
        document.getElementById("ec").innerHTML = "Please enter emergency contact!";
      }
      else if(c.validity.patternMismatch || d.validity.patternMismatch) {
        document.getElementById("c").innerHTML = "Please use the correct format! Example: xxx-xxxxxxx";
        document.getElementById("ec").innerHTML = "Please use the correct format! Example: xxx-xxxxxxx";
      }
      else if(c.validity.tooLong || d.validity.tooLong) {
        document.getElementById("c").innerHTML = "Phone number entered exceeds 11 digits!";
        document.getElementById("ec").innerHTML = "Phone number entered exceeds 11 digits!";
      }
      else if(c.validity.tooShort || d.validity.tooShort ) {
        document.getElementById("c").innerHTML = "Phone number entered subceeds 11 digits!";
        document.getElementById("ec").innerHTML = "Phone number entered subceeds 11 digits!";
      }

      //Check Address
      if(e.validity.valueMissing) {
        document.getElementById("ad").innerHTML = "Please enter address!";
      }
      else if(e.validity.patternMismatch){
        document.getElementById("ad").innerHTML = "Please use the correct format! Example: No.1, Street, Area";
      }

      //Check City
      if(f.validity.valueMissing) {
        document.getElementById("ct").innerHTML = "Please enter city!";
      }
      else if(f.validity.patternMismatch){
        document.getElementById("ct").innerHTML = "City must be letters only!";
      }

      //Check Zip
      if(g.validity.valueMissing) {
        document.getElementById("z").innerHTML = "Please enter zip!";
      }
      else if(g.validity.patternMismatch) {
        document.getElementById("z").innerHTML = "Zip must be digit only";
      }
      else if(g.validity.tooLong) {
        document.getElementById("z").innerHTML = "Zip entered exceeds 5 digits!";
      }
      else if(g.validity.tooShort) {
        document.getElementById("z").innerHTML = "Zip entered subceeds 5 digits!";
      }
    }  

    function checkValidation2() {
      var a = document.getElementById("newPassword");

      if(a.validity.valueMissing) {
        document.getElementById("np").innerHTML = "Please enter new password!";
      }
      else if(a.validity.patternMismatch){
        document.getElementById("np").innerHTML = "Password must contain at least a digit, a lowercase letter, an uppercase letter and a symbol!";
      }
      else if(a.validity.tooShort){
        document.getElementById("np").innerHTML = "Password must at least 8 characters!";
      }
    }

    function showPassword() {
      var a = document.getElementById("newPassword");
      var b = document.getElementById("re-enterNewPassword");

      if ((a.type && b.type) === "password") {
        a.type = "text";
        b.type = "text";
      } 
      else {  
        a.type = "password";
        b.type = "password";
      }
    }
  </script>
</body>

</html>