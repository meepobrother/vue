import { OnInit, EventEmitter } from '@angular/core';
export declare class FoxHeader implements OnInit {
    widget: any;
    onClick: EventEmitter<any>;
    constructor();
    ngOnInit(): void;
    _onClick(type: string): void;
}
