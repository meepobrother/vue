import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import {
    FoxCalendar,
    FoxIcon,
    FoxRange,
    FoxPage,
    FoxPageContent
} from './index';
@NgModule({
    declarations: [
        FoxCalendar,
        FoxIcon,
        FoxRange,
        FoxPage,
        FoxPageContent
    ],
    imports: [
        CommonModule,
        FormsModule
    ],
    exports: [
        FoxCalendar,
        FoxIcon,
        FoxRange,
        FoxPage,
        FoxPageContent
    ],
    providers: [],
})
export class FoxModule { }
