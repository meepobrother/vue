import { Injectable, InjectionToken, Inject } from '@angular/core';
import { HttpClient, HttpParams, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { Subject } from 'rxjs/Subject';

@Injectable()
export class ApiService {
    static that: any;
    header: HttpHeaders = new HttpHeaders();
    onInit: Subject<any> = new Subject();

    options: any = {
        siteUrl: "https://meepo.com.cn",
        baseUrl: "https://meepo.com.cn",
        staticUrl: ""
    };
    constructor(
        public http: HttpClient
    ) {
        this.header.append('Content-Type', 'application/x-www-form-urlencoded');
        this.header.append('Access-Control-Allow-Origin', '*');
        this.header.append('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT');
        this.header.append('Content-Type', 'application/json');
        this.header.append('Accept', 'application/json');

        this.init(window['config']);
    }

    init(options) {
        this.options = { ...this.options, ...options };
        console.log(this.options);
    }

    toQueryPair(key, value) {
        if (typeof value === 'undefined') {
            return key;
        }
        return key + '=' + encodeURIComponent(value === null ? '' : String(value));
    }

    toQueryString(obj) {
        let ret = [];
        for (let key in obj) {
            key = encodeURIComponent(key);
            const values = obj[key];
            if (values && values.constructor === Array) {
                const queryValues = [];
                for (let i = 0, len = values.length, value; i < len; i++) {
                    value = values[i];
                    queryValues.push(this.toQueryPair(key, value));
                }
                ret = ret.concat(queryValues);
            } else {
                ret.push(this.toQueryPair(key, values));
            }
        }
        return ret.join('&');
    }

    getUrl(routes, params, full) {
        routes = routes.replace(/\//ig, ".");
        let url = `${this.options.siteroot}app/index.php?c=entry&i=${this.options.uniacid}&do=${routes}&m=imeepos_runnerpro`
        if (params) {
            if (typeof (params) === 'object') {
                url += "&" + this.toQueryString(params);
            } else if (typeof (params) === 'string') {
                url += "&" + params;
            }
        }
        return full ? this.options.siteUrl + 'app/' + url : url;
    }

    private rad(d) {
        return d * Math.PI / 180;
    }

    getNumber(str) {
        str = str.trim();
        if (str === '') {
            return 0;
        }
        return parseFloat(str.replace(',', ''));
    }

    getDistanceByLnglat(lng1, lat1, lng2, lat2) {
        const rad1 = this.rad(lat1);
        const rad2 = this.rad(lat2);
        const a = rad1 - rad2;
        const b = this.rad(lng1) - this.rad(lng2);
        let s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a / 2), 2) + Math.cos(rad1) * Math.cos(rad2) * Math.pow(Math.sin(b / 2), 2)));
        s = s * 6378137.0;
        s = Math.round(s * 10000) / 10000000;
        return s;
    }

    ish5app() {
        const userAgent = navigator.userAgent;
        if (userAgent.indexOf('CK 2.0') > -1) {
            return true;
        }
        return false;
    }

    isWeixin() {
        const ua = navigator.userAgent.toLowerCase();
        const isWX = '' + ua.match(/MicroMessenger/i) === "micromessenger";
        return isWX;
    }

    post(url, post) {
        return this.http.post(url, post, { headers: this.header });
    }
}

