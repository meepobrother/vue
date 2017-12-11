import { OnInit, EventEmitter } from '@angular/core';
export declare class FoxList implements OnInit {
    items: any[];
    onItem: EventEmitter<any>;
    constructor();
    ngOnInit(): void;
    _onItem(item: any): void;
}
