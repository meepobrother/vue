import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'fox-main',
    templateUrl: 'fox-main.html',
    styleUrls: ['./fox-main.scss']
})
export class FoxMain implements OnInit {
    @Input() widget: any = {
        avatar: 'https://meepo.com.cn/addons/imeepos_runnerpro/icon.jpg',
        title: '同城预约',
        city: '杭州',
        role: '已通过实名认证',
        hasCollect: false
    };
    constructor() { }
    ngOnInit() { }

    collect() {
        this.widget.hasCollect = !this.widget.hasCollect;
    }
}
