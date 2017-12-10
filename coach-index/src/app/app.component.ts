import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'app';

  list: any[] = [{}, {}, {}, {}];

  cubes: any[] = [
    {
      title: '标题',
      desc: '简介',
      image: ''
    },
    {
      title: '标题',
      desc: '简介',
      image: ''
    }
  ];

  items: any[] = [{
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }, {
    title: '测试',
    image: ''
  }];
}
