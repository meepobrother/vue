import { Component, OnInit, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'fox-toolbar',
    template: `
        <ng-content></ng-content>
    `,
    styleUrls: ['./fox-toolbar.scss'],
    encapsulation: ViewEncapsulation.None
})
export class FoxToolbar implements OnInit {
    constructor() { }

    ngOnInit() { }
}
