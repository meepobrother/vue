import { Directive, OnInit, HostListener, Input, Output, EventEmitter } from '@angular/core';
import * as weui from 'weui.js';
import { ApiService } from '../util/api';

@Directive({
    selector: '[foxPickerTimeLen]',
})
export class FoxPickerTimeLen implements OnInit {
    times: any[] = [];
    @Input() foxPickerTimeLen: any;
    @Output() foxPickerTimeLenChange: EventEmitter<any> = new EventEmitter();
    @HostListener('click', ['$event'])
    onclick() {
        this.picker();
    }

    constructor(
        public api: ApiService
    ) { }

    ngOnInit() {
        const times = [];
        const _now = new Date();
        const year = _now.getFullYear();
        const month = _now.getFullYear();
        const day = _now.getFullYear();

        for (let i = 0; i < 8 * 60; i += 15) {
            times.push({
                label: `${i}分钟(${i/60}小时)`,
                value: i
            });
        }
        this.times = times;
    }

    picker() {
        weui.picker(this.times, {
            onConfirm: (result) => {
                this.foxPickerTimeLen = result[0];
                this.foxPickerTimeLenChange.emit(result[0]);
            }
        });
    }

}
