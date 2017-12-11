import { OnInit } from '@angular/core';
import { CoachService } from '../coach.service';
export declare class AppSetting implements OnInit {
    coach$: CoachService;
    btn_title: string;
    loading: boolean;
    constructor(coach$: CoachService);
    ngOnInit(): void;
    save(): void;
    foxPickerTimeChangeStart(e: any): void;
    foxPickerTimeChangeEnd(e: any): void;
    foxPickerTimeLenChange(e: any): void;
}
