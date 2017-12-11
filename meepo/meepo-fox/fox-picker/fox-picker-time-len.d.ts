import { OnInit, EventEmitter } from '@angular/core';
import { ApiService } from '../util/api';
export declare class FoxPickerTimeLen implements OnInit {
    api: ApiService;
    times: any[];
    foxPickerTimeLen: any;
    foxPickerTimeLenChange: EventEmitter<any>;
    onclick(): void;
    constructor(api: ApiService);
    ngOnInit(): void;
    picker(): void;
}
