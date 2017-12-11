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
const coach_service_1 = require("../coach.service");
let AppJoin = class AppJoin {
    constructor(coach) {
        this.coach = coach;
        this.groups = [];
        this.user = {
            description: '',
            mobile: '',
            code: ''
        };
        this.widget = {
            items: []
        };
        this.loading = false;
        this.isInner = true;
    }
    ngOnInit() {
        this.coach.getSkillGroup().subscribe((res) => {
            this.groups = res.groups;
            this.widget.items = this.groups;
            const skill = res.skill;
            this.user = res.user;
            this.coach.joinForm.mobile = this.user.mobile;
            this.coach.joinForm.title = skill.title;
            this.coach.joinForm.desc = skill.desc;
            this.coach.joinForm.fee = skill.fee;
            if (this.groups.length > 0) {
                this.groups[0].open = true;
            }
        });
    }
    textareaChange(e) {
        console.log(e);
    }
    post() {
        this.loading = true;
        this.coach.skillJoin(this.coach.joinForm).subscribe(res => {
            setTimeout(() => {
                this.loading = false;
                this.cancel();
            }, 1000);
        });
    }
    cancel() {
        if (this.isInner) {
            this.coach.showJoin = false;
        }
        else {
            window.location.href = this.coach.api.getUrl('coach_my', {});
        }
    }
    getCode() {
    }
    onSelectTag(e) {
        this.coach.joinForm.setting.push({
            title: e.title
        });
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Boolean)
], AppJoin.prototype, "isInner", void 0);
AppJoin = __decorate([
    core_1.Component({
        selector: 'app-join',
        templateUrl: './app-join.html',
        styleUrls: ['./app-join.scss']
    }),
    __metadata("design:paramtypes", [coach_service_1.CoachService])
], AppJoin);
exports.AppJoin = AppJoin;
//# sourceMappingURL=app-join.js.map