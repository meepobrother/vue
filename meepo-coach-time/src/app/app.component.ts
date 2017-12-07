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
  timeList: any[] = [];
  showTiaokuan: boolean = false;

  widget: any = {
    content: 'content',
    lastDate: new Date,
    selected: [],
    max: 200,
    loading: false,
    action: 'pay',
    time: {
      start: {
        hour: 7,
        minute: 0
      },
      end: {
        hour: 22,
        minute: 0
      }
    }
  };

  coach: any = {
    avatar: 'http://meepo.com.cn/addons/imeepos_runnerpro/icon.jpg',
    title: '同城预约',
    desc: '同城预约专注即时同城快递，提供10分钟上门59分钟送达全城的同城快递及跑腿服务。同城快递找小明跑腿，7*24提供安全、高效、便捷的高端服务！小明跑腿，让生活更高效！',
    fee: 10,
    timeLen: 30,
    id: 1
  };

  form: any = {
    desc: '',
    time: [],
    id: 1
  }

  constructor(
    public api: ApiService
  ) { }

  onTextareaChange() {
    if (this.form.desc.length > this.widget.max) {
      this.form.desc = this.form.desc.slice(0, 200);
    }
  }

  ngOnInit() {
    this.init();
  }

  onSelect(e: any) {
    if (e.add) {
      this.form.time.push(e);
    } else {
      let index = this.form.time.indexOf(e);
      this.form.time.splice(index, 1);
    }
  }

  init() {
    const url = this.api.getUrl('coach_time', { id: this.coach.id, act: 'detail' }, false);
  }

  post() {
    this.widget.loading = true;
    if (this.widget.action === 'pay') {
      let url = this.api.getUrl('coach_time', { id: this.coach.id, act: 'create' }, false);
      this.form.time.map(time => {
        let _date = new Date(time.year, time.month - 1, time.day, time.hour, time.minute);
        time.val = this.getNowFormatDate(_date, 'yyyy-MM-dd hh:mm');
      });
      this.api.post(url, this.form).subscribe((res: any) => {
        setTimeout(() => {
          this.widget.loading = false;
        }, 1000);
        let re_url = this.api.getUrl('pay', { tid: res.tid }, false);
        window.location.href = re_url;
      });
    }
  }

  getNowFormatDate(date, fmt) {
    let o = {
      "M+": date.getMonth() + 1, //月份 
      "d+": date.getDate(), //日 
      "h+": date.getHours(), //小时 
      "m+": date.getMinutes(), //分 
      "s+": date.getSeconds(), //秒 
      "q+": Math.floor((date.getMonth() + 3) / 3), //季度 
      "S": date.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
      if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
  }

}
