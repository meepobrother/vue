import { Component } from '@angular/core';
import { CoachService } from 'meepo-runnerpro';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  widget: any = {
    header: this.coach.header,
    footer: this.coach.footer
  };
  title = 'app';

  constructor(
    public coach: CoachService
  ) { }

  onHeader(type: string) {
    console.log(type);
    if (type === 'city') {
      window.location.href = this.coach.api.getUrl('citys_select', {});
    }
    if (type === 'my') {
      window.location.href = this.coach.api.getUrl('coach_my', {});
    }
    if (type === 'post') {
      window.location.href = this.coach.api.getUrl('coach_add', {});
    }
    if (type === 'title') {
      window.location.href = this.coach.api.getUrl('coach_index', {});
    }
  }

  onFooterItem(item: any) {
    if (item.link) {
      window.location.href = item.link;
    }
  }
}
