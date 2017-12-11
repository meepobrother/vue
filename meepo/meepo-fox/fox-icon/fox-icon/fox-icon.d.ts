import { OnInit, ElementRef, Renderer2 } from '@angular/core';
export declare class FoxIcon implements OnInit {
    ele: ElementRef;
    render: Renderer2;
    icon: string;
    constructor(ele: ElementRef, render: Renderer2);
    ngOnInit(): void;
}
