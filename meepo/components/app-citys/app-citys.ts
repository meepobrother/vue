import { Component, OnInit, Input } from '@angular/core';
import { CoachService } from '../coach.service';
@Component({
    selector: 'app-citys',
    templateUrl: './app-citys.html',
    styleUrls: ['./app-citys.scss']
})
export class AppCitys implements OnInit {
    hots: any[] = [];
    citys: any[] = [];

    @Input() isInner: boolean = true;
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
        this.coach.onInit();
        this.coach.getHotCitys().subscribe((res: any) => {
            this.hots = res;
        });
    }

    goTop() {
        window.scrollTo(0, 0);
    }

    selectCity(city: any) {
        if (this.isInner) {
            this.goTop();
            this.coach.setCity(city);
            this.coach.showCitys = false;
        } else {
            const params: any = {};
            params['name'] = city.name;
            params['latitude'] = city.latitude;
            params['longitude'] = city.longitude;
            location.href = this.coach.api.getUrl('coach_index', params);
        }
    }
}
