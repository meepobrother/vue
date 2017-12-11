import { OnInit } from '@angular/core';
import { CoachService } from '../coach.service';
export declare class AppCitys implements OnInit {
    coach: CoachService;
    hots: any[];
    citys: any[];
    isInner: boolean;
    constructor(coach: CoachService);
    ngOnInit(): void;
    goTop(): void;
    selectCity(city: any): void;
}
