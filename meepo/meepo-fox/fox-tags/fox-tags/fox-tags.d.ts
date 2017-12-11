import { OnInit, EventEmitter } from '@angular/core';
export declare class FoxTags implements OnInit {
    widget: any;
    items: any[];
    isMuilt: boolean;
    onSelect: EventEmitter<any>;
    constructor();
    ngOnInit(): void;
    select(item: any): void;
}
