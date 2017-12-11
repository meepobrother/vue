import { Component, OnInit } from '@angular/core';
import { CoachService } from './components/coach.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  widget: any = {
    header: {
      containerStyle: { margin: '0px' },
      account: '我的小店',
      info: {},
      bgImg: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/bg.jpg',
      items: [{
        title: '我的余额',
        num: '0'
      }, {
        title: '我的积分',
        num: '0'
      }, {
        title: '我的信誉',
        num: '0'
      }]
    },
    order: {
      containerStyle: { margin: '0px' },
      leftTitle: '我的任务',
      rightTitle: '查看',
      items: [{
        title: '待接单',
        icon: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/001.jpg'
      }, {
        title: '进行中',
        icon: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/002.jpg'
      }, {
        title: '待确认',
        icon: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/003.jpg'
      }]
    },
    list: {
      containerStyle: { margin: '0px' },
      items: [{
        image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/001.jpg',
        title: '我的预约'
      }, {
        image: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/002.jpg',
        title: '预约我的'
      }]
    },
    footer: this.coach.footer
  };
  title = 'app';
  constructor(
    public coach: CoachService
  ) { }
  ngOnInit() {
    const url = this.coach.api.getUrl('coach_home', { act: 'get_my_info' });
    this.coach.api.get(url).subscribe(res => {
      this.widget.header.info = res;
    });
  }

  onAccount() {
    window.location.href = this.coach.api.getUrl('coach_my', {});
  }

  onFooterItem(item: any) {
    if (item.link) {
      window.location.href = item.link;
    }
  }
}
