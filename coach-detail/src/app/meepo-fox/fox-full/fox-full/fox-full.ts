import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'fox-full',
    template: `
    <div class="shop-logo-big">
        <div class="shop-img">
            <img [src]="logo" alt="">
        </div>
    </div>
    `,
    styleUrls: ['./fox-full.scss']
})
export class FoxFull implements OnInit {
    @Input() logo: string = '';
    constructor() { }
    ngOnInit() { }
}
