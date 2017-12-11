import { OnInit, EventEmitter } from '@angular/core';
export declare class FoxTextarea implements OnInit {
    model: string;
    modelChange: EventEmitter<any>;
    title: string;
    placeholder: string;
    max: number;
    constructor();
    ngOnInit(): void;
    _change(): void;
}
