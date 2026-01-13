import { Component } from '@angular/core';

@Component({
  selector: 'app-notification',
  standalone: false,
  template: `<h1>{{title}}</h1> 
  <p class='c1'>This is Notification Component</p>
  <p> It is used to show notifications to the user.</p>
  <h4>{{4+5}}</h4>
  `,
  styles: [`h1 { color: blue; } 
  p{ font-size: 16px; color: green;  } .c1{
    background-color: yellow;
    color:red;
  }`]
})
export class Notification {
 title = 'NOTIFICATION COMPONENT';
}
