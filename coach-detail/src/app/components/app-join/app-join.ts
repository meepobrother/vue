import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { CoachService } from '../coach.service';

@Component({
    selector: 'app-join',
    templateUrl: './app-join.html',
    styleUrls: ['./app-join.scss']
})
export class AppJoin implements OnInit {
    groups: any[] = [];
    user: any = {
        description: '',
        mobile: '',
        code: ''
    };
    widget: any = {
        items: []
    };

    loading: boolean = false;
    constructor(
        public coach: CoachService
    ) { }

    ngOnInit() {
        this.coach.getSkillGroup().subscribe((res: any) => {
            this.groups = res.groups;
            this.widget.items = this.groups;
            const skill = res.skill;
            this.user = res.user;
            this.coach.joinForm.mobile = this.user.mobile;
            this.coach.joinForm.title = skill.title;
            this.coach.joinForm.desc = skill.desc;
            this.coach.joinForm.fee = skill.fee;
            if (this.groups.length > 0) {
                this.groups[0].open = true;
            }
        });
    }

    textareaChange(e: any) {
        console.log(e);
    }

    post() {
        this.loading = true;
        this.coach.skillJoin(this.coach.joinForm).subscribe(res => {
            setTimeout(() => {
                this.loading = false;
                this.cancel();
            }, 1000);
        });
    }

    cancel() {
        this.coach.showJoin = false;
    }

    getCode() {

    }
    onSelectTag(e: any) {
        this.coach.joinForm.setting.push({
            title: e.title
        });
    }
}
