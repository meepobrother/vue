import { OnInit, EventEmitter } from '@angular/core';
export declare class FoxDialog implements OnInit {
    onClose: EventEmitter<any>;
    constructor();
    ngOnInit(): void;
    close(): void;
}
