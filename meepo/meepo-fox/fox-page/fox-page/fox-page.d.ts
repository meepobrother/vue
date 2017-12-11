import { OnInit, Renderer2, ElementRef } from '@angular/core';
export declare class FoxPage implements OnInit {
    ele: ElementRef;
    render: Renderer2;
    outCls: string;
    loadingCls: string;
    classList: any;
    _loading: boolean;
    _center: boolean;
    transitions: {
        forward: {
            fade: {
                init: string;
                show: string;
                hide: string;
            };
            hslide: {
                init: string;
                show: string;
                hide: string;
            };
            vslide: {
                init: string;
                show: string;
                hide: string;
            };
            display: {
                hide: string;
            };
        };
        backward: {
            hslide: {
                init: string;
                show: string;
                hide: string;
            };
            vslide: {
                init: string;
                show: string;
                hide: string;
            };
            display: {
                hide: string;
            };
        };
    };
    pageLoadingWrapper: ElementRef;
    foxPageContent: ElementRef;
    constructor(ele: ElementRef, render: Renderer2);
    ngOnInit(): void;
    clearFX(): void;
    clearOutCls(): void;
    initLoadingElement(): void;
}
