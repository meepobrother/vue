declare const $: any;
declare const FoxUI: any;
const defaults = { baseUrl: '', siteUrl: '', staticUrl: '../addons/imeepos_runnerpro/assets/' };

export class Core {
    options: any = {};
    constructor() {

    }

    init(options) {
        this.options = $.extend({}, defaults, options || {})
    }

    toQueryPair(key, value) {
        if (typeof value == 'undefined') {
            return key
        }
        return key + '=' + encodeURIComponent(value === null ? '' : String(value))
    }

    number_format(number, fix) {
        var fix = arguments[1] ? arguments[1] : 2;
        var fh = ',';
        var jg = 3;
        var str = '';
        number = number.toFixed(fix);
        let zsw = number.split('.')[0];
        let xsw = number.split('.')[1];
        let zswarr = zsw.split('');
        for (var i = 1; i <= zswarr.length; i++) {
            str = zswarr[zswarr.length - i] + str;
            if (i % jg == 0) {
                str = fh + str;
            }
        }
        str = (zsw.length % jg == 0) ? str.substr(1) : str;
        zsw = str + '.' + xsw;
        return zsw
    }

    toQueryString(obj) {
        var ret = [];
        for (var key in obj) {
            key = encodeURIComponent(key);
            var values = obj[key];
            if (values && values.constructor == Array) {
                var queryValues = [];
                for (var i = 0, len = values.length, value; i < len; i++) {
                    value = values[i];
                    queryValues.push(this.toQueryPair(key, value))
                }
                ret = ret.concat(queryValues)
            } else {
                ret.push(this.toQueryPair(key, values))
            }
        }
        return ret.join('&')
    }

    getUrl(routes, params = {}, full = false) {
        routes = routes.replace(/\//ig, ".");
        var url = this.options.baseUrl.replace('ROUTES', routes);
        if (params) {
            if (typeof (params) == 'object') {
                url += "&" + this.toQueryString(params)
            } else if (typeof (params) == 'string') {
                url += "&" + params
            }
        }
        return full ? this.options.siteUrl + 'app/' + url : url
    }

    json(routes, args, callback, hasloading, ispost) {
        var url = ispost ? this.getUrl(routes) : this.getUrl(routes, args);
        var op = {
            url: url,
            type: ispost ? 'post' : 'get',
            dataType: 'json',
            cache: false,
            beforeSend: function () {
                if (hasloading) {
                    FoxUI.loader.show('mini')
                }
            },
            error: function (a) {
                /*alert(JSON.stringify(a));*/
                if (hasloading) {
                    FoxUI.loader.hide()
                }
            }
        };
        if (args && ispost) {
            op.data = args
        }
        if (callback) {
            op.success = function (data) {
                if (hasloading) {
                    FoxUI.loader.hide()
                }
                callback(data)
            }
        }
        $.ajax(op)
    }
}