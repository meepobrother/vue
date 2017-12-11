import { OnInit, EventEmitter } from '@angular/core';
export declare class FoxTabs implements OnInit {
    items: any[];
    roles: string[];
    onSelect: EventEmitter<any>;
    constructor();
    ngOnInit(): void;
    _select(item: any): void;
}
