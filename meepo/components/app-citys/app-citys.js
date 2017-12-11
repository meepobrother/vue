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
let AppCitys = class AppCitys {
    constructor(coach) {
        this.coach = coach;
        this.hots = [];
        this.citys = [];
        this.isInner = true;
    }
    ngOnInit() {
        this.coach.getCitys().subscribe((res) => {
            const list = [];
            for (const k in res) {
                list.push({
                    label: k,
                    list: res[k]
                });
            }
            this.citys = list;
        });
        this.coach.onInit();
        this.coach.getHotCitys().subscribe((res) => {
            this.hots = res;
        });
    }
    goTop() {
        window.scrollTo(0, 0);
    }
    selectCity(city) {
        if (this.isInner) {
            this.goTop();
            this.coach.setCity(city);
            this.coach.showCitys = false;
        }
        else {
            const params = {};
            params['name'] = city.name;
            params['latitude'] = city.latitude;
            params['longitude'] = city.longitude;
            location.href = this.coach.api.getUrl('coach_index', params);
        }
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Boolean)
], AppCitys.prototype, "isInner", void 0);
AppCitys = __decorate([
    core_1.Component({
        selector: 'app-citys',
        templateUrl: './app-citys.html',
        styleUrls: ['./app-citys.scss']
    }),
    __metadata("design:paramtypes", [coach_service_1.CoachService])
], AppCitys);
exports.AppCitys = AppCitys;
//# sourceMappingURL=app-citys.js.map