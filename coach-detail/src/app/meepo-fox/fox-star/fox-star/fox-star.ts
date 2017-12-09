import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'fox-star',
    templateUrl: './fox-star.html',
    styleUrls: ['./fox-star.scss']
})
export class FoxStar implements OnInit {
    @Input() on: boolean = false;
    constructor() { }
    ngOnInit() { }
}
