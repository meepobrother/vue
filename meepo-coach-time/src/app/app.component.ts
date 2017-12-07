import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ApiService } from './meepo';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'app';
  timeLen: number = 30;
  timeList: any[] = [];
  showTiaokuan: boolean = false;

  widget: any = {
    content: 'content',
    lastDate: new Date,
    selected: []
  };

  constructor(
    public api: ApiService
  ) { }

  ngOnInit() {
    this.init();
  }

  onSelect(e: any) {
    console.log(e);
  }

  init() {
    const url = this.api.getUrl('coach.time', {}, false);
    console.log(url);
  }

}
