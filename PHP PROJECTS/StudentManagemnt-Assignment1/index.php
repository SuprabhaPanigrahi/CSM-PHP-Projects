<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EduTrack - Manage Students Effortlessly</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
      integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
      body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
      }
      .header {
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 1rem 0;
      }
      .hero {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
        margin-bottom: 2rem;
      }
      .divider {
        height: 2px;
        background-color: #dee2e6;
        margin: 2rem 0;
      }
      .features {
        padding: 2rem 0;
      }
      .feature-table {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        border-collapse: collapse;
      }
      .feature-table td {
        padding: 1.5rem;
        text-align: center;
        border: 1px solid #dee2e6;
        font-weight: 500;
      }
      .btn-primary {
        background-color: #4361ee;
        border-color: #4361ee;
      }
    </style>
  </head>
  <body>
    <!-- Header -->
    <header class="header">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">
          <h1 class="h3 mb-0">
            <i class="fa-solid fa-user-graduate">EduTrack</i>
          </h1>
          <div>
            <button class="btn btn-outline-secondary me-2">Home</button>
            <button class="btn btn-primary">Features</button>
            <button class="btn btn-light" id="loginBtn1">Login</button>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
      <div class="container">
        <h2 class="display-4 fw-bold mb-3">Manage Students Effortlessly</h2>
        <div class="d-flex justify-content-center gap-3">
          <button class="btn btn-outline-light me-2" id="loginBtn2">
            Login
          </button>
          <button class="btn btn-outline-light">Get Started</button>
        </div>
      </div>
    </section>

    <!-- Divider -->
    <div class="container">
      <div class="divider"></div>
    </div>

    <!-- Features Section -->
    <section class="features">
      <div class="container">
        <h3 class="text-center mb-4">Features</h3>
        <table class="feature-table">
          <tr>
            <td>Sessions</td>
            <td>CRUD</td>
            <td>File Uploads</td>
            <td>AJAX</td>
          </tr>
        </table>
      </div>
    </section>

    <script>
      document.getElementById("loginBtn1").onclick = function () {
        window.location.href = "pages/login.php";
      };

      document.getElementById("loginBtn2").onclick = function () {
        window.location.href = "pages/login.php";
      };
    </script>
  </body>
</html>
