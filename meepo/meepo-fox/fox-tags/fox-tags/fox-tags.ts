import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'fox-tags',
    template: `
    <div style="position: relative;">
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
        items: []
    };

    @Input()
    set items(val: any[]) {
        if (val) {
            this.widget.items = val;
        }
        if (this.widget.items && this.widget.items.length > 0) {
            this.widget.items[0].active = true;
            this.select(this.widget.items[0]);
        }
    }
    get items() {
        return this.widget.items;
    }

    @Input() isMuilt: boolean = false;

    @Output() onSelect: EventEmitter<any> = new EventEmitter();

    constructor() { }

    ngOnInit() {
        console.log(this.widget);
    }

    select(item: any) {
        if (!this.isMuilt) {
            this.widget.items.map((res: any) => {
                res.active = false;
            });
        }
        item.active = !item.active;
        this.onSelect.emit(item);
    }
}
