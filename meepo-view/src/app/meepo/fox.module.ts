import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import {
    FoxCalendar,
    FoxIcon,
    FoxRange
} from './index';
@NgModule({
    declarations: [
        FoxCalendar,
        FoxIcon,
        FoxRange
    ],
    imports: [
        CommonModule,
        FormsModule
    ],
    exports: [
        FoxCalendar,
        FoxIcon,
        FoxRange
    ],
    providers: [],
})
export class FoxModule { }
