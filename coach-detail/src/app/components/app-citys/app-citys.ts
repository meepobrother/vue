import { Component, OnInit } from '@angular/core';
import { CoachService } from '../coach.service';
@Component({
    selector: 'app-citys',
    templateUrl: './app-citys.html',
    styleUrls: ['./app-citys.scss']
})
export class AppCitys implements OnInit {
    hots: any[] = [];
    citys: any[] = [];
    constructor(
        public coach: CoachService
    ) { }

    ngOnInit() {
        this.coach.getCitys().subscribe((res: any) => {
            const list = [];
            for (const k in res) {
                list.push({
                    label: k,
                    list: res[k]
                });
            }
            this.citys = list;
        });
        this.coach.getHotCitys().subscribe((res: any) => {
            this.hots = res;
        });
    }

    goTop() {
        window.scrollTo(0, 0);
    }

    selectCity(city: any) {
        this.goTop();
        this.coach.setCity(city);
        this.coach.showCitys = false;
    }
}
