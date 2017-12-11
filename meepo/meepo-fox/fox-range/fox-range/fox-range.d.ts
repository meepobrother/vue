import { OnInit, EventEmitter } from '@angular/core';
export declare class FoxRange implements OnInit {
    model: number;
    modelChange: EventEmitter<any>;
    step: number;
    max: number;
    constructor();
    ngOnInit(): void;
    onChange(): void;
}
