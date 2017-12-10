import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'fox-header',
    templateUrl: './fox-header.html',
    styleUrls: ['./fox-header.scss']
})
export class FoxHeader implements OnInit {
    @Input() widget: any = {
        title: '找任务 找服务',
        my: '我的',
        post: '入驻',
        city: '杭州'
    };
    @Output() onClick: EventEmitter<any> = new EventEmitter();
    constructor() { }

    ngOnInit() { }

    _onClick(type: string) {
        this.onClick.emit(type);
    }
}
