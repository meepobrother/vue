import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'fox-full',
    template: `
    <div class="shop-logo-big">
        <ng-content></ng-content>
    </div>
    `,
    styleUrls: ['./fox-full.scss']
})
export class FoxFull implements OnInit {
    @Input() logo: string = '';
    constructor() { }
    ngOnInit() { }
}
