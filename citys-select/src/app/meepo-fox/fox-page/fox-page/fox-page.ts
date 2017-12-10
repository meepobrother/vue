import { Component, OnInit, ViewEncapsulation, Renderer2, ElementRef, ViewChild, ContentChild, HostBinding } from '@angular/core';

@Component({
    selector: 'fox-page',
    template: `
        <fox-page-content #foxPageContent>
            <ng-content></ng-content>
            <div class="page-loading-wrapper" #pageLoadingWrapper>
                <fox-icon class="animate-spin" icon="icon-spin5"></fox-icon>
            </div>
        </fox-page-content>
    `,
    styleUrls: ['./fox-page.scss'],
    encapsulation: ViewEncapsulation.None
})
export class FoxPage implements OnInit {
    outCls = 'transition-out';
    loadingCls = 'fox-page-loading';

    classList: any;

    @HostBinding('class.fox-page-loading') _loading: boolean = false;
    @HostBinding('class.fx-slide-center') _center: boolean = true;

    transitions = {
        forward: {
            fade: {
                init: 'fx-fade-out',
                show: 'fx-fade-in',
                hide: 'fx-fade-out'
            },

            hslide: {
                init: 'fx-slide-right',
                show: 'fx-slide-center',
                hide: 'fx-slide-left'
            },

            vslide: {
                init: 'fx-slide-down',
                show: 'fx-slide-middle',
                hide: 'fx-slide-down'
            },

            display: {
                hide: 'fx-display-hide'
            }
        },

        backward: {
            hslide: {
                init: 'fx-slide-left',
                show: 'fx-slide-center',
                hide: 'fx-slide-right'
            },

            vslide: {
                init: 'fx-slide-down',
                show: 'fx-slide-middle',
                hide: 'fx-slide-down'
            },

            display: {
                hide: 'fx-display-hide'
            }
        }
    };

    @ViewChild('pageLoadingWrapper') pageLoadingWrapper: ElementRef;
    @ViewChild('foxPageContent') foxPageContent: ElementRef;
    constructor(
        public ele: ElementRef,
        public render: Renderer2
    ) { }

    ngOnInit() {
        this.initLoadingElement();
    }

    clearFX() {
        this.render.removeClass(this.ele.nativeElement, this.classList);
    }

    clearOutCls() {
        this.render.removeClass(this.ele.nativeElement, this.outCls);
    }

    initLoadingElement() {
        const el = this.pageLoadingWrapper.nativeElement;
        const cnt = this.foxPageContent.nativeElement;
    }
}

