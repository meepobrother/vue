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
let JdHomeOrderView = class JdHomeOrderView {
    constructor() {
        this.widget = {};
    }
    ngOnInit() { }
    myorder() { }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Object)
], JdHomeOrderView.prototype, "widget", void 0);
JdHomeOrderView = __decorate([
    core_1.Component({
        selector: 'jd-home-order-view',
        templateUrl: './jd-home-order-view.html',
        styleUrls: ['./jd-home-order-view.scss']
    }),
    __metadata("design:paramtypes", [])
], JdHomeOrderView);
exports.JdHomeOrderView = JdHomeOrderView;
//# sourceMappingURL=jd-home-order-view.js.map