import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';


import { AppComponent } from './app.component';

import { FoxModule } from './meepo/fox.module';

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    FoxModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
