import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { AppComponent } from './app.component';
import { FoxModule } from './meepo-fox';
import { AppCoach, AppField, AppStar } from './components';
import { CoachService } from './components/coach.service';
@NgModule({
  declarations: [
    AppComponent,
    AppCoach,
    AppField,
    AppStar
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
