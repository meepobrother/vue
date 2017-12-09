import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { CoachService } from '../coach.service';

@Component({
    selector: 'app-join',
    templateUrl: './app-join.html',
    styleUrls: ['./app-join.scss']
})
export class AppJoin implements OnInit {
    groups: any[] = [];
    constructor(
        public coach: CoachService
    ) { }

    ngOnInit() {
        this.coach.getSkillGroup().subscribe((res: any) => {
            this.groups = res;
        });
    }

    post() {

    }

    cancel() {
        window.location.reload();
    }
}
