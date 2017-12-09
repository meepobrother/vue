import { Component, OnInit } from '@angular/core';
import { CoachService } from '../coach.service';
import { defaultCoach } from '../init.data';
@Component({
    selector: 'app-star',
    templateUrl: './app-star.html',
    styleUrls: ['./app-star.scss']
})
export class AppStar implements OnInit {
    items: any[] = defaultCoach.stars;
    constructor(
        public coach: CoachService
    ) { }

    ngOnInit() { }
}
