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
const fox_swiper_item_1 = require("../fox-swiper-item/fox-swiper-item");
let FoxSwiper = class FoxSwiper {
    constructor(ele) {
        this.ele = ele;
        this._container = true;
        this.hasPage = false;
    }
    ngOnInit() { }
    ngAfterContentInit() {
        setTimeout(() => {
            const swiper = new Swiper(this.ele.nativeElement, {
                autoplay: true,
                autoHeight: true,
                pagination: {
                    el: '.swiper-pagination'
                }
            });
        }, 300);
    }
};
__decorate([
    core_1.HostBinding('class.swiper-container'),
    __metadata("design:type", Boolean)
], FoxSwiper.prototype, "_container", void 0);
__decorate([
    core_1.ContentChildren(fox_swiper_item_1.FoxSwiperItem),
    __metadata("design:type", core_1.QueryList)
], FoxSwiper.prototype, "swipers", void 0);
__decorate([
    core_1.Input(),
    __metadata("design:type", Boolean)
], FoxSwiper.prototype, "hasPage", void 0);
FoxSwiper = __decorate([
    core_1.Component({
        selector: 'fox-swiper',
        templateUrl: './fox-swiper.html',
        styleUrls: ['./fox-swiper.scss']
    }),
    __metadata("design:paramtypes", [core_1.ElementRef])
], FoxSwiper);
exports.FoxSwiper = FoxSwiper;
//# sourceMappingURL=fox-swiper.js.map