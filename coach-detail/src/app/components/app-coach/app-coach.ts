
import { Component, OnInit } from '@angular/core';
import { CoachService } from '../coach.service';

@Component({
    selector: 'app-coach',
    templateUrl: './app-coach.html',
    styleUrls: ['./app-coach.scss']
})
export class AppCoach implements OnInit {
    constructor(
        public coach$: CoachService
    ) { }

    onSelectTag(e: any) {
        this.coach$.onSelectTag(e);
    }

    onTextareaChange() {
        this.coach$.onTextareaChange();
    }

    ngOnInit() {
        this.coach$.onInit();
    }

    daySelect(e: any) {
        this.coach$.daySelect(e);
    }

    onSelect(e: any) {
        this.coach$.onSelect(e);
    }

    post() {
        this.coach$.post();
    }
}
