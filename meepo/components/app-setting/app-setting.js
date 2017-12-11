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
let AppSetting = class AppSetting {
    constructor(coach$) {
        this.coach$ = coach$;
        this.btn_title = '保存';
        this.loading = false;
    }
    ngOnInit() {
        this.coach$.widget.timeLen = 30;
    }
    save() {
        this.loading = true;
        this.coach$.updateCoach().subscribe(res => {
            this.btn_title = '保存成功';
            setTimeout(() => {
                this.loading = false;
                this.btn_title = '保存';
            }, 1000);
        });
    }
    foxPickerTimeChangeStart(e) {
        this.coach$.widget.time.start = Object.assign({}, e.value, { label: e.label });
    }
    foxPickerTimeChangeEnd(e) {
        this.coach$.widget.time.end = Object.assign({}, e.value, { label: e.label });
    }
    foxPickerTimeLenChange(e) {
        this.coach$.widget.timeLen = e.value;
    }
};
AppSetting = __decorate([
    core_1.Component({
        selector: 'app-setting',
        templateUrl: './app-setting.html',
        styleUrls: ['./app-setting.scss']
    }),
    __metadata("design:paramtypes", [coach_service_1.CoachService])
], AppSetting);
exports.AppSetting = AppSetting;
//# sourceMappingURL=app-setting.js.map