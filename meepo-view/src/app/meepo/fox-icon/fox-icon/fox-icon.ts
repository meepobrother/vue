import { Component, OnInit, Input, ElementRef, Renderer2 } from '@angular/core';

@Component({
    selector: 'fox-icon',
    template: `
        <ng-content></ng-content>
    `,
    styleUrls: ['./fox-icon.scss']
})
export class FoxIcon implements OnInit {
    @Input()
    set icon(val: string) {
        this.render.addClass(this.ele.nativeElement, val);
    }
    constructor(
        public ele: ElementRef,
        public render: Renderer2
    ) { }

    ngOnInit() { }
}
