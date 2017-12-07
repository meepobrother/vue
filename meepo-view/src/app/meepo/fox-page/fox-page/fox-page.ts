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

    // fx(action, animation, backward) {
    //     const transition = this.transition || 'fade';
    //     const dir = backward ? 'backward' : 'forward';

    //     // 无动画的显示隐藏简单处理
    //     if (this.transition === 'display') {
    //         if (action === 'show') {
    //             clearOutCls(this);
    //             this.classList.remove(transitions[dir].display.hide);
    //             fireEvent.call(this, 'aftershow');
    //         }
    //         else if (action === 'hide') {
    //             this.classList.add(transitions[dir].display.hide);
    //             fireEvent.call(this, 'afterhide');
    //         }

    //         return;
    //     }

    //     animation = (animation === false ? false : true);

    //     this.classList.remove('transition');

    //     var effect = transitions[dir] || transitions['forward'];

    //     if (!effect[transition]) {
    //         effect[transition] = transitions['forward'][transition];
    //     }

    //     if (!effect || !effect[transition]) {
    //         throw new Error('No transitions for direction ' + dir);
    //     }

    //     var actCls = effect[transition][action];
    //     var initCls = effect[transition].init;
    //     var me = this;

    //     if (!animation) {
    //         clearFX(this);
    //         this.classList.add(actCls);
    //     }
    //     else {

    //         if (action === 'show') {
    //             // keep this duplicated code here
    //             clearFX(this);
    //             this.classList.add(initCls);
    //         }

    //         setTimeout(function () {
    //             // keep this duplicated code here
    //             clearFX(me);
    //             me.classList.add('transition');
    //             me.classList.add(actCls);
    //         }, 60);
    //     }

    //     // clear hide class
    //     if (action === 'show') {
    //         clearOutCls(this);
    //     }
}
}

