import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { Subject } from 'rxjs/Subject';
export declare class ApiService {
    http: HttpClient;
    static that: any;
    header: HttpHeaders;
    onInit: Subject<any>;
    options: any;
    constructor(http: HttpClient);
    init(options: any): void;
    toQueryPair(key: string, value: string): string;
    toQueryString(obj: any): string;
    getUrl(routes: any, params: any, full?: boolean): string;
    private rad(d);
    getNumber(str: string): number;
    getDistanceByLnglat(lng1: any, lat1: any, lng2: any, lat2: any): number;
    ish5app(): boolean;
    isWeixin(): any;
    post(url: string, post: any): Observable<any>;
    get(url: string): Observable<any>;
    formatDate(date: Date, fmt: string): string;
}
