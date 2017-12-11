import { OnInit, QueryList, AfterContentInit, ElementRef } from '@angular/core';
import { FoxSwiperItem } from '../fox-swiper-item/fox-swiper-item';
export declare class FoxSwiper implements OnInit, AfterContentInit {
    ele: ElementRef;
    _container: boolean;
    swipers: QueryList<FoxSwiperItem>;
    hasPage: boolean;
    constructor(ele: ElementRef);
    ngOnInit(): void;
    ngAfterContentInit(): void;
}
