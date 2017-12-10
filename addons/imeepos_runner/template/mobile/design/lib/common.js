/**
 * 公用模块
 * Created by xingjie on 2016/12/8.
 */
;
var module = {
    /**
     * ajax请求
     * 传入Object类型参数，url必传，可传入data,type,async
     * 返回数据
     * */
    getData: function(o) {
        var data;
        $.ajax({
            url: o.url,
            data: o.data || {},
            async: o.async || false,
            type: o.type || 'post',
            success: function(d) {
                data = d;
            },
            error: function() {

            }
        });
        return data
    },


    /**
     * ajax请求   --带回调函数
     * 传入Object类型参数，url必传，callback必传，可传入data,type,async
     * */
    ajax: function(o) {
        $.ajax({
            url: o.url,
            data: o.data || {},
            async: o.async || true,
            type: o.type || 'post',
            dataType: o.dataType || 'json',
            success: o.callBack,
            error: function() { console.log('请求失败---') }
        });
    },


    /**
     * 获取地址栏参数
     * 单个获取
     * 接收nama
     */
    getSearch: function(nameS) {
        var reg = new RegExp("(^|&)" + nameS + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    },


    /***
     * 获取xxxx-xx-xx xx:xx格式的时间
     * 接受一个时间戳
     * 返回该格式时间字符串
     * ***/
    getNewDate: function(date, type) {
        var gmtCreate = new Date(date),
            y = gmtCreate.getFullYear(),
            m = (gmtCreate.getMonth() + 1) <= 9 ? '0' + (gmtCreate.getMonth() + 1) : (gmtCreate.getMonth() + 1),
            d = gmtCreate.getDate() <= 9 ? '0' + gmtCreate.getDate() : gmtCreate.getDate(),
            h = gmtCreate.getHours() <= 9 ? '0' + gmtCreate.getHours() : gmtCreate.getHours(),
            mi = gmtCreate.getMinutes() <= 9 ? '0' + gmtCreate.getMinutes() : gmtCreate.getMinutes();
        if (type == 'zh') {
            date = y + '年' + m + '月' + d + '日 ' + h + ':' + mi;
        } else {
            date = y + '-' + m + '-' + d + ' ' + h + ':' + mi;
        }
        return date
    },


    /**
     * 数字转换2位小数
     */
    toFixedTwo: function(num) {
        num = Number(num)
        if (isNaN(num)) {
            return ''
        } else {
            return num.toFixed(2)
        }
    },


    /* *
     * 转义时间
     * 接受时间，返回一个对象
     * 对象包含天，小时， 分钟， 秒
     */
    conversionDate: function(date) {
        var _this = this
        var d = parseInt(date / 86400000)
        var h = parseInt((date - d * 86400000) / 3600000)
        var m = parseInt((date - d * 86400000 - h * 3600000) / 60000)
        var s = parseInt((date - d * 86400000 - h * 3600000 - m * 60000) / 1000)
        return {
            time: date,
            d: d,
            h: _this.conversionTime(h),
            m: _this.conversionTime(m),
            s: _this.conversionTime(s)
        }
    },
    /*
     * 接受一个数字n
     * 如果小于10， 返回'0n'， 否则返回本身
     */
    conversionTime: function(t) {
        if (t < 10 && t >= 0) {
            t = '0' + t
        }
        return t
    },


    /**
     * 转义店铺等级
     * */
    getLevel: function(level, senior) {
        if (level === 'LEVEL1') {
            return '合伙人'
        } else if (level === 'LEVEL3') {
            return '客户经理'
        } else if (level === 'LEVEL4') {
            return '旗舰店'
        } else if (level === 'LEVEL8' && senior) {
            return '创业店'
        } else {
            return '会员店'
        }
    },


    /***
     * 公用头部
     * 左边后退按钮（公共），中间提示文字，分为右边带小icon和不带小icon的样式(特殊编辑页面)
     * module.headerList( {type :  'withIcon'  ;  ele  :  '.model-date'  ;  txt  :  '店铺设置'});   调用方法
     * type:regular 没有右边的icon   ;  withIcon 有导航分类图标  edit 编辑按钮
     * .model-date   父容器class名
     * o.title   页面title
     * o.list  下拉列表数组对象
     * o.url  返回箭头跳转指定页面地址
     * */
    headerList: function(o) {
        var _this = this
        if (o && o.type) {
            // 容器
            o.url = o.url ? o.url : '';
            var module = ('<div class="model-header"><span class="header-back" data-back="' + o.url + '"><i class="iconfont icon-jiantou-copy-copy"></i></span><h3 class="header-tit">' + o.title + '</h3>');
            if (o.type == 'regular') { //常规头部
                module += '<a class="header-home"><i></i></a>' + '</div>';
            } else if (o.type == 'withIcon') { //带下拉头部
                var ul = '<ul class="select-list hide" onclick="selectClick(event);">';
                for (var i = 0; i < o.list.length; i++) {
                    if (o.list[i].icon) {
                        var isActive = o.list[i].isActive ? 'active' : '';
                        ul += '<li data-type="' + o.list[i].type + '" class="' + isActive + '">' +
                            '<i class="iconfont ' + o.list[i].icon + '"></i>' +
                            '<span>' + o.list[i].text + '</span></li>'
                    } else {
                        ul += '<li data-type="' + o.list[i].status + '">' +
                            '<span>' + o.list[i].text + '</span></li>'
                    }
                }
                ul += '</ul>';
                module += ('<a class="header-home"><i class="iconfont icon-liebiao-copy"></i></a>' + ul + '</div>');
            } else if (o.type == 'edit') { //带编辑头部
                module += '<a class="header-home"></a>' + '</div>';
            }
        }
        // 挂在到元素上
        $(o.ele).html(module);
        /**  下拉选线  **/
        //点击显示
        $('a.header-home').click(function() {
            event.stopPropagation();
            if ($('ul.select-list').hasClass('hide')) {
                $('ul.select-list').removeClass('hide');
            } else {
                $('ul.select-list').addClass('hide');
            }
        });
        // 点击外部隐藏
        $('html').click(function(e) {
            if (!$('ul.select-list').hasClass('hide')) {
                $('ul.select-list').addClass('hide');
            }
        });
        /***
         * 公用头部
         * 后退按钮功能，如果没有后退的页面则刷新当前页面
         * */
        $('.header-back').click(function() {
            var backUrl = $(this).data('back');
            var historyNumber = _this.getSearch('historyNumber')
            var backNumber = historyNumber ? 0 - Number(historyNumber) : -1;
            if ((navigator.userAgent.indexOf('MSIE') >= 0) && (navigator.userAgent.indexOf('Opera') < 0)) { // IE
                if (history.length > 0) {
                    backUrl ? location.href = backUrl : window.history.go(backNumber);

                } else {
                    window.opener = null;
                    window.history.go(0);
                }
            } else { //非IE浏览器
                if (navigator.userAgent.indexOf('Firefox') >= 0 ||
                    navigator.userAgent.indexOf('Opera') >= 0 ||
                    navigator.userAgent.indexOf('Safari') >= 0 ||
                    navigator.userAgent.indexOf('Chrome') >= 0 ||
                    navigator.userAgent.indexOf('WebKit') >= 0) {

                    if (window.history.length > 1) {
                        backUrl ? location.href = backUrl : window.history.go(backNumber);
                    } else {
                        location.href = '/shopping/index.htm';
                    }
                } else { //未知的浏览器
                    backUrl ? location.href = backUrl : window.history.go(backNumber);
                }
            }
        })
    },


    /* 横向滚动条
     * $container：父容器
     * $target：滑动的元素
     * moveWhite：起始位置偏移量
     */
    navigationBar: function($container, $target, moveWhite) {
        var moveWhite = moveWhite ? moveWhite : 0
        var navigationObject = {
            $target: $target,
            x_start: 0,
            y_start: 0,
            x_now: 0,
            y_now: 0,
            target_left: 0,
            container_width: $container.width(),
            target_width: $target.width() + moveWhite
        }
        $target.on('touchstart', function(e) {
            var touch = e.originalEvent.targetTouches[0];
            navigationObject.x_start = touch.clientX;
            navigationObject.y_start = touch.clientY;
            navigationObject.target_left = parseInt(navigationObject.$target.css('left'));
        }).on('touchmove', function(e) {
            var touch = e.originalEvent.targetTouches[0];
            navigationObject.x_now = touch.clientX;
            navigationObject.y_now = touch.clientY;
            if (Math.abs(navigationObject.y_now - navigationObject.y_start) < Math.abs(navigationObject.x_now - navigationObject.x_start)) {
                e.preventDefault();
                var countPoint = navigationObject.x_now - navigationObject.x_start + navigationObject.target_left;
                if (countPoint > 0) {
                    navigationObject.$target.css('left', 0);
                } else if (countPoint < navigationObject.container_width - navigationObject.target_width) {
                    navigationObject.$target.css('left', navigationObject.container_width - navigationObject.target_width);
                } else {
                    navigationObject.$target.css('left', countPoint);
                }
            }
        })
    },


    /* 
     * 点击跳转页面
     * 非a标签link跳转，通过data-link属性的click事件跳转， 如果是a标签则阻止默认跳转
     * 通过添加class="unclick"，可以阻止跳转
     * 在页面完全加载完成后调用该方法，会给所以包含data-link的元素添加一个click跳转事件（！！重复调用会重复绑定，需要先解绑）
     *  */
    bindToLink: function() {
        $('[data-link]').on('click', function(e) {
            e.preventDefault()
            e.stopPropagation()
            if ($(this).data('link') && $(this).data('link') != '' && !$(this).hasClass('disabled')) {
                window.location.href = $(this).data('link')
            }
        })
    },


    /* 订单列表的订单号行 */
    orderItem: function(_this) {
        var item = '<li class="order-item">' +
            '<span class="order-number pull-left">订单号:<span class="mark">' + _this.orderno + '</span></span>' +
            '<span class="order-state pull-right">' + _this.state + '</span></li>' +
            '<li class="order-item">' +
            '<span class="order-buyer pull-left">收货人:<span class="mark">' + _this.receiverName + '</span></span>' +
            '<span class="order-date pull-right">' + _this.timer + '</span></li>';
        return item;
    },


    /**
     * list页面渲染
     * ***/
    drawList: function(dataObj) {
        var result = module.getData(dataObj); //ajax请求方法
        dataObj.prevDate = createItem(result, dataObj.prevDate); //遍历item方法
        return result.total <= result.pagesize * dataObj.data.page;
    },
    /**
     * 上拉加载
     * 接受方法名和可加载
     * **/
    pullLoad: function(dataObj) {
        $('.loading-data').remove()
        var dom = '<div class="loading-data hide"><i class="iconfont icon-loading" style="color: red;"></i><span>数据加载中...</span></div>';
        var obj = { page: 1, loading: false, canLoad: true },
            _self = this;
        $('body').append(dom);
        obj.canLoad = this.drawList(dataObj);
        $(document.body).infinite().unbind('infinite');
        $(document.body).infinite().on("infinite", function() { //监听上拉事件
            if (obj.loading) return; //如果正在加载，就不触发
            obj.loading = true; //打开flag
            $('.loading-data').removeClass('hide'); //如果可以触发加载，显示加载动画
            if (obj.canLoad) { //如果到底了
                obj.loading = true; //打开flag
                $('.loading-data').remove();
                $('.demos-content-padded').append('<div class="loading-footer text-center">已经到底了</div>'); //显示已经到底了
                $(document.body).destroyInfinite(); //销毁滚动加载事件
                return;
            } else {
                dataObj.data.page += 1;
                obj.canLoad = _self.drawList(dataObj);
                $('.loading-data').addClass('hide');
                obj.loading = false; //结束加载中...
            }
        });
    },

    /* 订单列表的商品展示行 */
    orderCommodities: function(_this) {
        var item = '<li class="module-order-commodities clearfix">' +
            '<a href="' + _this.detailUrl + '" class="clearfix"><img src=' + _this.orderItems[0].imageUrl + ' class="pull-left"/>' +
            '<div class="commodities-title pull-left">' + _this.orderItems[0].title + '</div>' +
            '<div class="commodities-price text-right pull-right">' +
            '<p><small>￥</small>' + _this.orderItems[0].price + '</p><p>×' + _this.orderItems[0].number + '</p>' +
            '</div></a></li>';
        return item;
    },

    /* 订单列表的合计行 */
    orderContent: function(_this, btnClass) {
        var item = '<li class="order-item module-order-statistics text-right mark">' +
            '<span class="order-total pull-left">合计：￥' + _this.totalPrice + '</span>' +
            '<span class="order-rebate pull-left">返利：<span><small>￥</small>' + _this.pfitPrice + '</span></span>' +
            '<a href = "' + _this.link + '" class="' + btnClass + '">查看物流</a></li>';
        return item;
    },

    /***
     * 商品展示列表
     * 买家商品展示模块
     * 包含imgUrl，title，price，original
     * ***/
    buyerGoodsList: function(o) {
        if (o && o.data.rows) {
            var module = $(o.ele).html(),
                item = ''
            for (var i = 0; i < o.data.rows.length; i++) {
                var _this = o.data.rows[i];
                item += '<dl class="pull-left"><dt><img src="' + _this.imgUrl + '"/></dt>' +
                    '<dd class="title">' + _this.title + '</dd>' +
                    '<dd class="price"><small>￥</small>' + _this.price + '<span><small>￥</small>' + _this.oldPrice + '</span></dd></dl>'
            }
            module += '<div class="module-goods-list clearfix">' + item + '</div>'
            $(o.ele).html(module)
        }
    },

    /***
     * 品牌分类
     * 买家选择品牌时每个品牌logo模块
     * 包含imgUrl和title
     * **/
    brandCategoryList: function(o) {
        if (o && o.data.rows) {
            var module = $(o.ele).html(),
                item = ''
            for (var i = 0; i < o.data.rows.length; i++) {
                var _this = o.data.rows[i];
                item += '<div class="brand-item text-center pull-left">' +
                    '<img src="' + _this.imgUrl + '"/>' +
                    '<p class="">' + _this.title + '</p>' +
                    '</div>'
            }
            module += '<div class="module-brand-list clearfix">' + item + '</div>'
            $(o.ele).html(module)
        }
    },

    /**
     * 确认订单
     * 购物车点击去结算页面
     * 包含发货仓库和rows数组组合的数组，ele,
     * **/
    sureOrderList: function(o) {
        if (o && o.data) {
            var module = ''
            for (var i = 0; i < o.data.length; i++) {
                var item = '<li><p class="order-name"><span>订单' + (i + 1) + '</span><span>' + o.data[i].warehouse + '</span></p></li>',
                    money = 0;
                for (var j = 0; j < o.data[i].rows.length; j++) {
                    var _this = o.data[i].rows[j];
                    item += this.orderCommodities(_this);
                    money += parseInt(_this.price);
                }
                module += '<ul class="module-order">' + item + this.sureOrderContent(money, _this.freight) + '</ul>'
            }
            $(o.ele).html(module)
        }
    },

    /* 确认订单单个订单合计行 */
    sureOrderContent: function(money, freight) {
        var item = '<li class="order-amount"><p>订单金额：</p><p><span>¥' + money + ' （含运费：¥' + freight + '）</span></p></li>';
        return item
    }

};

//页面完成载入后执行
window.onload = function() {
    //商品列表模块高度
    $('dt> img').height($('dt > img').width());
    //底部logo
    $('body').append('<div class="foot-logo"><img src="https://imgs2.mxthcdn.com/w/I272cbok1i6473055738_zkeoBq.png"></div>')
};