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
  tab: any = {};

  constructor(
    public coach$: CoachService
  ) { }

  ngOnInit() {
    this.coach$.onInit();
  }

  selectTabs(e: any) {
    this.tab = e;
    this.coach$.selectTabs(e);
  }

  onHeader(type) {
    if (type === 'post') {
      this.coach$.showJoin = true;
    }
    if (type === 'my') {
      location.href = this.coach$.api.getUrl('coach_my', {});
    }
    if (type === 'city') {
      this.coach$.showCitys = true;
    }
  }
}
