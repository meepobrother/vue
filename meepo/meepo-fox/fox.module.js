"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@angular/core");
const common_1 = require("@angular/common");
const forms_1 = require("@angular/forms");
const api_1 = require("./util/api");
const public_api_1 = require("./public_api");
const commponents = [
    public_api_1.FoxCalendar,
    public_api_1.FoxIcon,
    public_api_1.FoxRange,
    public_api_1.FoxPage,
    public_api_1.FoxPageContent,
    public_api_1.FoxDialog,
    public_api_1.FoxTags,
    public_api_1.FoxToolbar,
    public_api_1.FoxTabs,
    public_api_1.FoxFull,
    public_api_1.FoxMain,
    public_api_1.FoxPickerTime,
    public_api_1.FoxPickerTimeLen,
    public_api_1.FoxTextarea,
    public_api_1.FoxStar,
    public_api_1.FoxHeader,
    public_api_1.FoxSwiper,
    public_api_1.FoxSwiperItem,
    public_api_1.FoxSwiperTags,
    public_api_1.FoxCube,
    public_api_1.FoxList,
    public_api_1.FoxFooter,
    public_api_1.JdHomeHeaderView,
    public_api_1.JdHomeListView,
    public_api_1.JdHomeMoneyView,
    public_api_1.JdHomeOrderView
];
let FoxModule = class FoxModule {
};
FoxModule = __decorate([
    core_1.NgModule({
        declarations: [
            ...commponents
        ],
        imports: [
            common_1.CommonModule,
            forms_1.FormsModule
        ],
        exports: [
            ...commponents
        ],
        providers: [
            api_1.ApiService
        ],
    })
], FoxModule);
exports.FoxModule = FoxModule;
//# sourceMappingURL=fox.module.js.map