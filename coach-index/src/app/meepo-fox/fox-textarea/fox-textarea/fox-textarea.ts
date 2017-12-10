import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'fox-textarea',
    templateUrl: './fox-textarea.html',
    styleUrls: ['./fox-textarea.scss']
})
export class FoxTextarea implements OnInit {
    @Input() model: string = '';
    @Output() modelChange: EventEmitter<any> = new EventEmitter();
    @Input() title: string;
    @Input() placeholder: string;
    @Input() max: number = 200;
    constructor() { }

    ngOnInit() { }

    _change() {
        this.modelChange.emit(this.model);
    }
}
