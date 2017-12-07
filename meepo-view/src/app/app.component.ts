import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'app';
  timeLen: number = 30;
  timeList: any[] = [];
  onSelect(e: any) {
    console.log(e);
  }

}
