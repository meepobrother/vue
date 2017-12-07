import { Component, OnInit, Input, EventEmitter, Output } from '@angular/core';

@Component({
    selector: 'fox-calendar',
    template: `
    <div class="fox-calendar-detail" id="fox-calendar-container">
        <div class="fox-calendar-toolbar" id="fox-calendar-toolbar">
            <button class="fox-calendar-cancel" id="fox-calendar-cancel">
                取消
            </button>
            <button class="fox-calendar-set" id="fox-calendar-set">
                选取
            </button>
        </div>
        <div class="fox-calendar-selector" *ngIf="hasSelector">
            <div class="fox-calendar-selector-left">
                <fox-icon id="btn_prev_month" (click)="createPrevMonth()" icon="icon-left-nav"></fox-icon>
                <label>{{month}}月</label>
                <fox-icon id="btn_next_month" (click)="createNextMonth()" icon="icon-right-nav"></fox-icon>
            </div>
            <div class="fox-calendar-selector-right">
                <fox-icon id="btn_prev_year" [class.disable]="btnPrevYearDisable" icon="icon-left-nav"></fox-icon>
                <label>{{year}}年</label>
                <fox-icon id="btn_next_year" icon="icon-right-nav"></fox-icon>
            </div>
        </div>
        <div class="fox-calendar-week-header" *ngIf="hasHeader">
            <div class="fox-calendar-week-item">
                日
            </div>
            <div class="fox-calendar-week-item">
                一
            </div>
            <div class="fox-calendar-week-item">
                二
            </div>
            <div class="fox-calendar-week-item">
                三
            </div>
            <div class="fox-calendar-week-item">
                四
            </div>
            <div class="fox-calendar-week-item">
                五
            </div>
            <div class="fox-calendar-week-item">
                六
            </div>
        </div>
        <div class="fox-calendar-month-detail" *ngIf="!isRow">
            <div class="fox-calendar-row" *ngFor="let row of list">
                <div class="fox-calendar-col" (click)="select(col)" [ngClass]="col.type" *ngFor="let col of row">
                    <span class="fox-calendar-col-inner"> {{col.val}} </span>
                </div>
            </div>
        </div>
        <div class="fox-calendar-month-detail">
            <div class="fox-calendar-row" style="overflow: auto;">
                <div class="fox-calendar-col" style="min-width: 3em;" (click)="select(col)" [ngClass]="col.type" *ngFor="let col of list">
                    <span class="fox-calendar-col-inner"> {{col.val}} </span>
                </div>
            </div>

            <div class="fox-calendar-time-list">
                <ul>
                    <li *ngFor="let item of timeList">{{item.val}}</li>
                </ul>
            </div>
        </div>
    </div>
    `,
    styleUrls: ['./fox-calendar.scss']
})
export class FoxCalendar implements OnInit {
    _month: number = 1;
    @Input()
    set month(val: number) {
        this._month = val;
    }
    get month() {
        return this._month;
    }
    _year: number = 2017;
    @Input()
    set year(val: number) {
        this._year = val;
    }
    get year() {
        return this._year;
    }
    _day: number = 0;
    @Input()
    set day(val: number) {
        this._day = val;
    }
    get day() {
        return this._day;
    }
    _lastDate: Date = new Date();
    @Input()
    set lastDate(val: Date) {
        this._lastDate = val;
    }
    get lastDate() {
        return this._lastDate;
    }

    _timeLen: number = 30;
    timeList: any[] = [];
    @Input()
    set timeLen(val: number) {
        this._timeLen = val;
        this.createTimeList();
    }
    get timeLen() {
        return this._timeLen;
    }

    @Input() hasSelector: boolean = false;
    @Input() hasHeader: boolean = false;
    @Input() isRow: boolean = true;
    @Input() multi: boolean = false;
    @Output() onSelect: EventEmitter<any> = new EventEmitter();

    list: any[] = [];
    minDate: any;
    maxDate: any;
    btnPrevYearDisable: boolean = false;
    btnPrevMonthDisable: boolean = false;
    btnNextYearDisable: boolean = false;
    btnNextMonthDisable: boolean = false;

    constructor() { }

    ngOnInit() {
        this._month = this.lastDate.getMonth() + 1;
        this._year = this.lastDate.getFullYear();
        this._day = this.lastDate.getDay();

        const now = new Date();
        const now_str = `${now.getFullYear()}-${now.getMonth() + 1}-${now.getDate()}`;
        this.minDate = new Date(now_str);
        console.log(this.minDate);
        this.udpate();
        setTimeout(() => {
            this.createTimeList();
        }, 300);
    }

    createTimeList() {
        const _now = new Date();
        const start_str = `${this.year}-${this.month}-${this.day} 06:00`;
        console.log(start_str);
        const start_int = new Date(start_str).getTime();
        const end_str = `${this.year}-${this.month}-${this.day} 22:00`;
        const end_int = new Date(end_str).getTime();
        const time_len = this.timeLen * 60 * 1000;
        this.timeList = [];
        let now_time = start_int;
        console.log(now_time);
        do {
            now_time += time_len;
            const __time = new Date();
            __time.setTime(now_time);
            let __minute: any = __time.getMinutes();
            __minute = __minute > 10 ? __minute : '0' + __minute;
            console.log(__minute);
            this.timeList.push({
                val: `${__time.getHours()}:${__minute}`
            });
        } while (now_time < end_int);
        console.log(this.timeList);
    }

    setMinDate(val: Date = new Date()) {
        this.minDate = val;
        if (val && (val.getMonth() + 1 > this._month || val.getFullYear() > this._year)) {
            this._month = val.getMonth() + 1;
            this._year = val.getFullYear();
            this.udpate();
        } else {
            this.udpate();
        }
    }

