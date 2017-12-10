import { Component, OnInit, HostBinding, ContentChildren, QueryList, AfterContentInit, ElementRef, Input } from '@angular/core';
import { FoxSwiperItem } from '../fox-swiper-item/fox-swiper-item';
declare const Swiper: any;
@Component({
    selector: 'fox-swiper',
    templateUrl: './fox-swiper.html',
    styleUrls: ['./fox-swiper.scss']
})
export class FoxSwiper implements OnInit, AfterContentInit {
    @HostBinding('class.swiper-container') _container: boolean = true;

    @ContentChildren(FoxSwiperItem) swipers: QueryList<FoxSwiperItem>;

    @Input() hasPage: boolean = false;
    constructor(
        public ele: ElementRef
    ) { }

    ngOnInit() { }

    ngAfterContentInit() {
        setTimeout(() => {
            const swiper = new Swiper(this.ele.nativeElement, {
                autoplay: true,
                autoHeight: true,
                pagination: {
                    el: '.swiper-pagination'
                }
            });
        }, 300);
    }
}
