import { Component, OnInit } from '@angular/core';
import { ApiService } from 'meepo-fox';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'app';

  constructor(
    public api: ApiService
  ) { }

  ngOnInit() {
    this.getList();
  }

  getList() {
    let url = this.api.getUrl('coach_time', {}, false);
    this.api.get(url);
  }
}
