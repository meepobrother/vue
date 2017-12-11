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
let FoxHeader = class FoxHeader {
    constructor() {
        this.widget = {
            title: '找任务 找服务',
            my: '我的',
            post: '入驻',
            city: '杭州'
        };
        this.onClick = new core_1.EventEmitter();
    }
    ngOnInit() { }
    _onClick(type) {
        this.onClick.emit(type);
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Object)
], FoxHeader.prototype, "widget", void 0);
__decorate([
    core_1.Output(),
    __metadata("design:type", core_1.EventEmitter)
], FoxHeader.prototype, "onClick", void 0);
FoxHeader = __decorate([
    core_1.Component({
        selector: 'fox-header',
        templateUrl: './fox-header.html',
        styleUrls: ['./fox-header.scss']
    }),
    __metadata("design:paramtypes", [])
], FoxHeader);
exports.FoxHeader = FoxHeader;
//# sourceMappingURL=fox-header.js.map