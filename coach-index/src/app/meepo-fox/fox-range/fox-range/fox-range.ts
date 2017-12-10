import { Component, OnInit, Input, EventEmitter, Output } from '@angular/core';

@Component({
    selector: 'fox-range',
    template: `
        <input type="range" min="30" [attr.step]="step" [attr.max]="max" style="width: 100%;" (change)="onChange()" [(ngModel)]="model">
    `,
    styleUrls: ['./fox-range.scss']
})
export class FoxRange implements OnInit {
    @Input() model: number = 30;
    @Output() modelChange: EventEmitter<any> = new EventEmitter();
    @Input() step: number = 30;
    max: number = 30 * 20;
    constructor() { }
    ngOnInit() {

    }

    onChange() {
        this.modelChange.emit(this.model);
    }
}
