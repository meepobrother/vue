import { Directive, OnInit, HostListener, Input, Output, EventEmitter } from '@angular/core';
import { ApiService } from '../util/api';
declare const weui: any;
@Directive({
    selector: '[foxPickerTime]',
})
export class FoxPickerTime implements OnInit {
    times: any[] = [];
    @Input() foxPickerTime: any;
    @Output() foxPickerTimeChange: EventEmitter<any> = new EventEmitter();
    @HostListener('click', ['$event'])
    onclick() {
        this.picker();
    }

    constructor(
        public api: ApiService
    ) { }

    ngOnInit() {
        const times: any[] = [];
        const _now: Date = new Date();
        const year: number = _now.getFullYear();
        const month: number = _now.getFullYear();
        const day: number = _now.getFullYear();

        for (let i = 0; i < 24; i++) {
            [0, 30].map(item => {
                const _time: Date = new Date(year, month, day, i, item);
                times.push({
                    label: this.api.formatDate(_time, 'hh:mm'),
                    value: {
                        hour: _time.getHours(),
                        minute: _time.getMinutes()
                    }
                });
            });
        }
        this.times = times;
    }

    picker() {
        weui.picker(this.times, {
            onConfirm: (result: any) => {
                this.foxPickerTime = result[0];
                this.foxPickerTimeChange.emit(this.foxPickerTime);
            }
        });
    }

}
