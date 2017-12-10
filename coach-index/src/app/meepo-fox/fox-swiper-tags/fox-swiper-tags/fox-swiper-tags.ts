import { Component, OnInit, HostBinding, Input } from '@angular/core';

@Component({
    selector: 'fox-swiper-tags',
    templateUrl: './fox-swiper-tags.html',
    styleUrls: ['./fox-swiper-tags.scss']
})
export class FoxSwiperTags implements OnInit {
    _items: any[] = [];
    @Input()
    set items(val: any[]) {
        if (val) {
            this._items = val;
            this.filter();
        }
    }
    get items() {
        return this._items;
    }

    swipers: any[] = [];

    constructor() { }

    ngOnInit() { }

    filter() {
        const sum = this._items.length;
        const row = Math.ceil(sum / 10);
        for (let i = 0; i < row; i++) {
            this.swipers.push({
                list: this._items.splice(0, 10)
            });
        }
    }
}

