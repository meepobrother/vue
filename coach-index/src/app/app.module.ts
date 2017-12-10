import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';


import { AppComponent } from './app.component';

import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { FoxModule } from './meepo-fox';
import { AppCoach, AppField, AppStar, AppSetting, AppJoin, AppCitys } from './components';
import { CoachService } from './components/coach.service';

@NgModule({
  declarations: [
    AppComponent,
    AppCoach, AppField, AppStar, AppSetting, AppJoin, AppCitys
  ],
  imports: [
    BrowserModule,
    FoxModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [
    CoachService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
