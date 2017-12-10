import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'fox-cube',
    templateUrl: './fox-cube.html',
    styleUrls: ['./fox-cube.scss']
})
export class FoxCube implements OnInit {
    @Input() items: any[] = [];
    constructor() { }

    ngOnInit() { }
}
