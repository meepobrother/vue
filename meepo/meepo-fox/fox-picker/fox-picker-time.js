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
const api_1 = require("../util/api");
let FoxPickerTime = class FoxPickerTime {
    constructor(api) {
        this.api = api;
        this.times = [];
        this.foxPickerTimeChange = new core_1.EventEmitter();
    }
    onclick() {
        this.picker();
    }
    ngOnInit() {
        const times = [];
        const _now = new Date();
        const year = _now.getFullYear();
        const month = _now.getFullYear();
        const day = _now.getFullYear();
        for (let i = 0; i < 24; i++) {
            [0, 30].map(item => {
                const _time = new Date(year, month, day, i, item);
                times.push({
                    label: this.api.formatDate(_time, 'hh:mm'),
                    value: {
                        hour: _time.getHours(),
                        minute: _time.getMinutes()
                    }
                });
            });
        }
        this.times = times;
    }
    picker() {
        weui.picker(this.times, {
            onConfirm: (result) => {
                this.foxPickerTime = result[0];
                this.foxPickerTimeChange.emit(this.foxPickerTime);
            }
        });
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Object)
], FoxPickerTime.prototype, "foxPickerTime", void 0);
__decorate([
    core_1.Output(),
    __metadata("design:type", core_1.EventEmitter)
], FoxPickerTime.prototype, "foxPickerTimeChange", void 0);
__decorate([
    core_1.HostListener('click', ['$event']),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", []),
    __metadata("design:returntype", void 0)
], FoxPickerTime.prototype, "onclick", null);
FoxPickerTime = __decorate([
    core_1.Directive({
        selector: '[foxPickerTime]',
    }),
    __metadata("design:paramtypes", [api_1.ApiService])
], FoxPickerTime);
exports.FoxPickerTime = FoxPickerTime;
//# sourceMappingURL=fox-picker-time.js.map