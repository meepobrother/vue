import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'fox-main',
    templateUrl: 'fox-main.html',
    styleUrls: ['./fox-main.scss']
})
export class FoxMain implements OnInit {
    @Input() widget: any = {};
    constructor() { }
    ngOnInit() { }

    collect() {
        this.widget.hasCollect = !this.widget.hasCollect;
    }
}
