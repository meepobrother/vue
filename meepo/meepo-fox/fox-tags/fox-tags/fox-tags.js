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
let FoxTags = class FoxTags {
    constructor() {
        this.widget = {
            containerStyle: { margin: 0 },
            items: []
        };
        this.isMuilt = false;
        this.onSelect = new core_1.EventEmitter();
    }
    set items(val) {
        if (val) {
            this.widget.items = val;
        }
        if (this.widget.items && this.widget.items.length > 0) {
            this.widget.items[0].active = true;
            this.select(this.widget.items[0]);
        }
    }
    get items() {
        return this.widget.items;
    }
    ngOnInit() {
        console.log(this.widget);
    }
    select(item) {
        if (!this.isMuilt) {
            this.widget.items.map((res) => {
                res.active = false;
            });
        }
        item.active = !item.active;
        this.onSelect.emit(item);
    }
};
__decorate([
    core_1.Input(),
    __metadata("design:type", Object)
], FoxTags.prototype, "widget", void 0);
__decorate([
    core_1.Input(),
    __metadata("design:type", Array),
    __metadata("design:paramtypes", [Array])
], FoxTags.prototype, "items", null);
__decorate([
    core_1.Input(),
    __metadata("design:type", Boolean)
], FoxTags.prototype, "isMuilt", void 0);
__decorate([
    core_1.Output(),
    __metadata("design:type", core_1.EventEmitter)
], FoxTags.prototype, "onSelect", void 0);
FoxTags = __decorate([
    core_1.Component({
        selector: 'fox-tags',
        template: `
    <div style="position: relative;">
        <div class="w_tag bor-b" [ngStyle]="widget.containerStyle">
            <a href="javascript:;"
                [ngStyle]="widget.styleObj"
                class="tab_btn marbot20"
                [class.on]="item.active"
                *ngFor="let item of widget.items"
                (click)="select(item)"
            >{{item.title}}</a>
        </div>
    </div>
    `,
        styleUrls: ['./fox-tags.scss']
    }),
    __metadata("design:paramtypes", [])
], FoxTags);
exports.FoxTags = FoxTags;
//# sourceMappingURL=fox-tags.js.map