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
    FoxDialog,
    FoxTags,
    FoxToolbar,
    FoxTabs,
    FoxFull,
    FoxMain,
    FoxPickerTime,
    FoxPickerTimeLen,
    FoxTextarea,
    FoxStar
} from './public_api';

const commponents = [
    FoxCalendar,
    FoxIcon,
    FoxRange,
    FoxPage,
    FoxPageContent,
    FoxDialog,
    FoxTags,
    FoxToolbar,
    FoxTabs,
    FoxFull,
    FoxMain,
    FoxPickerTime,
    FoxPickerTimeLen,
    FoxTextarea,
    FoxStar
];

@NgModule({
    declarations: [
        ...commponents
    ],
    imports: [
        CommonModule,
        FormsModule
    ],
    exports: [
        ...commponents
    ],
    providers: [
        ApiService
    ],
})
export class FoxModule { }
