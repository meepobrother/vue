import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'fox-list',
    templateUrl: './fox-list.html',
    styleUrls: ['./fox-list.scss']
})
export class FoxList implements OnInit {
    @Input() items: any[] = [];
    constructor() { }
    ngOnInit() { }
}
