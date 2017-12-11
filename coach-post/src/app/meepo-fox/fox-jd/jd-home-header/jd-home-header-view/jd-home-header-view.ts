import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'jd-home-header-view',
    templateUrl: './jd-home-header-view.html',
    styleUrls: ['./jd-home-header-view.scss']
})
export class JdHomeHeaderView implements OnInit {
    @Input() widget: any = {
        containerStyle: { margin: '0px' },
        info: {}
    };

    @Output() onAccount: EventEmitter<any> = new EventEmitter();
    constructor() { }
    ngOnInit() {
        this.widget.info['nickname'] = this.widget.info['nickname'] || '昵称';
        this.widget.info['mobile'] = this.widget.info['mobile'] || '电话未知';
        this.widget.info['tag'] = this.widget.info['tag'] || '标签';
        this.widget.info['desc'] = this.widget.info['desc'] || '用户等级';
    }

    _onAccount() {
        this.onAccount.emit('');
    }
}
