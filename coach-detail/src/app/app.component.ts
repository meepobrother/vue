import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { CoachService } from './components/coach.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
  encapsulation: ViewEncapsulation.None
})
export class AppComponent implements OnInit {
  title = 'app';
  tab: string;
  constructor(
    public coach$: CoachService
  ) { }

  ngOnInit() {
    this.coach$.onInit();
  }
  selectTabs(e: any) {
    this.tab = e.code;
    console.log(this.tab);
    this.coach$.selectTabs(e);
  }
}
