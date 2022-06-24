<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Reitech Solution Leave Management System - Forgot Password</title>
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

    if(isset($_POST["submit"])) {
      $email = $_POST["email"];
      $query = "SELECT * FROM employee WHERE email = '$email'";
      $result = mysqli_query($connect, $query) or die (mysqli_error($connect));

      if(mysqli_num_rows($result) === 1) {
        foreach($result as $row) {
          $name = $row["name"];
        }
        $link = "<a href='http://localhost:8080/lms/resetPassword.php?key=".$email."'> Click Here</a>";

        try {
          // Create the Transport
          $transport = (new Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
            ->setUsername('fypise2022@gmail.com')
            ->setPassword('pznkeidszbkhwygq')
          ;
      
          // Create the Mailer using your created Transport
          $mailer = new Swift_Mailer($transport);
      
          // Create a message
          $body = 'Hi '.$name.', <p>Please '.$link.' to reset leave management account password.</p>';
      
          $message = (new Swift_Message('Leave Management Account Reset Password'))
            ->setFrom(['fypise2022@gmail.com' => 'Reitech Solution Human Resource'])
            ->setTo([$email])
            ->setBody($body)
            ->setContentType('text/html')
          ;
      
          // Send the message
          $mailer->send($message); 
          ?>
            <script>
              Swal.fire({
                icon: 'info',
                title: 'Check Email',
              })
            </script>
          <?php 
        } 
        catch(Exception $e) {
            echo $e->getMessage();
        }
      }
      else { 
      ?>
        <script>
          Swal.fire({
            icon: 'error',
            title: 'Invalid Credentials',
            text: 'Email not found',
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
                    <h5 class="card-title text-center pb-0 fs-4">Reset Your Account Password</h5>
                    <p class="text-center small">Enter Email Address To Send Password Link</p>
                  </div>
                  <form class="row g-3 needs-validation" method="post" novalidate >
                    <div class="col-12">
                      <label for="email" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" name="email" class="form-control" id="email" required>
                        <div class="invalid-feedback">Please enter your email!</div>
                      </div>
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
</body>

</html>