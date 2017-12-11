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
let FoxDialog = class FoxDialog {
    constructor() {
        this.onClose = new core_1.EventEmitter();
    }
    ngOnInit() { }
    close() {
        this.onClose.emit('');
    }
};
__decorate([
    core_1.Output(),
    __metadata("design:type", core_1.EventEmitter)
], FoxDialog.prototype, "onClose", void 0);
FoxDialog = __decorate([
    core_1.Component({
        selector: 'fox-dialog',
        template: `
    <div id="fox-dialog-container" class="dialog-container">
        <div class="popup-head">
            <div class="popup-title">
                <a style="float:right;" (click)="close()">关闭</a>
            </div>
        </div>
        <div class="popup-body">
            <ng-content></ng-content>
        </div>
    </div>
    `,
        styleUrls: ['./fox-dialog.scss'],
        encapsulation: core_1.ViewEncapsulation.None
    }),
    __metadata("design:paramtypes", [])
], FoxDialog);
exports.FoxDialog = FoxDialog;
//# sourceMappingURL=fox-dialog.js.map