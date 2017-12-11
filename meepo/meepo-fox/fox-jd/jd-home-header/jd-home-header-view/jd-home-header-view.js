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
let JdHomeHeaderView = class JdHomeHeaderView {
    constructor() {
        this.widget = {
            containerStyle: { margin: '0px' },
            info: {}
        };
        this.onAccount = new core_1.EventEmitter();
    }
    ngOnInit() {
        this.widget.info['nickname'] = this.widget.info['nickname'] || '昵称';
        this.widget.info['mobile'] = this.widget.info['mobile'] || '电话未知';
        this.widget.info['tag'] = this.widget.info['tag'] || '标签';
        this.widget.info['desc'] = this.widget.info['desc'] || '用户等级';
    }
    _onAccount() {
        this.onAccount.emit('');
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Object)
], JdHomeHeaderView.prototype, "widget", void 0);
__decorate([
    core_1.Output(),
    __metadata("design:type", core_1.EventEmitter)
], JdHomeHeaderView.prototype, "onAccount", void 0);
JdHomeHeaderView = __decorate([
    core_1.Component({
        selector: 'jd-home-header-view',
        templateUrl: './jd-home-header-view.html',
        styleUrls: ['./jd-home-header-view.scss']
    }),
    __metadata("design:paramtypes", [])
], JdHomeHeaderView);
exports.JdHomeHeaderView = JdHomeHeaderView;
//# sourceMappingURL=jd-home-header-view.js.map