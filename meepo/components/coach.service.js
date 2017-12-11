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
const meepo_fox_1 = require("../meepo-fox");
const init_data_1 = require("./init.data");
let CoachService = class CoachService {
    constructor(api) {
        this.api = api;
        // 角色
        this.roles = ['member'];
        // 详情
        this.coach = init_data_1.defaultCoach;
        // 设置
        this.widget = init_data_1.defaultWidget;
        // 预约表单
        this.form = init_data_1.defaultForm;
        // 已经选择
        this.hasSelect = [];
        // 时间列表
        this.timeList = [];
        // 显示条款
        this.showTiaokuan = false;
        // 是否初始化
        this.hasInit = false;
        // 显示入驻
        this.showJoin = false;
        // 城市列表
        this.showCitys = false;
        // 标签
        this.tags = [];
        // 城市
        this.city = {};
        this.joinForm = {
            mobile: '',
            title: '',
            desc: '',
            setting: [],
            fee: '',
            city: ''
        };
        this.footer = [
            {
                title: '服务大厅',
                isImage: true,
                icon: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/002.jpg',
                link: this.api.getUrl('coach_index', {})
            }, {
                title: '发布悬赏',
                isImage: true,
                icon: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/003.jpg',
                link: this.api.getUrl('coach_post', {})
            }, {
                title: '个人中心',
                isImage: true,
                icon: 'https://meepo.com.cn/addons/imeepos_runnerpro/assets/pc/001.jpg',
                link: this.api.getUrl('coach_home', {})
            }
        ];
        this.header = init_data_1.defaultHeader;
    }
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
    setCity(res) {
        this.city = res;
        this.header.city = res.name;
        this.joinForm.city = res.name;
    }
    onInit(cache = true) {
        if (cache) {
            if (!this.hasInit) {
                this.init2();
            }
        }
        else {
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
    onSelectTag(e) {
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
        this.getCity().subscribe((res) => {
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
        this.api.get(url).subscribe((res) => {
            this.hasSelect = res.hasSelect;
            this.coach = res.detail;
            this.tags = res.tags;
            this.widget = Object.assign({}, this.widget, res.detail.setting);
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
        this.api.get(url).subscribe((res) => {
            this.hasSelect = res.hasSelect;
        });
    }
    daySelect(e) {
        this.day = e.day;
        this.year = e.year;
        this.month = e.month;
        this.gethasSelect();
    }
    onSelect(e) {
        if (e.add) {
            this.form.time.push(e);
        }
        else {
            const index = this.form.time.indexOf(e);
            this.form.time.splice(index, 1);
        }
    }
    post() {
        this.widget.loading = true;
        if (this.widget.action === 'pay') {
            const url = this.api.getUrl('coach_detail', { id: this.coach.id, act: 'create' }, false);
            this.form.time.map((time) => {
                const _date = new Date(time.year, time.month - 1, time.day, time.hour, time.minute);
                time.val = this.getNowFormatDate(_date, 'yyyy-MM-dd hh:mm');
            });
            this.api.post(url, this.form).subscribe((res) => {
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
        if (/(y+)/.test(fmt))
            fmt = fmt.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
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
    skillJoin(data) {
        const url = this.api.getUrl('coach_detail', { act: 'add_skill' }, false);
        return this.api.post(url, data);
    }
    getSkillList() {
        const url = this.api.getUrl('coach_detail', { act: 'list' });
        return this.api.get(url);
    }
};
CoachService = __decorate([
    core_1.Injectable(),
    __metadata("design:paramtypes", [meepo_fox_1.ApiService])
], CoachService);
exports.CoachService = CoachService;
//# sourceMappingURL=coach.service.js.map