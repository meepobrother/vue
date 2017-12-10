import { Component, OnInit, HostBinding } from '@angular/core';

@Component({
    selector: 'fox-swiper-item',
    templateUrl: './fox-swiper-item.html',
    styleUrls: ['./fox-swiper-item.scss']
})
export class FoxSwiperItem implements OnInit {
    @HostBinding('class.swiper-slide') _slide: boolean = true;
    constructor() { }
    ngOnInit() { }
}
