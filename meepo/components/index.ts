import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {
    AppCitys, AppCoach, AppField, AppJoin, AppSetting, AppStar,
    CoachService
} from './public_api';

import { FoxModule } from 'imeepos-foxui';

@NgModule({
    declarations: [
        AppCitys, AppCoach, AppField, AppJoin, AppSetting, AppStar
    ],
    imports: [
        CommonModule,
        FoxModule
    ],
    exports: [
        AppCitys, AppCoach, AppField, AppJoin, AppSetting, AppStar,
        FoxModule
    ],
    providers: [
        CoachService
    ],
})
export class MeepoComponentsModule { }

export * from './public_api';
