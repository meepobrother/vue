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
let FoxTabs = class FoxTabs {
    constructor() {
        this.items = [];
        this.roles = ['member'];
        this.onSelect = new core_1.EventEmitter();
    }
    ngOnInit() {
        const delIds = [];
        this.items.map((res, index) => {
            if (res.active) {
                this._select(res);
            }
            if (this.roles.indexOf(res.role) === -1) {
                delIds.push(res);
            }
        });
        delIds.map(item => {
            const index = this.items.indexOf(item);
            this.items.splice(index, 1);
        });
    }
    _select(item) {
        this.items.map(res => {
            res.active = false;
        });
        item.active = !item.active;
        this.onSelect.emit(item);
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Array)
], FoxTabs.prototype, "items", void 0);
__decorate([
    core_1.Input(),
    __metadata("design:type", Array)
], FoxTabs.prototype, "roles", void 0);
__decorate([
    core_1.Output(),
    __metadata("design:type", core_1.EventEmitter)
], FoxTabs.prototype, "onSelect", void 0);
FoxTabs = __decorate([
    core_1.Component({
        selector: 'fox-tabs',
        templateUrl: './fox-tabs.html',
        styleUrls: ['./fox-tabs.scss']
    }),
    __metadata("design:paramtypes", [])
], FoxTabs);
exports.FoxTabs = FoxTabs;
//# sourceMappingURL=fox-tabs.js.map