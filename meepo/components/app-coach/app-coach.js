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
let AppCoach = class AppCoach {
    constructor(coach$) {
        this.coach$ = coach$;
    }
    onSelectTag(e) {
        this.coach$.onSelectTag(e);
    }
    onTextareaChange() {
        this.coach$.onTextareaChange();
    }
    ngOnInit() {
        this.coach$.onInit();
    }
    daySelect(e) {
        this.coach$.daySelect(e);
    }
    onSelect(e) {
        this.coach$.onSelect(e);
    }
    post() {
        this.coach$.post();
    }
};
AppCoach = __decorate([
    core_1.Component({
        selector: 'app-coach',
        templateUrl: './app-coach.html',
        styleUrls: ['./app-coach.scss']
    }),
    __metadata("design:paramtypes", [coach_service_1.CoachService])
], AppCoach);
exports.AppCoach = AppCoach;
//# sourceMappingURL=app-coach.js.map