import { Component } from '@angular/core';

@Component({
  selector: 'app-todo',
  standalone: false,
  templateUrl: './todo.html',
  styleUrl: './todo.css',
})
export class Todo {
   tasks:string[] = ['Task1', 'Task2', 'Task3', 'Task4', 'Task5'];
      taskToEdit:string = '';
      isEdit:boolean = false;
      addTask(newTask:string):void{
         // alert(`addtask clicked=>${newTask}`);
         this.tasks.push(newTask);
         alert(`Task Added successfully`);
      }
   
      editTask(index:number):void{
        this.taskToEdit = this.tasks[index];
        this.isEdit = true;
      }
      updateTask(UpdatedTask:string):void{
         const index = this.tasks.indexOf(this.taskToEdit);
         
         console.log(index,UpdatedTask);
         if(index > -1){
            this.tasks[index] = UpdatedTask;
            this.isEdit = false;
            alert(`Task Updated successfully`);
         } 
      }
       deleteTask(index:number):void{    
         if(confirm(`Are you sure to delete this task?`))
         {
            this.tasks.splice(index,1);
            alert(`Task Deleted successfully`);
         }
         
      }
}
