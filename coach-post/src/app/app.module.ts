import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';


import { AppComponent } from './app.component';

import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import {
  MeepoComponentsModule,
  FoxModule, CoachService
} from 'meepo-runnerpro';

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpClientModule,
    FoxModule,
    MeepoComponentsModule
  ],
  providers: [
    CoachService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
