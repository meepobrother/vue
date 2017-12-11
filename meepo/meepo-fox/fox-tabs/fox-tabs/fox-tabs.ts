import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'fox-tabs',
    templateUrl: './fox-tabs.html',
    styleUrls: ['./fox-tabs.scss']
})
export class FoxTabs implements OnInit {
    @Input() items: any[] = [];
    @Input() roles: string[] = ['member'];
    @Output() onSelect: EventEmitter<any> = new EventEmitter();
    constructor() { }

    ngOnInit() {
        const delIds: any[] = [];
        this.items.map((res, index) => {
            if (res.active) {
                this._select(res);
            }
            if (this.roles.indexOf(res.role) === -1) {
                delIds.push(res);
            }
        });
        delIds.map(item => {
            const index = this.items.indexOf(item);
            this.items.splice(index, 1);
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
