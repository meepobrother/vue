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
let FoxFooter = class FoxFooter {
    constructor() {
        this.items = [];
        this.onItem = new core_1.EventEmitter();
    }
    ngOnInit() { }
    _onItem(item) {
        this.onItem.emit(item);
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Array)
], FoxFooter.prototype, "items", void 0);
__decorate([
    core_1.Output(),
    __metadata("design:type", core_1.EventEmitter)
], FoxFooter.prototype, "onItem", void 0);
FoxFooter = __decorate([
    core_1.Component({
        selector: 'fox-footer',
        templateUrl: './fox-footer.html',
        styleUrls: ['./fox-footer.scss']
    }),
    __metadata("design:paramtypes", [])
], FoxFooter);
exports.FoxFooter = FoxFooter;
//# sourceMappingURL=fox-footer.js.map