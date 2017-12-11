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
  widget: any = {
    footer: this.coach.footer,
    advs: [{
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/001.jpg'
    }, {
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/002.jpg'
    }, {
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/003.jpg'
    }],
    items: [{
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/002.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/001.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/003.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/002.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/001.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/003.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/002.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/001.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/003.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/002.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/001.jpg'
    }, {
      title: '测试',
      image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/003.jpg'
    },],
    cubes: [
      {
        title: '找服务',
        desc: '服务上门，预约附近服务人员！',
        image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/002.jpg',
        link: this.coach.api.getUrl('coach_index', {})
      },
      {
        title: '发任务',
        desc: '有需求，发布悬赏，全网抢单！',
        image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/001.jpg',
        link: this.coach.api.getUrl('index', { m: 'imeepos_runner' })
      }
    ]
  };
  title = 'app';

  list: any[] = [];


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

  onFooterItem(item: any) {
    if (item.link) {
      window.location.href = item.link;
    }
  }

  onCubeItem(item: any) {
    if (item.link) {
      window.location.href = item.link;
    }
  }
}
