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
let AppField = class AppField {
    constructor(coach$) {
        this.coach$ = coach$;
    }
    ngOnInit() {
        const qrcode = new QRCode('qrcode', {
            text: window.location.href,
            width: 256,
            height: 256,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }
};
AppField = __decorate([
    core_1.Component({
        selector: 'app-field',
        templateUrl: './app-field.html',
        styleUrls: ['./app-field.scss']
    }),
    __metadata("design:paramtypes", [coach_service_1.CoachService])
], AppField);
exports.AppField = AppField;
//# sourceMappingURL=app-field.js.map