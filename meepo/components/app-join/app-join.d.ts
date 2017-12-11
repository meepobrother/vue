import { OnInit } from '@angular/core';
import { CoachService } from '../coach.service';
export declare class AppJoin implements OnInit {
    coach: CoachService;
    groups: any[];
    user: any;
    widget: any;
    loading: boolean;
    isInner: boolean;
    constructor(coach: CoachService);
    ngOnInit(): void;
    textareaChange(e: any): void;
    post(): void;
    cancel(): void;
    getCode(): void;
    onSelectTag(e: any): void;
}
