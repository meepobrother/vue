import { Component, OnInit, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'fox-page-content',
    template: `
        <ng-content></ng-content>
    `,
    styleUrls: ['./fox-page-content.scss'],
    encapsulation: ViewEncapsulation.None
})
export class FoxPageContent implements OnInit {
    constructor() { }

    ngOnInit() { }
}
