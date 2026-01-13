import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  standalone: true,
  templateUrl: './app.html',
})
export class App {
    boxColor: string = 'red';
    changeColor(color: string){
      this.boxColor = color;
    }
}
