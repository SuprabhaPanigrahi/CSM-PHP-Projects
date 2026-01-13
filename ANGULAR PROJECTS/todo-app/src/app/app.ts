import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  standalone: false,
  templateUrl: './app.html',
  styleUrls: ['./app.css']
})
export class App {
  tasks: string[] = ['Task1', 'Task2', 'Task3'];
  taskInput: string = '';
  editIndex: number | null = null;

  get isEdit(): boolean {
    return this.editIndex !== null;
  }

  addTask(): void {
    if (!this.taskInput.trim()) return;
    this.tasks.push(this.taskInput);
    this.taskInput = '';
  }

  editTask(index: number): void {
    this.taskInput = this.tasks[index];
    this.editIndex = index;
  }

  updateTask(): void {
    if (this.editIndex === null || !this.taskInput.trim()) return;
    this.tasks[this.editIndex] = this.taskInput;
    this.taskInput = '';
    this.editIndex = null;
  }

  deleteTask(index: number): void {
    if (confirm('Are you sure to delete this task?')) {
      this.tasks.splice(index, 1);
    }
  }
}