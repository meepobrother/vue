import { OnInit } from '@angular/core';
import { CoachService } from '../coach.service';
export declare class AppField implements OnInit {
    coach$: CoachService;
    constructor(coach$: CoachService);
    ngOnInit(): void;
}
