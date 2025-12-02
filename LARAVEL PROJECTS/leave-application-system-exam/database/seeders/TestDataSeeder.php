<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Models\EmployeeLeaveQuota;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        $hr = Department::create(['DepartmentName' => 'HR', 'IsActive' => true]);
        $it = Department::create(['DepartmentName' => 'IT', 'IsActive' => true]);
        $finance = Department::create(['DepartmentName' => 'Finance', 'IsActive' => true]);
        $sales = Department::create(['DepartmentName' => 'Sales', 'IsActive' => true]);
        
        $medical = LeaveType::create(['LeaveTypeCode' => 'ML', 'LeaveTypeName' => 'Medical Leave', 'IsPaidLeave' => true, 'IsActive' => true]);
        $casual = LeaveType::create(['LeaveTypeCode' => 'CL', 'LeaveTypeName' => 'Casual Leave', 'IsPaidLeave' => true, 'IsActive' => true]);
        $earned = LeaveType::create(['LeaveTypeCode' => 'EL', 'LeaveTypeName' => 'Earned Leave', 'IsPaidLeave' => true, 'IsActive' => true]);
        $maternity = LeaveType::create(['LeaveTypeCode' => 'MAT', 'LeaveTypeName' => 'Maternity Leave', 'IsPaidLeave' => true, 'IsActive' => true]);
        
        
        // IT Department Employees
        $suprabha = Employee::create([
            'EmployeeCode' => 'EMP001',
            'FirstName' => 'Suprabha',
            'LastName' => 'Panigrahi',
            'DepartmentId' => $it->DepartmentId,
            'DateOfJoining' => '2023-01-15',
            'IsActive' => true
        ]);
        
        $asha = Employee::create([
            'EmployeeCode' => 'EMP002',
            'FirstName' => 'Asha',
            'LastName' => 'Sahu',
            'DepartmentId' => $it->DepartmentId,
            'DateOfJoining' => '2023-03-20',
            'IsActive' => true
        ]);
        
        $ipsita = Employee::create([
            'EmployeeCode' => 'EMP003',
            'FirstName' => 'Ipsita',
            'LastName' => 'Mohanty',
            'DepartmentId' => $it->DepartmentId,
            'DateOfJoining' => '2023-06-10',
            'IsActive' => true
        ]);
        
        // HR Department Employees
        $sarah = Employee::create([
            'EmployeeCode' => 'EMP004',
            'FirstName' => 'Sarah',
            'LastName' => 'Williams',
            'DepartmentId' => $hr->DepartmentId,
            'DateOfJoining' => '2023-02-28',
            'IsActive' => true
        ]);
        
        $ram = Employee::create([
            'EmployeeCode' => 'EMP005',
            'FirstName' => 'Ram',
            'LastName' => 'Prasad',
            'DepartmentId' => $hr->DepartmentId,
            'DateOfJoining' => '2023-04-15',
            'IsActive' => false 
        ]);
        
        // Finance Department Employees
        $amit = Employee::create([
            'EmployeeCode' => 'EMP006',
            'FirstName' => 'Amit',
            'LastName' => 'Behera',
            'DepartmentId' => $finance->DepartmentId,
            'DateOfJoining' => '2023-05-22',
            'IsActive' => true
        ]);
        
        $subhakanta = Employee::create([
            'EmployeeCode' => 'EMP007',
            'FirstName' => 'Subhakanta',
            'LastName' => 'Panda',
            'DepartmentId' => $finance->DepartmentId,
            'DateOfJoining' => '2023-08-30',
            'IsActive' => true
        ]);
        
        // Sales Department Employees
        $lisa = Employee::create([
            'EmployeeCode' => 'EMP008',
            'FirstName' => 'Lisa',
            'LastName' => 'Das',
            'DepartmentId' => $sales->DepartmentId,
            'DateOfJoining' => '2023-07-12',
            'IsActive' => true
        ]);
        
        $rajesh = Employee::create([
            'EmployeeCode' => 'EMP009',
            'FirstName' => 'Rajesh',
            'LastName' => 'Nayak',
            'DepartmentId' => $sales->DepartmentId,
            'DateOfJoining' => '2023-09-05',
            'IsActive' => true
        ]);
        
        $currentYear = date('Y');
        
        //check who can take how much leave
        // Suprabha Panigrahi (EMP001) - IT Department
        EmployeeLeaveQuota::create([
            'EmployeeId' => $suprabha->EmployeeId,
            'LeaveTypeId' => $medical->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 10,
            'TotalUsed' => 8 //has taken two medical leave so it shouldnot allow anymore 
        ]);
        
        EmployeeLeaveQuota::create([
            'EmployeeId' => $suprabha->EmployeeId,
            'LeaveTypeId' => $casual->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 12,
            'TotalUsed' => 12 
        ]);
        
        EmployeeLeaveQuota::create([
            'EmployeeId' => $suprabha->EmployeeId,
            'LeaveTypeId' => $earned->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 15,
            'TotalUsed' => 5
        ]);
        
        // Asha Sahu (EMP002) - IT Department
        EmployeeLeaveQuota::create([
            'EmployeeId' => $asha->EmployeeId,
            'LeaveTypeId' => $medical->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 10,
            'TotalUsed' => 2
        ]);
        
        EmployeeLeaveQuota::create([
            'EmployeeId' => $asha->EmployeeId,
            'LeaveTypeId' => $casual->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 12,
            'TotalUsed' => 4
        ]);
        
        // Ipsita Mohanty (EMP003) - IT Department
        EmployeeLeaveQuota::create([
            'EmployeeId' => $ipsita->EmployeeId,
            'LeaveTypeId' => $medical->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 10,
            'TotalUsed' => 3
        ]);
        
        EmployeeLeaveQuota::create([
            'EmployeeId' => $ipsita->EmployeeId,
            'LeaveTypeId' => $casual->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 12,
            'TotalUsed' => 12 // No balance
        ]);
        
        EmployeeLeaveQuota::create([
            'EmployeeId' => $ipsita->EmployeeId,
            'LeaveTypeId' => $earned->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 15,
            'TotalUsed' => 0
        ]);
        
        // Sarah Williams (EMP004) - HR Department
        EmployeeLeaveQuota::create([
            'EmployeeId' => $sarah->EmployeeId,
            'LeaveTypeId' => $medical->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 10,
            'TotalUsed' => 1
        ]);
        
        EmployeeLeaveQuota::create([
            'EmployeeId' => $sarah->EmployeeId,
            'LeaveTypeId' => $maternity->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 180,
            'TotalUsed' => 90
        ]);
        
        // Amit Behera (EMP006) - Finance Department
        EmployeeLeaveQuota::create([
            'EmployeeId' => $amit->EmployeeId,
            'LeaveTypeId' => $medical->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 10,
            'TotalUsed' => 0
        ]);
        
        EmployeeLeaveQuota::create([
            'EmployeeId' => $amit->EmployeeId,
            'LeaveTypeId' => $earned->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 15,
            'TotalUsed' => 0
        ]);
        
        // Subhakanta Panda (EMP007) - Finance Department
        EmployeeLeaveQuota::create([
            'EmployeeId' => $subhakanta->EmployeeId,
            'LeaveTypeId' => $medical->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 10,
            'TotalUsed' => 6
        ]);
        
        EmployeeLeaveQuota::create([
            'EmployeeId' => $subhakanta->EmployeeId,
            'LeaveTypeId' => $casual->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 12,
            'TotalUsed' => 10
        ]);
        
        // Lisa Das (EMP008) - Sales Department
        EmployeeLeaveQuota::create([
            'EmployeeId' => $lisa->EmployeeId,
            'LeaveTypeId' => $medical->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 10,
            'TotalUsed' => 0
        ]);
        
        EmployeeLeaveQuota::create([
            'EmployeeId' => $lisa->EmployeeId,
            'LeaveTypeId' => $casual->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 12,
            'TotalUsed' => 3
        ]);
        
        // Rajesh Nayak (EMP009) - Sales Department
        EmployeeLeaveQuota::create([
            'EmployeeId' => $rajesh->EmployeeId,
            'LeaveTypeId' => $medical->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 10,
            'TotalUsed' => 9 // Only 1 day left
        ]);
        
        EmployeeLeaveQuota::create([
            'EmployeeId' => $rajesh->EmployeeId,
            'LeaveTypeId' => $earned->LeaveTypeId,
            'LeaveYear' => $currentYear,
            'TotalAllocated' => 15,
            'TotalUsed' => 12
        ]);
        
        // ===== HOLIDAYS =====
        Holiday::create([
            'HolidayDate' => date('Y-m-15'),
            'Description' => 'Republic Day'
        ]);
        
        Holiday::create([
            'HolidayDate' => date('Y-m-26'),
            'Description' => 'Independence Day'
        ]);
        
        
        // Suprabha has an approved medical leave
        LeaveApplication::create([
            'EmployeeId' => $suprabha->EmployeeId,
            'LeaveTypeId' => $medical->LeaveTypeId,
            'FromDate' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'ToDate' => Carbon::now()->addDays(9)->format('Y-m-d'),
            'TotalDays' => 3,
            'Reason' => 'Doctor appointment',
            'Status' => 'Approved',
            'AppliedOn' => Carbon::now()->subDays(3)
        ]);
        
        // Asha has a pending casual leave
        LeaveApplication::create([
            'EmployeeId' => $asha->EmployeeId,
            'LeaveTypeId' => $casual->LeaveTypeId,
            'FromDate' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'ToDate' => Carbon::now()->addDays(6)->format('Y-m-d'),
            'TotalDays' => 2,
            'Reason' => 'Family function',
            'Status' => 'Pending',
            'AppliedOn' => Carbon::now()->subDays(1)
        ]);
        
        // Sarah has maternity leave
        LeaveApplication::create([
            'EmployeeId' => $sarah->EmployeeId,
            'LeaveTypeId' => $maternity->LeaveTypeId,
            'FromDate' => Carbon::now()->subDays(30)->format('Y-m-d'),
            'ToDate' => Carbon::now()->addDays(150)->format('Y-m-d'),
            'TotalDays' => 180,
            'Reason' => 'Maternity leave',
            'Status' => 'Approved',
            'AppliedOn' => Carbon::now()->subDays(45)
        ]);
        
        // Subhakanta has an approved leave
        LeaveApplication::create([
            'EmployeeId' => $subhakanta->EmployeeId,
            'LeaveTypeId' => $medical->LeaveTypeId,
            'FromDate' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'ToDate' => Carbon::now()->addDays(12)->format('Y-m-d'),
            'TotalDays' => 3,
            'Reason' => 'Health checkup',
            'Status' => 'Approved',
            'AppliedOn' => Carbon::now()->subDays(5)
        ]);
    }
}