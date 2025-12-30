// src/app/app.ts
import { Component, signal } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.html',
  styleUrls: ['./app.css'] 
})
export class App {
  taskList: string[] = [];
  title: string = "Welcome to my Task Management System";

  addTask(value: string) {
    if (value.trim()) {
      this.taskList.push(value);
    }
  }

  deleteTask(index: number) {
    if (confirm('Are you sure to delete task at index: ' + index)) {
      this.taskList.splice(index, 1);
      alert("Task deleted at index: " + index);
    }
  }
}
