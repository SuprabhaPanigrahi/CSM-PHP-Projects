import { Component } from '@angular/core';

@Component({
  selector: 'app-user-form',
  standalone: false,
  templateUrl: './user-form.html',
  styleUrl: './user-form.css',
})
export class UserForm {
  name :string ='John Doe'
  isDisabled: boolean = true;
  changeName(){
    this.name = 'Jane Smith'
    console.log('Name changed to:', this.name);
  }
  toggle(){
    this.isDisabled = !this.isDisabled;
    console.log('isDisabled toggled to:', this.isDisabled);
  }
}
