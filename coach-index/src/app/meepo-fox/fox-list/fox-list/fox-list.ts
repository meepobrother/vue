import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'fox-list',
    templateUrl: './fox-list.html',
    styleUrls: ['./fox-list.scss']
})
export class FoxList implements OnInit {
    @Input() items: any[] = [];
    @Output() onItem: EventEmitter<any> = new EventEmitter();
    constructor() { }
    ngOnInit() { }

    _onItem(item: any) {
        this.onItem.emit(item);
    }
}
