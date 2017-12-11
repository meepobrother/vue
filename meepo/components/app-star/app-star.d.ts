import { OnInit } from '@angular/core';
import { CoachService } from '../coach.service';
export declare class AppStar implements OnInit {
    coach$: CoachService;
    items: any[];
    showDialog: boolean;
    form: any;
    hasPostPermission: boolean;
    constructor(coach$: CoachService);
    ngOnInit(): void;
}
