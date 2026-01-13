import { Component,OnInit } from '@angular/core';

@Component({
  selector: 'app-employee',
  standalone: false,
  templateUrl: './employee.html',
  styleUrl: './employee.css',
})
export class Employee {
  employees:EmployeeType[]=[];
  employeeObj:EmployeeType={
    name:'',
    email:'',
    phone:''
  };
  ngOnInit(): void {
    const empData = localStorage.getItem("employees");
    if(empData){
      this.employees = JSON.parse(empData);
    }
  }
  // name:string ='john';
  // email:string='';
  // phone:string='';
  // updateName(name: string): void {
  //   alert("name changed to "+name);
  //   this.name = name;
  // }
  save(){
    // alert("Employee details saved:\nName: "+this.name+"\nEmail: "+this.email+"\nPhone: "+this.phone);
    // let emp :EmployeeType={
    //   name:this.name,
    //   email:this.email,
    //   phone:this.phone
    // };
     this.employees.push({...this.employeeObj});
     localStorage.setItem("employees",JSON.stringify(this.employees));
     alert("Employee details saved successfully!");
     this.employeeObj.name='';
     this.employeeObj.email='';
     this.employeeObj.phone='';
  }
}
interface EmployeeType {
  name: string;
  email: string;
  phone: string;
}