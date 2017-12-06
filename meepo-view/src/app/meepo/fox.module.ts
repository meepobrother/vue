import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FoxCalendar, FoxIcon } from './index';
@NgModule({
    declarations: [
        FoxCalendar,
        FoxIcon
    ],
    imports: [CommonModule],
    exports: [
        FoxCalendar,
        FoxIcon
    ],
    providers: [],
})
export class FoxModule { }