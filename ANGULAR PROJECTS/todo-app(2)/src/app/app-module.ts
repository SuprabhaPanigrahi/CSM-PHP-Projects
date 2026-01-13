import { NgModule, provideBrowserGlobalErrorListeners } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing-module';
import { App } from './app';
import { Todo } from './components/todo/todo';
import { Notification } from './components/notification/notification';
import { UserForm } from './components/user-form/user-form';
import { ProductList } from './components/product-list/product-list';
import { Employee } from './components/employee/employee';
import { FormsModule } from '@angular/forms';

@NgModule({
  declarations: [
    App,
    Todo,
    Notification,
    UserForm,
    ProductList,
    Employee
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule
  ],
  providers: [
    provideBrowserGlobalErrorListeners()
  ],
  bootstrap: [App]
})
export class AppModule { }
