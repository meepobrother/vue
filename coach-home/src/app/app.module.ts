import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';


import { AppComponent } from './app.component';


import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { FoxModule } from './meepo-fox';
import { AppCoach, AppField, AppStar, AppSetting, AppJoin, AppCitys } from './components';
import { CoachService } from 'meepo-runnerpro';

@NgModule({
  declarations: [
    AppComponent,
    AppCoach, AppField, AppStar, AppSetting, AppJoin, AppCitys
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpClientModule,
    FoxModule
  ],
  providers: [
    CoachService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
