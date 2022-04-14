<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Reitech Solution Leave Management System - Reset Password</title>
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

    if(isset($_GET["key"])) {
      $email = $_GET["key"];
    }

    if(isset($_POST["submit"])) {
      $email = $_POST["email"];
      $newPassword = $_POST["newPassword"];
      $reenterPassword = $_POST["re-enterNewPassword"];
      $dbPassword = "";

      $query1 = "SELECT * FROM employee WHERE email = '$email'";
      $result1 = mysqli_query($connect, $query1) or die (mysqli_error($connect)); 

      if(mysqli_num_rows($result1) === 1) {
        foreach($result1 as $row1) {
          $dbPassword = $row1["password"];
        }
      }

      if(strcmp($newPassword, $reenterPassword) === 0) {
        if(strcmp($newPassword, $dbPassword) === 0) { 
          ?>
            <script>
              Swal.fire({
                icon: 'error',
                title: 'Warning',
                text: 'New password cant be same with previous password',
              })
            </script>
          <?php 
        }
        else {
          $query2 = "UPDATE employee SET password = '$newPassword' WHERE email = '$email'";
          mysqli_query($connect, $query2) or die (mysqli_error($connect)); 
          ?>         
            <script>
              swal.fire({
                icon: 'success',
                title: 'Password Reset',
              }).then(function() {
                window.location.href = 'index.php'
              });
            </script>
          <?php 
        }
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
  ?>

  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="d-flex justify-content-center py-4">
                <a href="index.php" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/company-logo.png" alt="">
                  <span class="d-none d-lg-block">Reitech Solution</span>
                </a>
              </div><!-- End Logo -->
              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Reset Password</h5>
                    <p class="text-center small">Enter New Password to Reset Password</p>
                  </div>
                  <form class="row g-3 needs-validation" method="post" novalidate onsubmit="checkValidation()">
                    <div class="col-12">
                      <label for="newPassword" class="form-label">New Password</label>
                      <input type="password" name="newPassword" class="form-control" id="newPassword" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{1,}$" minlength="8" required>
                      <input type="hidden" name="email" value="<?php echo $email ?>">
                      <div class="invalid-feedback"><p id="np"></p></div>
                    </div>
                    <div class="col-12">
                      <label for="re-enterNewPassword" class="form-label">Re-enter New Password</label>
                      <input type="password" name="re-enterNewPassword" class="form-control" id="re-enterNewPassword" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{1,}$" minlength="8" required>
                      <div class="invalid-feedback">Please re-enter new password!</div>
                    </div>
                    <div class="col-12">
                      <input type="checkbox" class="form-check-input" onclick="showPassword()">
                      <label class="form-check-label" for="rememberMe">Show password</label>
                    </div>  
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit" name="submit">Submit</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Back to login? <a href="index.php">Click here</a></p>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>      
    </div>
  </main><!-- End #main -->

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
    function checkValidation() {
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