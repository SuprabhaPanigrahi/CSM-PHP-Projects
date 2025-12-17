<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Salary;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    // Display combined data from all 3 tables
    public function showCombinedData()
    {
        // Method 1: Using Eloquent Relationships
        $employees = Employee::with(['department', 'salary'])->get();
        
        // Method 2: Using Raw SQL Join (Alternative)
        $combinedData = DB::table('employees')
            ->join('departments', 'employees.id', '=', 'departments.emp_id')
            ->join('salaries', 'employees.id', '=', 'salaries.emp_id')
            ->select(
                'employees.id',
                'employees.emp_name',
                'employees.emp_code',
                'employees.emp_email',
                'employees.emp_phone',
                'employees.joining_date',
                'departments.dept_name',
                'departments.dept_head',
                'departments.location',
                'salaries.basic_salary',
                'salaries.allowances',
                'salaries.deductions',
                'salaries.net_salary',
                'salaries.payment_date'
            )
            ->orderBy('employees.id', 'asc')
            ->get();
        
        // Get individual table data
        $allEmployees = Employee::all();
        $allDepartments = Department::all();
        $allSalaries = Salary::all();

        return view('employee_data', compact(
            'combinedData', 
            'employees',
            'allEmployees', 
            'allDepartments', 
            'allSalaries'
        ));
    }

    // Add new employee with department and salary
    public function addEmployee(Request $request)
    {
        $request->validate([
            'emp_name' => 'required|string|max:255',
            'emp_code' => 'required|unique:employees',
            'emp_email' => 'required|email|unique:employees',
            'emp_phone' => 'required',
            'joining_date' => 'required|date',
            'dept_name' => 'required',
            'dept_head' => 'required',
            'location' => 'required',
            'basic_salary' => 'required|numeric',
            'allowances' => 'required|numeric',
            'deductions' => 'required|numeric',
            'payment_date' => 'required|date'
        ]);

        DB::transaction(function () use ($request) {
            // Create employee
            $employee = Employee::create([
                'emp_name' => $request->emp_name,
                'emp_code' => $request->emp_code,
                'emp_email' => $request->emp_email,
                'emp_phone' => $request->emp_phone,
                'joining_date' => $request->joining_date
            ]);

            // Calculate net salary
            $netSalary = $request->basic_salary + $request->allowances - $request->deductions;

            // Create department
            Department::create([
                'emp_id' => $employee->id,
                'dept_name' => $request->dept_name,
                'dept_head' => $request->dept_head,
                'location' => $request->location
            ]);

            // Create salary
            Salary::create([
                'emp_id' => $employee->id,
                'basic_salary' => $request->basic_salary,
                'allowances' => $request->allowances,
                'deductions' => $request->deductions,
                'net_salary' => $netSalary,
                'payment_date' => $request->payment_date
            ]);
        });

        return redirect()->route('employee.data')->with('success', 'Employee added successfully to all tables!');
    }
}