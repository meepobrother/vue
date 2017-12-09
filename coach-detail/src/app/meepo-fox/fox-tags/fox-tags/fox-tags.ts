import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'fox-tags',
    template: `
    <div style="position: relative;min-height: 80px;">
        <div class="w_tag bor-b" [ngStyle]="widget.containerStyle">
            <a href="javascript:;"
                [ngStyle]="widget.styleObj"
                class="tab_btn marbot20"
                [class.on]="item.active"
                *ngFor="let item of widget.items"
                (click)="select(item)"
            >{{item.title}}</a>
        </div>
    </div>
    `,
    styleUrls: ['./fox-tags.scss']
})
export class FoxTags implements OnInit {
    @Input() widget: any = {
        containerStyle: { margin: 0 },
        items: [{
            title: '洗衣做饭',
            fee: '20',
            timeLen: 30
        }, {
            title: '母婴照看',
            fee: '30',
            timeLen: 30
        }, {
            title: '搬家货运',
            fee: '20',
            timeLen: 30
        }, {
            title: '家电维修',
            fee: '50',
            timeLen: 30
        }, {
            title: '私人医生',
            fee: '30',
            timeLen: 30
        }, {
            title: '开锁换锁',
            fee: '20',
            timeLen: 30
        }, {
            title: '代办跑腿',
            fee: '10',
            timeLen: 30
        }, {
            title: '足疗按摩',
            fee: '100',
            timeLen: 30
        }]
    };

    @Input()
    set items(val: any[]) {
        if (val) {
            this.widget.items = val;
        }
        this.widget.items[0].active = true;
        this.select(this.widget.items[0]);
    }
    get items() {
        return this.widget.items;
    }

    @Input() isMuilt: boolean = false;

    @Output() onSelect: EventEmitter<any> = new EventEmitter();

    constructor() { }

    ngOnInit() { }

    select(item: any) {
        if (!this.isMuilt) {
            this.widget.items.map(res => {
                res.active = false;
            });
            item.active = !item.active;
        }
        this.onSelect.emit(item);
    }
}
