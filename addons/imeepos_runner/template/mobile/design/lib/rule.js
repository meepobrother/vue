/**
 * Created by yexiangmin on 2016/12/28.
 * 验证信息表达式
 */
(function(meepo) {
    var rule = {
        empty: function(obj, txt) { //输入内容为空
            if (obj.val() == '') {
                $.toast(txt, "text"); //弹出验证内容
                return false
            }
            return true
        },
        phone: function(obj, callback) { //手机正则匹配
            var myReg = /^1[34578]\d{9}$/;
            if (!myReg.test(obj.val())) {
                $.toast("手机号码有误", "text");
                return false
            }
            if (callback) {
                return callback();
            } else {
                return true
            }
        },
        card: function(obj) { //微信号正则匹配
            var myReg = /^[-a-zA-Z0-9_|]+$/;
            if (!myReg.test(obj.val())) {
                $.toast("微信号只能字母、数字和'-''_'", "text");
                return false
            }
            return true
        },
        alipay: function(obj) { //支付宝正则匹配
            var myReg = /^(1[34578]\d{9})|([a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+)$/;
            if (!myReg.test(obj.val())) {
                $.toast('支付宝账号有误', 'text');
                return false;
            }
            return true;
        },
        bankcard: function(obj) { //银行卡正则匹配
            var myReg = /^(\d{16}|\d{19})$/;
            if (!myReg.test(obj.val().replace(/\s+/g, ""))) {
                $.toast('银行卡号有误', 'text');
                return false;
            }
            return true;
        },
        idcard: function(obj) { //身份证号码
            var myReg = /^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/;
            if (!myReg.test(obj.val())) {
                $.toast('身份证号码有误', 'text');
                return false;
            }
            return true;
        },
        number: function(obj, text) {
            var reg = /^[0-9]*$/;
            if (!reg.test(obj.val())) {
                $.toast(text, 'text');
                return false;
            }
            return true;
        },
        passWord: function(obj) {
            var reg = /^[a-zA-Z][a-zA-Z0-9!@$_.-]*$/;
            if (!reg.test(obj.val())) {
                $.toast('密码只能由字母开头，输入字母、数字和"!""@""$""_""-""."', 'text');
                return false;
            }
            return true;
        },
        express: function(obj) {
            var flag = this.empty(obj, '请填写回寄物流单号！')
            var flag2 = this.number(obj, '请正确填写物流单号！')
            return flag && flag2
        }
    }
    meepo['rule'] = rule;
})(meepo || {});