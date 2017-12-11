import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'jd-home-list-view',
    templateUrl: './jd-home-list-view.html',
    styleUrls: ['./jd-home-list-view.scss']
})
export class JdHomeListView implements OnInit {
    
    @Input() widget: any = {};

    constructor() { }

    ngOnInit() { }
}
