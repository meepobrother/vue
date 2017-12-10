import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'fox-footer',
    templateUrl: './fox-footer.html',
    styleUrls: ['./fox-footer.scss']
})
export class FoxFooter implements OnInit {
    @Input() items: any[] = [];
    @Output() onItem: EventEmitter<any> = new EventEmitter();
    constructor() { }
    ngOnInit() { }

    _onItem(item: any) {
        this.onItem.emit(item);
    }
}
