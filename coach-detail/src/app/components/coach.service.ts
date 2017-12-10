import { Injectable } from '@angular/core';
import { ApiService } from '../meepo-fox';
import {
    defaultCoach,
    defaultWidget,
    defaultForm,
    defaultHeader
} from './init.data';
@Injectable()
export class CoachService {
    // 角色
    roles: string[] = ['member'];
    // 详情
    coach: any = defaultCoach;
    // 设置
    widget: any = defaultWidget;
    // 预约表单
    form: any = defaultForm;
    // 今天
    day: number;
    // 年
    year: number;
    // 月
    month: number;
    // 已经选择
    hasSelect: any[] = [];
    // 时间列表
    timeList: any[] = [];
    // 显示条款
    showTiaokuan: boolean = false;
    // 是否初始化
    hasInit: boolean = false;
    // 显示入驻
    showJoin: boolean = false;
    // 城市列表
    showCitys: boolean = false;
    // 标签
    tags: any = [];
    // 城市
    city: any = {};
    joinForm: any = {
        mobile: '',
        title: '',
        desc: '',
        setting: [],
        fee: '',
        city: ''
    };


    header: any = defaultHeader;
    constructor(
        public api: ApiService
    ) { }

    selectTabs(e) {

    }

    getCity() {
        return this.api.get('https://meepo.com.cn/v1/cities');
    }

    getCitys() {
        return this.api.get('https://meepo.com.cn/v1/cities/?type=group');
    }

    getHotCitys() {
        return this.api.get('https://meepo.com.cn/v1/cities/?type=hot');
    }

    setCity(res: any) {
        this.city = res;
        this.header.city = res.name;
        this.joinForm.city = res.name;
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
        this.form.title = e.title;
    }

    onTextareaChange() {
        if (this.form.desc.length > this.widget.max) {
            this.form.desc = this.form.desc.slice(0, 200);
        }
    }

    init() {
        this.getCity().subscribe((res: any) => {
            this.city = res;
            this.header.city = res.name;
            this.joinForm.city = res.name;
        });
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
            this.tags = res.tags;
            this.widget = { ...this.widget, ...res.detail.setting };
            this.widget.tabs[1].num = res.starsTotal;
            this.coach.stars = res.stars;
        });
    }

    gethasSelect() {
        const url = this.api.getUrl('coach_detail', {
            id: this.coach.id,
            act: 'detail',
            year: this.year,
            month: this.month,
            day: this.day
        }, false);
        this.api.get(url).subscribe((res: any) => {
            this.hasSelect = res.hasSelect;
        });
    }

    daySelect(e: any) {
        this.day = e.day;
        this.year = e.year;
        this.month = e.month;
        this.gethasSelect();
    }

    onSelect(e: any) {
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

    checkHasRole(role, roles) {
        return roles.indexOf(role) !== -1;
    }

    getSkillGroup() {
        const url = this.api.getUrl('coach_detail', { act: 'groups' }, false);
        return this.api.get(url);
    }

    skillJoin(data: any) {
        const url = this.api.getUrl('coach_detail', { act: 'add_skill' }, false);
        return this.api.post(url, data);
    }
}

