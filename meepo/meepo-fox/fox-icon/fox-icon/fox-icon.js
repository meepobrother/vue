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
let FoxIcon = class FoxIcon {
    constructor(ele, render) {
        this.ele = ele;
        this.render = render;
    }
    set icon(val) {
        this.render.addClass(this.ele.nativeElement, val);
    }
    ngOnInit() { }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", String),
    __metadata("design:paramtypes", [String])
], FoxIcon.prototype, "icon", null);
FoxIcon = __decorate([
    core_1.Component({
        selector: 'fox-icon',
        template: `
        <ng-content></ng-content>
    `,
        styleUrls: ['./fox-icon.scss'],
        encapsulation: core_1.ViewEncapsulation.None
    }),
    __metadata("design:paramtypes", [core_1.ElementRef,
        core_1.Renderer2])
], FoxIcon);
exports.FoxIcon = FoxIcon;
//# sourceMappingURL=fox-icon.js.map