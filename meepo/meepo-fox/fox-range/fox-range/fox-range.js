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
let FoxRange = class FoxRange {
    constructor() {
        this.model = 30;
        this.modelChange = new core_1.EventEmitter();
        this.step = 30;
        this.max = 30 * 20;
    }
    ngOnInit() {
    }
    onChange() {
        this.modelChange.emit(this.model);
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Number)
], FoxRange.prototype, "model", void 0);
__decorate([
    core_1.Output(),
    __metadata("design:type", core_1.EventEmitter)
], FoxRange.prototype, "modelChange", void 0);
__decorate([
    core_1.Input(),
    __metadata("design:type", Number)
], FoxRange.prototype, "step", void 0);
FoxRange = __decorate([
    core_1.Component({
        selector: 'fox-range',
        template: `
        <input type="range" min="30" [attr.step]="step" [attr.max]="max" style="width: 100%;" (change)="onChange()" [(ngModel)]="model">
    `,
        styleUrls: ['./fox-range.scss']
    }),
    __metadata("design:paramtypes", [])
], FoxRange);
exports.FoxRange = FoxRange;
//# sourceMappingURL=fox-range.js.map