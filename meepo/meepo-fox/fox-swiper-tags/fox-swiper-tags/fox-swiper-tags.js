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
let FoxSwiperTags = class FoxSwiperTags {
    constructor() {
        this._items = [];
        this.swipers = [];
    }
    set items(val) {
        if (val) {
            this._items = val;
            this.filter();
        }
    }
    get items() {
        return this._items;
    }
    ngOnInit() { }
    filter() {
        const sum = this._items.length;
        const row = Math.ceil(sum / 10);
        for (let i = 0; i < row; i++) {
            this.swipers.push({
                list: this._items.splice(0, 10)
            });
        }
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Array),
    __metadata("design:paramtypes", [Array])
], FoxSwiperTags.prototype, "items", null);
FoxSwiperTags = __decorate([
    core_1.Component({
        selector: 'fox-swiper-tags',
        templateUrl: './fox-swiper-tags.html',
        styleUrls: ['./fox-swiper-tags.scss']
    }),
    __metadata("design:paramtypes", [])
], FoxSwiperTags);
exports.FoxSwiperTags = FoxSwiperTags;
//# sourceMappingURL=fox-swiper-tags.js.map