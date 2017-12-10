import { Component, OnInit } from '@angular/core';
import { CoachService } from './components/coach.service';
import * as queryString from 'query-string';
const parsed = queryString.parse(location.search);

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'app';

  list: any[] = [];

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

  constructor(
    public coach: CoachService
  ) {

  }

  ngOnInit() {
    if (parsed.name) {
      this.coach.setCity({
        name: parsed.name,
        latitude: parsed.latitude,
        longitude: parsed.longitude
      });
    }
    this.getSkills();
  }

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

  getSkills() {
    this.coach.getSkillList().subscribe((res: any) => {
      this.list = res.list;
    });
  }

  onItem(item: any) {
    window.location.href = this.coach.api.getUrl('coach_detail', { id: item.id });
  }
}
