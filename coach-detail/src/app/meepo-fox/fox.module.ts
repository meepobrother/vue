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
    FoxMain
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
    FoxMain
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
