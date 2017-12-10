import { Component, OnInit } from '@angular/core';
import { CoachService } from '../coach.service';
import { defaultCoach } from '../init.data';
declare const weui: any;
@Component({
    selector: 'app-star',
    templateUrl: './app-star.html',
    styleUrls: ['./app-star.scss']
})
export class AppStar implements OnInit {
    items: any[] = defaultCoach.stars;

    showDialog: boolean = false;

    form: any = {
        content: ''
    };
    hasPostPermission: boolean = true;
    constructor(
        public coach$: CoachService
    ) { }

    ngOnInit() {
        // 是预约者 可评论一次
        console.log(this.coach$.roles.indexOf('coacher'));
        if (this.coach$.roles.indexOf('coacher') === -1) {
            this.hasPostPermission = false;
        }
    }

}