    setMaxDate(val: Date = new Date()) {
        this.maxDate = val;
        if (val && (val.getMonth() + 1 < this._month || val.getFullYear() < this._year)) {
            this._month = val.getMonth() + 1;
            this._year = val.getFullYear();
            this.udpate();
        } else {
            this.udpate();
        }
    }

    canUse(type: string = '') {
        return {
            active: type.indexOf('active') !== -1,
            prev: type.indexOf('prev') !== -1,
            normal: type.indexOf('normal') !== -1,
            disable: type.indexOf('disable') !== -1,
            next: type.indexOf('next') !== -1
        };
    }

    select(item: any) {
        if (this.isRow) {
            if (!this.multi) {
                this.list.map(res => {
                    res.type = res.type.trim();
                    const check = this.canUse(res.type);
                    if (check.active) {
                        if (res.back) {
                            res.type = res.back;
                        } else {
                            res.type = 'normal';
                        }
                    }
                });
            }
        } else {
            if (!this.multi) {
                this.list.map(list => {
                    list.map(res => {
                        res.type = res.type.trim();
                        const check = this.canUse(res.type);
                        if (check.active) {
                            if (res.back) {
                                res.type = res.back;
                            } else {
                                res.type = 'normal';
                            }
                        }
                    });
                });
            }
        }
        item.type = item.type.trim();
        if (item.type === 'normal') {
            item.back = item.type;
            item.type = 'active';
            console.log('选中', item);
            this.onSelect.emit(item);
        } else if (item.type === 'active') {
            item.type = item.back;
            console.log('取消', item);
            this.onSelect.emit(item);
        } else if (item.type === 'next') {
            item.back = item.type;
            item.type = 'active';
            console.log('选择', item);
            this.onSelect.emit(item);
        }
        this.day = item.val;
        this.createTimeList();
    }

    udpate() {
        if (this.isRow) {
            this.create2();
        } else {
            this.create();
        }
        this.createTimeList();
    }

    create2() {
        this.updateSelect(this._year, this._month);
        this.list = [];
        for (let i = 1; i <= this.daysInMonth(this.month, this.year); i++) {
            let active = "",
                type = "normal";
            if (
                this.lastDate
                && this.lastDate.getFullYear() === this.year
                && this.lastDate.getMonth() + 1 === this.month
                && this.lastDate.getDate() === i) {
                active = "active";
            }
            if (!this.isInRange(new Date(this.year, this.month - 1, i))) {
                type = "disable";
            }
            this.list.push({
                "val": i,
                "type": type + " " + active
            });
        }
        const count = this.list.length;
        for (let i = count, j = 1; i < 42; i++ , j++) {
            this.list.push({
                "val": j,
                "type": "next"
            });
        }
    }

    create() {
        this.updateSelect(this._year, this._month);
        this.list = [];
        this.list.push([]);
        for (let i = 0, l = this.weekInMonth(this.month, this.year), j = this.daysInMonth(this.month - 1, this.year); i < l; i++) {
            this.list[0].push({
                "val": j - (l - i - 1),
                "type": "prev"
            });
        }
        for (let i = 1; i <= this.daysInMonth(this.month, this.year); i++) {
            let active = "",
                type = "normal";
            if (this.lastDate
                && this.lastDate.getFullYear() === this.year
                && this.lastDate.getMonth() + 1 === this.month
                && this.lastDate.getDate() === i) {
                active = "active";
            }
            if (!this.isInRange(new Date(this.year, this.month - 1, i))) {
                type = "disable";
            }
            let arr = this.list[this.list.length - 1];
            if (arr.length > 6) {
                arr = [];
                this.list.push(arr);
            }
            arr.push({
                "val": i,
                "type": type + " " + active
            });
        }
        let count = 0;
        this.list.map((item: any) => {
            count += item.length;
        });
        for (let i = count, j = 1; i < 42; i++ , j++) {
            let arr = this.list[this.list.length - 1];
            if (arr.length > 6) {
                arr = [];
                this.list.push(arr);
            }
            arr.push({
                "val": j,
                "type": "next"
            });
        }
    }

    createNextMonth() {
        this._month++;
        if (this._month > 12) {
            this._month = 1;
            this._year++;
        }
        this.udpate();
    }

    createPrevMonth() {
        this._month--;
        if (this._month === 0) {
            this._month = 12;
            this._year--;
        }
        this.udpate();
    }

    createPrevYear() {
        this._year--;
        this.udpate();
    }

    createNextYear() {
        this._year++;
        this.udpate();
    }

    daysInMonth(month, year) {
        return new Date(year, month, 0).getDate();
    }

    weekInMonth(month, year) {
        return new Date(year, month - 1, 1).getDay();
    }

    isInRange(date) {
        if (this.minDate && date.getTime() < this.minDate.getTime()) {
            return false;
        } else if (this.maxDate && date.getTime() > this.maxDate.getTime()) {
            return false;
        }
        return true;
    }

    updateSelect(year, month) {
        if (this.minDate && year - 1 < this.minDate.getFullYear()) {
            this.btnPrevYearDisable = true;
        } else {
            this.btnPrevYearDisable = false;
        }
        if (this.minDate && month - 2 < this.minDate.getMonth()) {
            this.btnPrevMonthDisable = true;
        } else {
            this.btnPrevMonthDisable = false;
        }
        if (this.maxDate && year + 1 > this.maxDate.getFullYear()) {
            this.btnNextYearDisable = true;
        } else {
            this.btnNextYearDisable = false;
        }
        if (this.maxDate && month > this.maxDate.getMonth()) {
            this.btnNextMonthDisable = true;
        } else {
            this.btnNextMonthDisable = false;
        }
    }
}
