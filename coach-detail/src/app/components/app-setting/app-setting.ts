
import { Component, OnInit } from '@angular/core';
import { CoachService } from '../coach.service';

@Component({
    selector: 'app-setting',
    templateUrl: './app-setting.html',
    styleUrls: ['./app-setting.scss']
})
export class AppSetting implements OnInit {
    title: string = '保存';
    loading: boolean = false;
    constructor(
        public coach$: CoachService
    ) { }

    ngOnInit() { }

    save() {
        this.loading = true;
        this.coach$.updateCoach().subscribe(res => {
            this.loading = false;
        });
    }
}
