"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@angular/core");
const http_1 = require("@angular/common/http");
const Subject_1 = require("rxjs/Subject");
let ApiService = class ApiService {
    constructor(http) {
        this.http = http;
        this.header = new http_1.HttpHeaders();
        this.onInit = new Subject_1.Subject();
        this.options = {
            siteUrl: "https://meepo.com.cn",
            baseUrl: "https://meepo.com.cn",
            staticUrl: ""
        };
        this.header.append('Content-Type', 'application/x-www-form-urlencoded');
        this.header.append('Access-Control-Allow-Origin', '*');
        this.header.append('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT');
        this.header.append('Content-Type', 'application/json');
        this.header.append('Accept', 'application/json');
        this.init(window['config']);
    }
    init(options) {
        this.options = Object.assign({}, this.options, options);
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
            }
            else {
                ret.push(this.toQueryPair(key, values));
            }
        }
        return ret.join('&');
    }
    getUrl(routes, params, full = false) {
        routes = routes.replace(/\//ig, ".");
        let url;
        if (params.m) {
            url = `${this.options.siteroot}app/index.php?c=entry&i=${this.options.uniacid}&do=${routes}`;
        }
        else {
            url = `${this.options.siteroot}app/index.php?c=entry&i=${this.options.uniacid}&do=${routes}&m=imeepos_runnerpro`;
        }
        if (params) {
            if (typeof (params) === 'object') {
                url += "&" + this.toQueryString(params);
            }
            else if (typeof (params) === 'string') {
                url += "&" + params;
            }
        }
        return url;
    }
    rad(d) {
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
    get(url) {
        return this.http.get(url, { headers: this.header });
    }
    formatDate(date, fmt) {
        const o = {
            "M+": date.getMonth() + 1,
            "d+": date.getDate(),
            "h+": date.getHours(),
            "m+": date.getMinutes(),
            "s+": date.getSeconds(),
            "q+": Math.floor((date.getMonth() + 3) / 3),
            "S": date.getMilliseconds()
        };
        if (/(y+)/.test(fmt))
            fmt = fmt.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (const k in o)
            if (new RegExp("(" + k + ")").test(fmt))
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    }
};
ApiService = __decorate([
    core_1.Injectable(),
    __metadata("design:paramtypes", [http_1.HttpClient])
], ApiService);
exports.ApiService = ApiService;
//# sourceMappingURL=api.js.map