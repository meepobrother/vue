import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ApiService } from './util/api';

import {
    FoxCalendar,
    FoxIcon,
    FoxRange,
    FoxPage,
    FoxPageContent,
    FoxDialog
} from './public_api';

@NgModule({
    declarations: [
        FoxCalendar,
        FoxIcon,
        FoxRange,
        FoxPage,
        FoxPageContent,
        FoxDialog
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
        FoxPageContent,
        FoxDialog
    ],
    providers: [
        ApiService
    ],
})
export class FoxModule { }
