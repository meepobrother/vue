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
let FoxPage = class FoxPage {
    constructor(ele, render) {
        this.ele = ele;
        this.render = render;
        this.outCls = 'transition-out';
        this.loadingCls = 'fox-page-loading';
        this._loading = false;
        this._center = true;
        this.transitions = {
            forward: {
                fade: {
                    init: 'fx-fade-out',
                    show: 'fx-fade-in',
                    hide: 'fx-fade-out'
                },
                hslide: {
                    init: 'fx-slide-right',
                    show: 'fx-slide-center',
                    hide: 'fx-slide-left'
                },
                vslide: {
                    init: 'fx-slide-down',
                    show: 'fx-slide-middle',
                    hide: 'fx-slide-down'
                },
                display: {
                    hide: 'fx-display-hide'
                }
            },
            backward: {
                hslide: {
                    init: 'fx-slide-left',
                    show: 'fx-slide-center',
                    hide: 'fx-slide-right'
                },
                vslide: {
                    init: 'fx-slide-down',
                    show: 'fx-slide-middle',
                    hide: 'fx-slide-down'
                },
                display: {
                    hide: 'fx-display-hide'
                }
            }
        };
    }
    ngOnInit() {
        this.initLoadingElement();
    }
    clearFX() {
        this.render.removeClass(this.ele.nativeElement, this.classList);
    }
    clearOutCls() {
        this.render.removeClass(this.ele.nativeElement, this.outCls);
    }
    initLoadingElement() {
        const el = this.pageLoadingWrapper.nativeElement;
        const cnt = this.foxPageContent.nativeElement;
    }
};
__decorate([
    core_1.HostBinding('class.fox-page-loading'),
    __metadata("design:type", Boolean)
], FoxPage.prototype, "_loading", void 0);
__decorate([
    core_1.HostBinding('class.fx-slide-center'),
    __metadata("design:type", Boolean)
], FoxPage.prototype, "_center", void 0);
__decorate([
    core_1.ViewChild('pageLoadingWrapper'),
    __metadata("design:type", core_1.ElementRef)
], FoxPage.prototype, "pageLoadingWrapper", void 0);
__decorate([
    core_1.ViewChild('foxPageContent'),
    __metadata("design:type", core_1.ElementRef)
], FoxPage.prototype, "foxPageContent", void 0);
FoxPage = __decorate([
    core_1.Component({
        selector: 'fox-page',
        template: `
        <fox-page-content #foxPageContent>
            <ng-content></ng-content>
            <div class="page-loading-wrapper" #pageLoadingWrapper>
                <fox-icon class="animate-spin" icon="icon-spin5"></fox-icon>
            </div>
        </fox-page-content>
    `,
        styleUrls: ['./fox-page.scss'],
        encapsulation: core_1.ViewEncapsulation.None
    }),
    __metadata("design:paramtypes", [core_1.ElementRef,
        core_1.Renderer2])
], FoxPage);
exports.FoxPage = FoxPage;
//# sourceMappingURL=fox-page.js.map