import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'fox-cube',
    templateUrl: './fox-cube.html',
    styleUrls: ['./fox-cube.scss']
})
export class FoxCube implements OnInit {
    @Input() items: any[] = [];
    @Output() onItem: EventEmitter<any> = new EventEmitter();
    constructor() { }

    ngOnInit() { }

    _onItem(item: any) {
        this.onItem.emit(item);
    }
}
