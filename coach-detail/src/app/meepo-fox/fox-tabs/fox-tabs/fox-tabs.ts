import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'fox-tabs',
    templateUrl: './fox-tabs.html',
    styleUrls: ['./fox-tabs.scss']
})
export class FoxTabs implements OnInit {
    @Input() items: any[] = [];

    @Output() onSelect: EventEmitter<any> = new EventEmitter();
    constructor() { }

    ngOnInit() {
        this.items.map(res => {
            if (res.active) {
                this._select(res);
            }
        });
    }

    _select(item: any) {
        this.items.map(res => {
            res.active = false;
        });
        item.active = !item.active;
        this.onSelect.emit(item);
    }
}
