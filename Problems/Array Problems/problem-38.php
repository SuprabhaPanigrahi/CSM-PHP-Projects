<?PHP

// WAP TO CREATE ASSOCIATIVE ARRAY OF 10 EMPLOYEES WITH FOLLOWING KEY :
// ID NAME SALARY AGE

// DISPLAY MENU TO PERFORM FOLLOWING ACTIONS ON THOSE RECORDS :
// 1. ADD NEW EMPLOYEE
// 2. SEARCH EMPLOYEE BY NAME
// 3. PRINT ANNUAL SALARY OF ALL EMPLOYEES
// 4. DISPLAY ALL RECORD IN ASCENDING ORDER
// 5. DESCENDING 
// 6. FIND TOTAL SALARY EXPENSES OVER ALL EMPLOYEES ANNUALLY
// 7. CREATE COPY OF ALL EMPLOYEE RECORD WHOSE SALARY IS GREATHER THAN 5OOO AND PRINT THE NEW COPY
// 8. CREATE A NEW ARRAY OF EMPLOYEES WITH ADDITIONAL DETAILS OF EACH EMPLOYEE 
// COMMISSION PERCENTAGE
// TOTAL SALRY


$employees = [
    ["ID" => 101, "Name" => "A", "Salary" => 10000, "Age" => 25],
    ["ID" => 102, "Name" => "B", "Salary" => 10000, "Age" => 25],
    ["ID" => 103, "Name" => "C", "Salary" => 10000, "Age" => 25],
    ["ID" => 104, "Name" => "D", "Salary" => 10000, "Age" => 25],
    ["ID" => 105, "Name" => "E", "Salary" => 10000, "Age" => 25],
    ["ID" => 106, "Name" => "F", "Salary" => 10000, "Age" => 25],
    ["ID" => 107, "Name" => "G", "Salary" => 10000, "Age" => 25],
    ["ID" => 108, "Name" => "H", "Salary" => 10000, "Age" => 25],
    ["ID" => 109, "Name" => "I", "Salary" => 10000, "Age" => 25],
    ["ID" => 110, "Name" => "J", "Salary" => 10000, "Age" => 25],
];

function printMenu()
{
    echo "\n Choose the action to perform: \n";
    echo "1. Add new employee\n";
    echo "2. Search employee by name\n";
    echo "3. Print annual salary of all employees\n";
    echo "4. Display all records ascending by Name\n";
    echo "5. Display all records descending by Name\n";
    echo "6. Total annual salary expense\n";
    echo "7. Copy employees with salary > 5000 and print\n";
    echo "8. New array with commission % and total salary\n";
    echo "9. Exit\n";
    echo "Enter choice: ";
}

do {
    printMenu();
    $choice = (int)readline("enter your choice : ");

    switch ($choice) {
        case 1:
            addNewEmployee($employees);
            break;
        case 2:
            searchEmployeeByName($employees);
            break;
        case 3:
            printAnnualSalary($employees);
            break;
        case 4:
            displayRecords($employees, true);
            break;
        case 5:
            displayRecords($employees, false);
            break;
        case 6:
            totalSalaryExpense($employees);
            break;
        case 7:
            copyHighSalaryEmployees($employees);
            break;
        case 8:
            addCommissionAndTotalSalary($employees);
            break;
        case 9:
            echo "Exiting...\n";
            break;
        default:
            echo "Invalid choice! Try again.\n";
    }
} while ($choice !== 9);



function addNewEmployee(&$employees)
{
    $id = (int) readline("Enter ID: ");
    $name = readline("Enter Name: ");
    $salary = (float) readline("Enter Salary: ");
    $age = (int) readline("Enter Age: ");

    $employees[] = ["ID" => $id, "Name" => $name, "Salary" => $salary, "Age" => $age];
    echo "Employee added successfully.\n";
}

function searchEmployeeByName($employees)
{
    $searchName = readline("Enter name to search: ");
    $found = false;
    foreach ($employees as $emp) {
        if (strcasecmp($emp['Name'], $searchName) === 0) {
            print_r($emp);
            $found = true;
        }
    }
    if (!$found) {
        echo "Employee not found.\n";
    }
}

function printAnnualSalary($employees)
{
    foreach ($employees as $emp) {
        echo "{$emp['Name']} - Annual Salary: " . ($emp['Salary'] * 12) . "\n";
    }
}

function displayRecordsAsc($employees, $asc = true)
{
    usort($employees, function ($a, $b) use ($asc) {
        return $asc ? strcmp($a['Name'], $b['Name']) : strcmp($b['Name'], $a['Name']);
    });
    foreach ($employees as $emp) {
        print_r($emp);
    }
}

function displayRecordsDesc($employees, $asc = false)
{
    usort($employees, function ($a, $b) use ($asc) {
        return $asc ? strcmp($a['Name'], $b['Name']) : strcmp($b['Name'], $a['Name']);
    });
    foreach ($employees as $emp) {
        print_r($emp);
    }
}

function totalSalaryExpense($employees)
{
    $total = 0;
    foreach ($employees as $emp) {
        $total += $emp['Salary'] * 12;
    }
    echo "Total annual salary expense: $total\n";
}

function copyHighSalaryEmployees($employees)
{
    $copy = array_filter($employees, fn($emp) => $emp['Salary'] > 5000);
    echo "Employees with salary > 5000:\n";
    foreach ($copy as $emp) {
        print_r($emp);
    }
}

function addCommissionAndTotalSalary($employees)
{
    $newArr = [];
    foreach ($employees as $emp) {
        $commissionPercent = 10; // example commission percent
        $commissionAmount = $emp['Salary'] * $commissionPercent / 100;
        $totalSalary = $emp['Salary'] + $commissionAmount;

        $emp['CommissionPercent'] = $commissionPercent;
        $emp['TotalSalary'] = $totalSalary;

        $newArr[] = $emp;
    }
    echo "Employees with commission and total salary:\n";
    foreach ($newArr as $emp) {
        print_r($emp);
    }
}



?>