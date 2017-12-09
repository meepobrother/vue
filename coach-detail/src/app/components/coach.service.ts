import { Injectable } from '@angular/core';
import { ApiService } from '../meepo-fox';
import {
    defaultCoach,
    defaultWidget,
    defaultForm
} from './init.data';
@Injectable()
export class CoachService {
    coach: any = defaultCoach;
    widget: any = defaultWidget;
    form: any = defaultForm;
    day: number;
    year: number;
    month: number;
    hasSelect: any[] = [];
    timeList: any[] = [];
    showTiaokuan: boolean = false;

    hasInit: boolean = false;
    constructor(
        public api: ApiService
    ) { }

    selectTabs(e) {
        // console.log(e);
    }

    onInit(cache = true) {
        if (cache) {
            if (!this.hasInit) {
                this.init2();
            }
        } else {
            this.init2();
        }
    }

    init2() {
        const now = new Date();
        this.day = now.getDate();
        this.year = now.getFullYear();
        this.month = now.getMonth() + 1;
        this.init();
        this.hasInit = true;
    }

    onSelectTag(e: any) {
        this.coach.title = e.title;
        this.coach.fee = e.fee;
        this.coach.timeLen = e.timeLen;
    }

    onTextareaChange() {
        if (this.form.desc.length > this.widget.max) {
            this.form.desc = this.form.desc.slice(0, 200);
        }
    }

    init() {
        const url = this.api.getUrl('coach_detail', {
            id: this.coach.id,
            act: 'detail',
            year: this.year,
            month: this.month,
            day: this.day
        }, false);
        this.api.get(url).subscribe((res: any) => {
            this.hasSelect = res.hasSelect;
            this.coach = res.detail;
            this.widget = { ...this.widget, ...res.detail.setting };
            this.widget.tabs[1].num = res.starsTotal;
            this.coach.stars = res.stars;
        });
    }

    daySelect(e: any) {
        this.day = e.day;
        this.year = e.year;
        this.month = e.month;
        this.init();
    }

    onSelect(e: any) {
        console.log(e);
        if (e.add) {
            this.form.time.push(e);
        } else {
            const index = this.form.time.indexOf(e);
            this.form.time.splice(index, 1);
        }
    }

    post() {
        this.widget.loading = true;
        if (this.widget.action === 'pay') {
            const url = this.api.getUrl('coach_detail', { id: this.coach.id, act: 'create' }, false);
            this.form.time.map(time => {
                const _date = new Date(time.year, time.month - 1, time.day, time.hour, time.minute);
                time.val = this.getNowFormatDate(_date, 'yyyy-MM-dd hh:mm');
            });
            this.api.post(url, this.form).subscribe((res: any) => {
                setTimeout(() => {
                    this.widget.loading = false;
                }, 1000);
                const re_url = this.api.getUrl('pay', { tid: res.tid }, false);
                window.location.href = re_url;
            });
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
        if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (const k in o)
            if (new RegExp("(" + k + ")").test(fmt))
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    }

    updateCoach() {
        const url = this.api.getUrl('coach_detail', { id: this.coach.id, act: 'update' }, false);
        return this.api.post(url, this.widget);
    }
}

