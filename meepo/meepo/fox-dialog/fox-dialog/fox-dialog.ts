import { Component, OnInit, ViewEncapsulation, EventEmitter, Output } from '@angular/core';

@Component({
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
    encapsulation: ViewEncapsulation.None
})
export class FoxDialog implements OnInit {
    @Output() onClose: EventEmitter<any> = new EventEmitter();
    constructor() { }

    ngOnInit() { }

    close() {
        this.onClose.emit('');
    }
}
