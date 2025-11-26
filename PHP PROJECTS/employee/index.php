<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container">
        <div class="row">
            <fieldset>
                <legend>Employee DEtails</legend>
                <form>
                    <div class="mb-3">
                        <label>
                            Employee Name
                        </label>
                        <input class="form-control" type="text" id="name" name="name" placeholder="enter your name">
                    </div>
                    <div class="mb-3">
                        <label> Hire Date</label>
                        <input class="form-control" type="date" name="hire_date" id="hire_date">
                    </div>
                    <div class="mb-3">
                        <label> Salary</label>
                        <input class="form-control" type="text" name="salary" id="salary">
                    </div>
                    <div class="mb-3">
                        <label> Department </label>
                        <select class="form-select" id="department" name="department"></select>
                    </div>
                    <div class="mb-3">
                        <label> Employment Type</label>
                        <input type="radio" name="etype" id="contractual" value="contractual"> Contractual
                        <input type="radio" name="etype" id="permanent" value="permanent"> Permanent
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                    <button class="btn btn-warning" type="reset">Reset</button>
                </form>
            </fieldset>
        </div>
        <div class="row mt-5">
            <fieldset>
                <legend>Table</legend>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th> #</th>
                            <th> Name</th>
                            <th> Hire Date</th>
                            <th> Salary</th>
                            <th> Employement Type</th>
                            <th> Department</th>
                            <th colspan="2"> Action</th>
                        </tr>
                    </thead>
                    <tbody id="emp_tables">
                        <!-- <tr></tr> -->
                    </tbody>
                </table>

            </fieldset>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            loadDepartments();
            $('form').on('submit', function(e) {
                e.preventDefault();
                saveEmployees();
            })
            showTables();
        });

        function loadDepartments() {
            $.ajax({
                url: 'process/fetch_departments.php',
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let departments = JSON.parse(response);
                    console.log(departments);
                    for (let index in departments) {
                        $('#department').append(`<option value=${departments[index]['dept_id']}>${departments[index]['name']}</option>`)
                    }

                },
                error: function(error) {
                    console.log(error);

                }
            });
        }

        function saveEmployees() {
            let name = $('#name').val();
            let hire_date = $('#hire_date').val();
            let salary = $('#salary').val();
            let department = $('#department').val();
            // let etype = $('.etype').val();
            let etype = $('input[name="etype"]:checked').val();
            // alert(etype);
            $.ajax({
                url: 'process/insert_employees.php',
                type: 'POST',
                data: {
                    name: name,
                    hire_date: hire_date,
                    salary: salary,
                    department: department,
                    etype: etype
                },
                success: function(response) {
                    console.log(response);
                    showTables();

                },
                error: function(error) {
                    console.log(error);

                }
            });
        }

        function showTables() {
            $.ajax({
                url: 'process/fetch_employees.php',
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let employees = JSON.parse(response);
                    console.log(employees);

                    $('#emp_tables').empty();
                    let i = 1;
                    for (let index in employees) {
                        $('#emp_tables').append(`
                        <tr>
                    <td> ${i}</td>
                    <td> ${employees[index]['name']}</td>
                    <td> ${employees[index]['hire_date']}</td>
                    <td> ${employees[index]['salary']}</td>
                    <td> ${employees[index]['employment_type']}</td>
                    <td> ${employees[index]['department_name']}</td>
                    <td><a href="#" onclick='viewEmployees(${employees[index]['emp_id']})';><i class="fas fa-edit"></i></a>
                    <a href="#" onclick='deleteEmployees(${employees[index]['emp_id']})';><i class="fas fa-trash text-danger"></i></a>
                    </td>
                </tr>
                        `)
                        i++;
                    }
                }
            });
        }

        function viewEmployees(employeeId) {
            // alert(employeeId);
            $.ajax({
                url: 'process/fetch-employee-by-id.php',
                type: 'GET',
                data: {
                    id: employeeId
                },
                success: function(response) {
                    console.log(response);
                    let employee = JSON.parse(response);
                    if (employee) {
                        $('#name').val(employee['name']);
                        $('#hire_date').val(employee['hire_date']);
                        $('#salary').val(employee['salary']);
                        $('#department').val(employee['dept_id']);
                        if (employee['employment_type'] == 'contractual') {
                            $('#contractual').prop('checked', true);
                            $('#permanent').prop('checked', false);

                        } else {
                            $('#permanent').prop('checked', true);
                            $('#contractual').prop('checked', false);

                        }
                    }
                },
                error: function(error) {
                    console.log(error); 
                }
            })
        }

        function deleteEmployees(employeeId) {
            // alert(employeeId);
            if (confirm('Do you want to delete this record?')) {
                $.ajax({
                    url: 'process/delete-employee.php',
                    type: 'POST',
                    data: {
                        id: employeeId
                    },
                    success: function(response) {
                        alert('Employee deleted successfully');
                        showTables();
                    },
                    error: function(error) {
                        console.log(error);

                    }
                })
            }
        }
    </script>
</body>

</html>