<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      padding: 20px;
    }

    .fieldset-container {
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 30px;
    }

    .fieldset-title {
      font-weight: bold;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #dee2e6;
      color: #495057;
    }

    .form-label {
      font-weight: 500;
      color: #495057;
    }

    .table th {
      background-color: #e9ecef;
      color: #495057;
    }

    .table-container {
      overflow-x: auto;
    }

    .notes-column {
      min-width: 150px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1 class="text-center mb-4 text-primary">Employee Management Form</h1>

    <!-- Employee Details Section -->
    <div class="fieldset-container">
      <div class="fieldset-title">Employee Details</div>
      <form id="employeeForm">
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter employee name">
          </div>
          <div class="col-md-6">
            <label for="hireDate" class="form-label">Hire Date</label>
            <div class="input-group">
              <input type="date" class="form-control" id="hireDate" name="hireDate">
              <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                  <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z" />
                  <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z" />
                </svg>
              </span>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="salary" class="form-label">Salary</label>
            <input type="number" step="0.01" class="form-control" id="salary" name="salary" placeholder="Enter salary">
          </div>
          <div class="col-md-6">
            <label for="dept_id" class="form-label">Department</label>
            <select class="form-select" id="dept_id" name="dept_id">
              <option value="">Select Department</option>
              <!-- Departments will be loaded dynamically -->
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Employment Type</label>
            <div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="employmentType" id="contractual" value="contractual">
                <label class="form-check-label" for="contractual">Contractual</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="employmentType" id="permanent" value="permanent">
                <label class="form-check-label" for="permanent">Permanent</label>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-4">
            <button type="button" id="saveEmployee" class="btn btn-primary me-2">Save</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
          </div>
      </form>
    </div>

    <!-- Employees List Section -->
    <div class="fieldset-container">
      <div class="fieldset-title">Employees List</div>
      <div class="table-container">
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th scope="col">SL #</th>
              <th scope="col">Name</th>
              <th scope="col">Hire Date</th>
              <th scope="col">Salary</th>
              <th scope="col">Department</th>
              <th scope="col">Employment Type</th>
              <th scope="col" class="notes-column">Action</th>
            </tr>
          </thead>
          <tbody id="employeesTableBody">
            <!-- employees will be populated here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    $(document).ready(function() {
      getEmployees();
      loadDepartments();
      $('#employeeForm').on('submit',function(){
        addEmployees();
      })
    });

    function getEmployees() {
      $.ajax({
        url: 'process/getEmployees.php',
        method: 'GET',
        success: function(response) {
          console.log(response);
          let employees = JSON.parse(response);
          console.log(employees);
          $('#employeesTableBody').empty();
          let i = 1;
          for (let index in employees) {
            $('#employeesTableBody').append(
              `<tr>
                <td>${i}</td>
                <td>${employees[index]['name']}</td>
                <td>${employees[index]['hire_date']}</td>
                <td>${employees[index]['salary']}</td>
                <td>${employees[index]['employment_type']}</td>
                <td>${employees[index]['dept_id']}</td>
                <td><a href='#' onclick="viewEmployees(${employees[index]['emp_id']})";><i class="fas fa-edit"></i></a>
               <a href="#" onclick='deleteEmployees(${employees[index]['emp_id']})';><i class="fas fa-trash text-danger"></i></a>
                </td>
              </tr>`
            )
          }
        }
      });
    }
    function loadDepartments(){
      $.ajax({
        url : 'process/loadDepartments.php',
        method : 'POST',
        success : function(response){
          console.log(`Raw response : ${response}`);
          let departments = JSON.parse(response);
          console.log(departments);
          for(let index in departments){
            $('#dept_id').append(`<option value=${departments[index]['dept_id']}>${departments[index]['name']}</option>`)
          }
        },
        error : function(response){
          console.log(error);
          
        }
      })
    }
    function addEmployees(){
      $.ajax({
        url : 'process/addEmployees.php',
        method : 'POST',
        data : {name : name, hire_date : hireDate, salary : salary, department : dept_id, employment_type : employment_type},
        success : function(response){
          alert('Data inserted successfully');
        },
        error : function(response){
          alert('Error inserting data');
        }
      });
    }
  </script>
</body>

</html>