
import { Component, OnInit } from '@angular/core';
import { CoachService } from '../coach.service';

@Component({
    selector: 'app-setting',
    templateUrl: './app-setting.html',
    styleUrls: ['./app-setting.scss']
})
export class AppSetting implements OnInit {
    btn_title: string = '保存';
    loading: boolean = false;
    constructor(
        public coach$: CoachService
    ) { }

    ngOnInit() {
        this.coach$.widget.timeLen = 30;
    }

    save() {
        this.loading = true;
        this.coach$.updateCoach().subscribe(res => {
            this.btn_title = '保存成功';
            setTimeout(() => {
                this.loading = false;
                this.btn_title = '保存';
            }, 1000);
        });
    }

    foxPickerTimeChangeStart(e: any) {
        this.coach$.widget.time.start = {
            ...e.value,
            label: e.label
        };
    }

    foxPickerTimeChangeEnd(e: any) {
        this.coach$.widget.time.end = {
            ...e.value,
            label: e.label
        };
    }

    foxPickerTimeLenChange(e: any) {
        this.coach$.widget.timeLen = e.value;
    }
}
