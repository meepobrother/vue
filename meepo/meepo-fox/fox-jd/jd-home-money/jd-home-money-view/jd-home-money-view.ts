import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'jd-home-money-view',
    templateUrl: './jd-home-money-view.html',
    styleUrls: ['./jd-home-money-view.scss']
})
export class JdHomeMoneyView implements OnInit {
    @Input() widget: any = {};
    constructor() { }

    ngOnInit() { }

    myorder() { }
}
