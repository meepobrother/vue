import { Component, OnInit } from '@angular/core';
import { CoachService } from '../coach.service';
declare const QRCode: any;
@Component({
    selector: 'app-field',
    templateUrl: './app-field.html',
    styleUrls: ['./app-field.scss']
})
export class AppField implements OnInit {
    constructor(
        public coach$: CoachService
    ) { }

    ngOnInit() {
        const qrcode = new QRCode('qrcode', {
            text: window.location.href,
            width: 256,
            height: 256,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }
}

