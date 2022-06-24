<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Add Employee - Admin</title>
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

      if(isset($_POST["add"])) {
        $a = "RS";
        $b = strval(random_int(1000, 9999));
        $employeeID = $a."".$b;
        $employeeEmail = $_POST["email"];
        $employeePassword = random_int(100000, 999999);
        $employeeName = $_POST["name"];
        $employeeContact = $_POST["contact"];
        $employeeEmergencyContact = $_POST["emergencyContact"];
        $employeeAddress = $_POST["address"];
        $employeeZip = $_POST["zip"];
        $employeeCity = $_POST["city"];
        $employeeState = $_POST["state"];
        $employeeDepartment = $_POST["department"];
        $employeePosition = $_POST["position"];
        $employeeRegisteredDate = date("y-m-d");
        $employeeStatus = "Active";
      
        $fileName = basename($_FILES["profilePicture"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowTypes = array("jpg", "png", "jpeg");

        if(in_array($fileType, $allowTypes)){ 
          $image = $_FILES['profilePicture']['tmp_name']; 
          $imgContent = addslashes(file_get_contents($image)); 
        }

        $query2 = "SELECT * FROM employee WHERE email = '$employeeEmail'";
        $result2 = mysqli_query($connect, $query2) or die (mysqli_error($connect));
      
        if(mysqli_num_rows($result2) !== 1) {
          $query3 = "INSERT INTO employee (employeeID, email, password, name, profilePicture, contact, emergencyContact, address, zip, city, state, department, position, registeredDate, status) VALUES ('$employeeID', '$employeeEmail', '$employeePassword', '$employeeName', '$imgContent', '$employeeContact', '$employeeEmergencyContact', '$employeeAddress', '$employeeZip', '$employeeCity', '$employeeState', '$employeeDepartment', '$employeePosition', '$employeeRegisteredDate', '$employeeStatus')";
          mysqli_query($connect, $query3) or die (mysqli_error($connect));
      
          $query4 = "SELECT * FROM laeve";
          $result4 = mysqli_query($connect, $query4) or die (mysqli_error($connect));
      
          if(mysqli_num_rows($result4) > 0) {
            foreach($result4 as $row4) {
              $leaveType = $row4["type"];
              $daysEntitled = $row4["daysEntitled"];
      
              $query5 = "INSERT INTO balance (leaveType, daysAvailable, daysTaken, employeeID) VALUES ('$leaveType', '$daysEntitled', '0', '$employeeID')";
              mysqli_query($connect, $query5) or die(mysqli_error($connect));
            }
          }
          try {
            // Create the Transport
            $transport = (new Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
              ->setUsername('fypise2022@gmail.com')
              ->setPassword('Fyp140222#')
            ;
        
            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);
        
            // Create a message
            $body = 'Hello '.$employeeName.', 
                    <p>Here is the temporary password <b> '.$employeePassword.' </b> for account '.$employeeEmail.'.</p>
                    <p><b>You may change the password upon login.<b></p>';
        
            $message = (new Swift_Message('Leave Management System Account Registration'))
              ->setFrom(['fypise2022@gmail.com' => 'Reitech Solution Human Resource'])
              ->setTo([$employeeEmail])
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
            swal.fire({
              icon: 'success',
              title: 'Employee Added',
            }).then(function() {
              window.location.href = 'adminManagesEmployees.php'
            });
          </script>
        <?php 
        } 
        else { 
        ?>
          <script>
            swal.fire({
              icon: 'warning',
              title: 'Employee Already Existed',
            })
          </script> 
        <?php }
      }
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
      <h1>Add Employee</h1>
      <nav>
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="adminDashboard.php">Home</a></li>
          <li class="breadcrumb-item">Employees</li>
          <li class="breadcrumb-item"><a href="adminManagesEmployees.php">Manage Employee</a></li>
          <li class="breadcrumb-item active">Add Employee</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Employee Details</h5>
                <!-- Add Employee Form -->
                <form class="row g-3 needs-validation" method="post" enctype="multipart/form-data" novalidate onsubmit="checkValidation()">
                    <div class="col-md-12">
                      <div class="form-floating has-validation">
                      <input type="text" class="form-control" name="name" placeholder="Name" id="name" pattern="[a-zA-Z ]+" required>
                        <label for="floatingName">Name</label>
                        <div class="invalid-feedback"><p id="n"></p></div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-floating has-validation">
                      <input type="email" class="form-control" name="email" placeholder="Email" id="email" pattern="[a-z0-9._%+-]+@gmail.com" required>
                        <label for="floatingEmail">Email</label>
                        <div class="invalid-feedback"><p id="e"></p></div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                      <input type="tel" class="form-control" name="contact" placeholder="Contact" id="contact" pattern="[0-9]{3}-[0-9]{7}" minlength="11" maxlength="11" required>
                        <label for="floatingContact">Contact</label>
                        <div class="invalid-feedback"><p id="c"></p></div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                      <input type="tel" class="form-control" name="emergencyContact" placeholder="Emergency Contact" id="emergencyContact" pattern="[0-9]{3}-[0-9]{7}" minlength="11" maxlength="11" required>
                        <label for="floatingEmergencyContact">Emergency Contact</label>
                        <div class="invalid-feedback"><p id="ec"></p></div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <label for="profilePicture" class="col-sm-2 col-form-label">Profile Picture Upload</label>
                      <div class="col-sm-10">
                        <input type="file" name="profilePicture" class="form-control" accept="image/*" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <select class="form-select" name="department" aria-label="Department" required>
                          <option value="Accounting">Accounting</option>
                          <option value="Information Technology">Information Technology</option>
                          <option value="Finance">Finance</option>
                        </select>
                        <label for="floatingDepartment">Department</label>
                        <div class="invalid-feedback">Please select department!</div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating has-validation">
                        <input type="text" class="form-control" name="position" placeholder="Position" id="position" pattern="[a-zA-Z ]+" required>
                        <label for="floatingPosition">Position</label>
                        <div class="invalid-feedback"><p id="p"></p></div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating has-validation">
                      <textarea class="form-control" name="address" placeholder="Address" id="address" style="height: 100px;" pattern="^[\w\d./*]{1,},[^\S][\w\d./* ]{1,},[^\S][\w\d./* ]{1,}$"  minlength="5" required></textarea>
                        <label for="floatingTextarea">Address</label>
                        <div class="invalid-feedback"><p id="ad"></p></div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="col-md-12">
                        <div class="form-floating has-validation">
                        <input type="text" class="form-control" name="city" placeholder="City" id="city" pattern="[a-zA-Z ]+" required>
                          <label for="floatingCity">City</label>
                          <div class="invalid-feedback"><p id="ct"></p></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3 has-validation">
                        <select class="form-select" name="state" aria-label="State" required>
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
                      <input type="text" class="form-control" name="zip" placeholder="Zip" id="zip" pattern="^[0-9]{1,}$" minlength="5" maxlength="5" required>
                        <label for="floatingZip">Zip</label>
                        <div class="invalid-feedback"><p id="z"></p></div>
                      </div>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary mb-2" name="add"><i class='ri-add-circle-fill'></i> Add</button>
                      <a href="adminManagesEmployees.php"><button type="button" class="btn btn-secondary mb-2"><i class='bx bxs-left-arrow-alt'></i> Back</button></a>
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

  <script>
    function checkValidation() {
      var a = document.getElementById("name");
      var b = document.getElementById("email");
      var c = document.getElementById("contact");
      var d = document.getElementById("emergencyContact");
      var e = document.getElementById("position");
      var f = document.getElementById("address");
      var g = document.getElementById("city");
      var h = document.getElementById("zip");

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

      //Check Position
      if(e.validity.valueMissing) {
        document.getElementById("p").innerHTML = "Please enter position!";
      }
      else if(e.validity.patternMismatch){
        document.getElementById("p").innerHTML = "Position must be letters only!";
      }

      //Check Address
      if(f.validity.valueMissing) {
        document.getElementById("ad").innerHTML = "Please enter address!";
      }
      else if(f.validity.patternMismatch){
        document.getElementById("ad").innerHTML = "Please use the correct format! Example: No.1, Street, Area";
      }

      //Check City
      if(g.validity.valueMissing) {
        document.getElementById("ct").innerHTML = "Please enter city!";
      }
      else if(g.validity.patternMismatch){
        document.getElementById("ct").innerHTML = "City must be letters only!";
      }

      //Check Zip
      if(h.validity.valueMissing) {
        document.getElementById("z").innerHTML = "Please enter zip!";
      }
      else if(h.validity.patternMismatch) {
        document.getElementById("z").innerHTML = "Zip must be digit only";
      }
      else if(h.validity.tooLong) {
        document.getElementById("z").innerHTML = "Zip entered exceeds 5 digits!";
      }
      else if(h.validity.tooShort) {
        document.getElementById("z").innerHTML = "Zip entered subceeds 5 digits!";
      }
    }  
  </script>
</body>

</html>