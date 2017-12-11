"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@angular/core");
let FoxCalendar = class FoxCalendar {
    constructor() {
        this._month = 1;
        this._hasSelect = [];
        this._year = 2017;
        this._day = 0;
        this._lastDate = new Date();
        this._timeLen = 30;
        this.timeList = [];
        this.hasSelector = false;
        this.hasHeader = false;
        this.isRow = true;
        this.multi = false;
        this.onSelect = new core_1.EventEmitter();
        this.daySelect = new core_1.EventEmitter();
        this.list = [];
        this.btnPrevYearDisable = false;
        this.btnPrevMonthDisable = false;
        this.btnNextYearDisable = false;
        this.btnNextMonthDisable = false;
        this._timeStart = {
            hour: 0,
            minute: 0
        };
        this._timeEnd = {
            hour: 24,
            minute: 0
        };
    }
    get hasSelect() {
        return this._hasSelect;
    }
    set hasSelect(val) {
        this._hasSelect = val;
        this.setSelect();
    }
    set month(val) {
        this._month = val;
    }
    get month() {
        return this._month;
    }
    set year(val) {
        this._year = val;
    }
    get year() {
        return this._year;
    }
    set day(val) {
        this._day = val;
    }
    get day() {
        return this._day;
    }
    get hour() {
        return this._minute;
    }
    set hour(val) {
        this._minute = val;
    }
    get minute() {
        return this._minute;
    }
    set minute(val) {
        this._minute = val;
    }
    set lastDate(val) {
        this._lastDate = val;
    }
    get lastDate() {
        return this._lastDate;
    }
    set timeLen(val) {
        this._timeLen = val;
        this.createTimeList();
    }
    get timeLen() {
        return this._timeLen;
    }
    set timeStart(val) {
        this._timeStart = val;
        this.udpate();
    }
    get timeStart() {
        return this._timeStart;
    }
    set timeEnd(val) {
        this._timeEnd = val;
        this.udpate();
    }
    get timeEnd() {
        return this._timeEnd;
    }
    ngOnInit() {
        this._month = this.lastDate.getMonth() + 1;
        this._year = this.lastDate.getFullYear();
        this._day = this.lastDate.getDate();
        const __now = new Date();
        this.minDate = new Date(__now.getFullYear(), __now.getMonth(), __now.getDate(), 0, 0);
        this.udpate();
    }
    setSelect() {
        this._hasSelect.map((select) => {
            this.timeList.map((time) => {
                if ('' + time.timeInt === '' + select.timeInt) {
                    time.disable = true;
                }
            });
        });
    }
    onTouch(e) {
        console.log('touch');
    }
    createTimeList() {
        const _now = new Date();
        const _now_int = _now.getTime();
        const start_date = new Date(this.year, this.month - 1, this.day, this.timeStart.hour, this.timeStart.minute);
        const start_int = start_date.getTime();
        const end_int = new Date(this.year, this.month - 1, this.day, this.timeEnd.hour, this.timeEnd.minute).getTime();
        const time_len = this.timeLen * 60 * 1000;
        this.timeList = [];
        let now_time = start_int;
        this.timeList.push({
            val: this.getNowFormatDate(start_date, 'hh:mm'),
            hour: start_date.getHours(),
            minute: start_date.getMinutes(),
            disable: (now_time < _now_int),
            timeInt: now_time / 1000
        });
        do {
            now_time += time_len;
            if (now_time < end_int) {
                const __time = new Date();
                __time.setTime(now_time);
                this.timeList.push({
                    val: this.getNowFormatDate(__time, 'hh:mm'),
                    hour: __time.getHours(),
                    minute: __time.getMinutes(),
                    disable: (now_time < _now_int),
                    timeInt: now_time / 1000
                });
            }
        } while (now_time + time_len < end_int);
        this.setSelect();
    }
    setMinDate(val = new Date()) {
        this.minDate = val;
        if (val && (val.getMonth() + 1 > this._month || val.getFullYear() > this._year)) {
            this._month = val.getMonth() + 1;
            this._year = val.getFullYear();
            this.udpate();
        }
        else {
            this.udpate();
        }
    }
    setMaxDate(val = new Date()) {
        this.maxDate = val;
        if (val && (val.getMonth() + 1 < this._month || val.getFullYear() < this._year)) {
            this._month = val.getMonth() + 1;
            this._year = val.getFullYear();
            this.udpate();
        }
        else {
            this.udpate();
        }
    }
    canUse(type = '') {
        return {
            active: type.indexOf('active') !== -1,
            prev: type.indexOf('prev') !== -1,
            normal: type.indexOf('normal') !== -1,
            disable: type.indexOf('disable') !== -1,
            next: type.indexOf('next') !== -1
        };
    }
    selectTime(item) {
        if (!item.disable) {
            item['active'] = !item['active'];
            this.hour = item.hour;
            this.minute = item.minute;
            const data = {
                hour: item.hour,
                minute: item.minute,
                timeInt: item.timeInt,
                add: item['active']
            };
            this.finish(data);
        }
    }
    select(item) {
        if (this.isRow) {
            if (!this.multi) {
                this.list.map(res => {
                    res.type = res.type.trim();
                    const check = this.canUse(res.type);
                    if (check.active) {
                        if (res.back) {
                            res.type = res.back;
                        }
                        else {
                            res.type = 'normal';
                        }
                    }
                });
            }
        }
        else {
            if (!this.multi) {
                this.list.map((list) => {
                    list.map((res) => {
                        res.type = res.type.trim();
                        const check = this.canUse(res.type);
                        if (check.active) {
                            if (res.back) {
                                res.type = res.back;
                            }
                            else {
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
        }
        else if (item.type === 'active') {
            item.type = item.back;
        }
        else if (item.type === 'next') {
            item.back = item.type;
            item.type = 'active';
        }
        this.day = item.val;
        this.daySelect.emit({
            year: this.year,
            month: this.month,
            day: this.day,
            add: item.type === 'active'
        });
        this.createTimeList();
    }
    finish(data) {
        const item = {
            year: this.year,
            month: this.month,
            day: this.day,
            hour: data.hour,
            minute: data.minute,
            timeInt: data.timeInt,
            add: data.add
        };
        this.onSelect.emit(item);
    }
    udpate() {
        if (this.isRow) {
            this.create2();
        }
        else {
            this.create();
        }
        this.createTimeList();
    }
    create2() {
        this.updateSelect(this._year, this._month);
        this.list = [];
        const day = this.lastDate.getDate();
        for (let i = day; i <= this.daysInMonth(this.month, this.year); i++) {
            let active = "", type = "normal";
            if (this.lastDate
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
        for (let i = count, j = 1; i < 42; i++, j++) {
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
            let active = "", type = "normal";
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
        this.list.map((item) => {
            count += item.length;
        });
        for (let i = count, j = 1; i < 42; i++, j++) {
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
        }
        else if (this.maxDate && date.getTime() > this.maxDate.getTime()) {
            return false;
        }
        return true;
    }
    updateSelect(year, month) {
        if (this.minDate && year - 1 < this.minDate.getFullYear()) {
            this.btnPrevYearDisable = true;
        }
        else {
            this.btnPrevYearDisable = false;
        }
        if (this.minDate && month - 2 < this.minDate.getMonth()) {
            this.btnPrevMonthDisable = true;
        }
        else {
            this.btnPrevMonthDisable = false;
        }
        if (this.maxDate && year + 1 > this.maxDate.getFullYear()) {
            this.btnNextYearDisable = true;
        }
        else {
            this.btnNextYearDisable = false;
        }
        if (this.maxDate && month > this.maxDate.getMonth()) {
            this.btnNextMonthDisable = true;
        }
        else {
            this.btnNextMonthDisable = false;
        }
    }
    getNowFormatDate(date, fmt) {
        const o = {
            "M+": date.getMonth() + 1,
            "d+": date.getDate(),
            "h+": date.getHours(),
            "m+": date.getMinutes(),
            "s+": date.getSeconds(),
            "q+": Math.floor((date.getMonth() + 3) / 3),
            "S": date.getMilliseconds()
        };
        if (/(y+)/.test(fmt))
            fmt = fmt.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (const k in o)
            if (new RegExp("(" + k + ")").test(fmt))
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Object),
    __metadata("design:paramtypes", [Array])
], FoxCalendar.prototype, "hasSelect", null);
__decorate([
    core_1.Input(),
    __metadata("design:type", Number),
    __metadata("design:paramtypes", [Number])
], FoxCalendar.prototype, "month", null);
__decorate([
    core_1.Input(),
    __metadata("design:type", Number),
    __metadata("design:paramtypes", [Number])
], FoxCalendar.prototype, "year", null);
__decorate([
    core_1.Input(),
    __metadata("design:type", Number),
    __metadata("design:paramtypes", [Number])
], FoxCalendar.prototype, "day", null);
__decorate([
    core_1.Input(),
    __metadata("design:type", Object),
    __metadata("design:paramtypes", [Number])
], FoxCalendar.prototype, "hour", null);
__decorate([
    core_1.Input(),
    __metadata("design:type", Object),
    __metadata("design:paramtypes", [Number])
], FoxCalendar.prototype, "minute", null);
__decorate([
    core_1.Input(),
    __metadata("design:type", Date),
    __metadata("design:paramtypes", [Date])
], FoxCalendar.prototype, "lastDate", null);
__decorate([
    core_1.Input(),
    __metadata("design:type", Number),
    __metadata("design:paramtypes", [Number])
], FoxCalendar.prototype, "timeLen", null);
__decorate([
    core_1.Input(),
    __metadata("design:type", Boolean)
], FoxCalendar.prototype, "hasSelector", void 0);
__decorate([
    core_1.Input(),
    __metadata("design:type", Boolean)
], FoxCalendar.prototype, "hasHeader", void 0);
__decorate([
    core_1.Input(),
    __metadata("design:type", Boolean)
], FoxCalendar.prototype, "isRow", void 0);
__decorate([
    core_1.Input(),
    __metadata("design:type", Boolean)
], FoxCalendar.prototype, "multi", void 0);
__decorate([
    core_1.Output(),
    __metadata("design:type", core_1.EventEmitter)
], FoxCalendar.prototype, "onSelect", void 0);
__decorate([
    core_1.Output(),
    __metadata("design:type", core_1.EventEmitter)
], FoxCalendar.prototype, "daySelect", void 0);
__decorate([
    core_1.Input(),
    __metadata("design:type", Object),
    __metadata("design:paramtypes", [Object])
], FoxCalendar.prototype, "timeStart", null);
__decorate([
    core_1.Input(),
    __metadata("design:type", Object),
    __metadata("design:paramtypes", [Object])
], FoxCalendar.prototype, "timeEnd", null);
FoxCalendar = __decorate([
    core_1.Component({
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
        <div class="fox-calendar-month-detail" *ngIf="isRow">
            <p class="weui-msg__desc">
                请选择预约日期
            </p>
            <div class="fox-calendar-row" style="overflow: auto;">
                <div class="fox-calendar-col" style="min-width: 3em;" (click)="select(col)" [ngClass]="col.type" *ngFor="let col of list">
                    <span class="fox-calendar-col-inner"> {{col.val}} </span>
                </div>
            </div>
            <p class="weui-msg__desc">
                选择预约时间及时长，每个30分钟！可选择多个！
            </p>
            <div class="fox-calendar-time-list">
                <ul>
                    <li [class.disable]="item.disable" [class.active]="item.active"
                        (click)="selectTime(item)" *ngFor="let item of timeList">{{item.val}}</li>
                </ul>
            </div>
        </div>
        <ng-content></ng-content>
    </div>
    `,
        styleUrls: ['./fox-calendar.scss']
    }),
    __metadata("design:paramtypes", [])
], FoxCalendar);
exports.FoxCalendar = FoxCalendar;
//# sourceMappingURL=fox-calendar.js.map