<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Country</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-5">
    <div class="card shadow p-4">
      <h4 class="text-center mb-4">Add Country</h4>

      <!-- Form -->
      <div class="row mb-3">
        <div class="col-md-5">
          <input type="text" id="country_name" class="form-control" placeholder="Enter Country Name">
        </div>
        <div class="col-md-5">
          <input type="text" id="country_code" class="form-control" placeholder="Enter Country Code">
        </div>
        <div class="col-md-2">
          <button id="add_country" class="btn btn-primary w-100">Add</button>
        </div>
      </div>

      <!-- Table -->
      <table class="table table-bordered text-center" id="country_table">
        <thead class="table-dark">
          <tr>
            <th>SL No</th>
            <th>Country Name</th>
            <th>Country Code</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Example Row -->
        </tbody>
      </table>


    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    $(document).ready(function() {
      function loadCountries() {
        console.log("Loading countries...");
        $.ajax({
          url: 'process/processCountry.php',
          method: 'GET',
          success: function(response) {
            console.log("Raw response:", response);
            console.log("Response type:", typeof response);

            try {
              let data = response;
              console.log("Data:", data);

              let str = '';
              let count = 1;

              // Check if it's an array
              if (Array.isArray(data)) {
                data.forEach(row => {
                  str += `
              <tr>
                <td>${count++}</td>
                <td>${row.name}</td>
                <td>${row.code}</td>
                <td>
                    <div class="actions">
                    <button class="btn-update" onclick="updateStudent(${row.code})">
                      <i class="fas fa-edit me-1"></i>Update</button>
                    <button class="btn-delete" onclick="confirmDelete(${row.code}, '$(row.name.replace(/'/g, "\\'")}')">
                      <i class="fas fa-trash me-1"></i>Delete</button>
                    </div>
                  </td>
              </tr>
            `;
                });

                $("tbody").html(str);
              } else {
                console.error("Expected array but got:", data);
                $("tbody").html('<tr><td colspan="3" class="text-danger">Invalid data format</td></tr>');
              }
            } catch (e) {
              console.error("Error processing data:", e, response);
              $("tbody").html('<tr><td colspan="3" class="text-danger">Error loading data</td></tr>');
            }
          },
          error: function(error) {
            console.error("AJAX Error:", error);
            $("tbody").html('<tr><td colspan="3" class="text-danger">Failed to load data</td></tr>');
          }
        });
      }

      // Load data on page load
      loadCountries();

      // Add new country
      $("#add_country").click(function() {
        let name = $("#country_name").val().trim();
        let code = $("#country_code").val().trim();

        if (name === "" || code === "") {
          alert("Please enter both country name and code!");
          return;
        }

        $.ajax({
          url: 'process/addCountry.php',
          method: 'POST',
          data: {
            name: name,
            code: code
          },
          success: function(response) {
            console.log("Add response:", response);
            if (response.trim() === "success") {
              $("#country_name").val('');
              $("#country_code").val('');
              loadCountries();
            } else {
              alert("Failed to add country: " + response);
            }
          },
          error: function(error) {
            console.error("Add Error:", error);
          }
        });
      });

    });
  </script>
</body>

</html>