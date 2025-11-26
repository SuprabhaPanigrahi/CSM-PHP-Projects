<?php

// WAP TO CREATE ASSOCIATIVE ARRAY OF 10 EMPLOYEES WITH FOLLOWING KEY :
// ID NAME SALARY AGE

// DISPLAY MENU TO PERFORM FOLLOWING ACTIONS ON THOSE RECORDS :
// 1. ADD NEW EMPLOYEE
// 2. SEARCH EMPLOYEE BY NAME
// 3. PRINT ANNUAL SALARY OF ALL EMPLOYEES
// 4. DISPLAY ALL RECORD IN ASCENDING ORDER
// 5. DESCENDING 
// 6. FIND TOTAL SALARY EXPENSES OVER ALL EMPLOYEES ANNUALLY
// 7. CREATE COPY OF ALL EMPLOYEE RECORD WHOSE SALARY IS GREATER THAN 5000 AND PRINT THE NEW COPY
// 8. CREATE A NEW ARRAY OF EMPLOYEES WITH ADDITIONAL DETAILS OF EACH EMPLOYEE 
// COMMISSION PERCENTAGE
// TOTAL SALARY

$employees = [
    ["ID" => 101, "Name" => "A", "Salary" => 10000, "Age" => 25],
    ["ID" => 102, "Name" => "B", "Salary" => 10100, "Age" => 25],
    ["ID" => 103, "Name" => "C", "Salary" => 10500, "Age" => 25],
    ["ID" => 104, "Name" => "D", "Salary" => 11000, "Age" => 25],
    ["ID" => 105, "Name" => "E", "Salary" => 18000, "Age" => 25],
    ["ID" => 106, "Name" => "F", "Salary" => 10020, "Age" => 25],
    ["ID" => 107, "Name" => "G", "Salary" => 20000, "Age" => 25],
    ["ID" => 108, "Name" => "H", "Salary" => 14000, "Age" => 25],
    ["ID" => 109, "Name" => "I", "Salary" => 16000, "Age" => 25],
    ["ID" => 110, "Name" => "J", "Salary" => 14000, "Age" => 25],
];

function printMenu()
{
    echo "\nChoose the action to perform:\n";
    echo "1. Add new employee\n";
    echo "2. Search employee by name\n";
    echo "3. Print annual salary of all employees\n";
    echo "4. Display all records ascending by Name\n";
    echo "5. Display all records descending by Name\n";
    echo "6. Total annual salary expense\n";
    echo "7. Copy employees with salary > 5000 and print\n";
    echo "8. New array with commission % and total salary\n";
    echo "9. Exit\n";
}

do {
    printMenu();
    $choice = (int) readline("Enter your choice: ");

    switch ($choice) {
        case 1:
            addNewEmployee();
            break;
        case 2:
            searchEmployeeByName();
            break;
        case 3:
            printAnnualSalary();
            break;
        case 4:
            displayRecordsAsc(true);
            break;
        case 5:
            displayRecordsAsc(false);
            break;
        case 6:
            totalSalaryExpense();
            break;
        case 7:
            copyHighSalaryEmployees();
            break;
        case 8:
            addCommissionAndTotalSalary();
            break;
        case 9:
            echo "Exiting...\n";
            break;
        default:
            echo "Invalid choice! Try again.\n";
    }
} while ($choice !== 9);



// --- FUNCTIONS BELOW ---

function addNewEmployee()
{
    global $employees;

    $id = (int) readline("Enter ID: ");
    $name = readline("Enter Name: ");
    $salary = (float) readline("Enter Salary: ");
    $age = (int) readline("Enter Age: ");

    $employees[] = ["ID" => $id, "Name" => $name, "Salary" => $salary, "Age" => $age];
    echo "Employee added successfully.\n";
}

function searchEmployeeByName()
{
    global $employees;

    $searchName = readline("Enter name to search: ");
    $found = false;

    foreach ($employees as $emp) {
        if (strcasecmp($emp['Name'], $searchName) === 0) {
            print_r($emp);
            $found = true;
            break;
        }
    }

    if (!$found) {
        echo "Employee not found.\n";
    }
}

function printAnnualSalary()
{
    global $employees;

    foreach ($employees as $emp) {
        echo "{$emp['Name']} - Annual Salary: " . ($emp['Salary'] * 12) . "\n";
    }
}

function displayRecordsAsc($asc = true)
{
    global $employees;

    $sorted = $employees;
    usort($sorted, function ($a, $b) use ($asc) {
        return $asc ? strcmp($a['Name'], $b['Name']) : strcmp($b['Name'], $a['Name']);
    });

    echo $asc ? "Ascending Order:\n" : "Descending Order:\n";
    foreach ($sorted as $emp) {
        print_r($emp);
    }
}

function totalSalaryExpense()
{
    global $employees;

    $total = 0;
    foreach ($employees as $emp) {
        $total += $emp['Salary'] * 12;
    }

    echo "Total annual salary expense: $total\n";
}

function copyHighSalaryEmployees()
{
    global $employees;

    $copy = array_filter($employees, fn($emp) => $emp['Salary'] > 5000);
    echo "Employees with salary > 5000:\n";

    foreach ($copy as $emp) {
        print_r($emp);
    }
}

function addCommissionAndTotalSalary()
{
    global $employees;

    $newArray = [];
    foreach ($employees as $emp) {
        $commissionPercent = 10; // Example fixed commission rate
        $totalSalary = $emp['Salary'] + ($emp['Salary'] * $commissionPercent / 100);
        $emp['Commission%'] = $commissionPercent;
        $emp['TotalSalary'] = $totalSalary;
        $newArray[] = $emp;
    }

    echo "Employees with commission and total salary:\n";
    foreach ($newArray as $emp) {
        print_r($emp);
    }
}

?>
