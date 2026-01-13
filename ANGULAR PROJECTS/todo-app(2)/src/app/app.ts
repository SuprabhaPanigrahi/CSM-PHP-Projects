import { Component, signal } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.html',
  standalone: false,
  styleUrl: './app.css'
})
export class App {
   productList =[{id:1,name:'Laptop',price:45000},
              {id:2,name:'Mobile',price:25000},
              {id:3,name:'Tablet',price:15000},
              {id:4,name:'Smart Watch',price:8000}];
}
