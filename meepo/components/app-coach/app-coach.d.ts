import { OnInit } from '@angular/core';
import { CoachService } from '../coach.service';
export declare class AppCoach implements OnInit {
    coach$: CoachService;
    constructor(coach$: CoachService);
    onSelectTag(e: any): void;
    onTextareaChange(): void;
    ngOnInit(): void;
    daySelect(e: any): void;
    onSelect(e: any): void;
    post(): void;
}
