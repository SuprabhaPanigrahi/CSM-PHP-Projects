import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-product-list',
  standalone: false,
  templateUrl: './product-list.html',
  styleUrl: './product-list.css',
})
export class ProductList {
  @Input() products:any=[]
  colspanCount:number=3;
  applyClass:string='tbl font-red';
  headingStyle:string="color:rgb(223, 209, 209);background-color: green;";
  updateColSPan(value:number){
    this.applyClass='tbl font-red back-colr';
    this.colspanCount = value;
  }
  
}
