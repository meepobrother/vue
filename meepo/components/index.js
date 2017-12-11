"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
function __export(m) {
    for (var p in m) if (!exports.hasOwnProperty(p)) exports[p] = m[p];
}
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@angular/core");
const common_1 = require("@angular/common");
const public_api_1 = require("./public_api");
const meepo_fox_1 = require("../meepo-fox");
let MeepoComponentsModule = class MeepoComponentsModule {
};
MeepoComponentsModule = __decorate([
    core_1.NgModule({
        declarations: [
            public_api_1.AppCitys, public_api_1.AppCoach, public_api_1.AppField, public_api_1.AppJoin, public_api_1.AppSetting, public_api_1.AppStar
        ],
        imports: [
            common_1.CommonModule,
            meepo_fox_1.FoxModule
        ],
        exports: [
            public_api_1.AppCitys, public_api_1.AppCoach, public_api_1.AppField, public_api_1.AppJoin, public_api_1.AppSetting, public_api_1.AppStar,
            meepo_fox_1.FoxModule
        ],
        providers: [
            public_api_1.CoachService
        ],
    })
], MeepoComponentsModule);
exports.MeepoComponentsModule = MeepoComponentsModule;
__export(require("./public_api"));
//# sourceMappingURL=index.js.map