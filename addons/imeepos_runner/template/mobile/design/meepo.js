/**
 * Created by yexiangmin on 2016/12/28.
 * 验证信息表达式
 */
(function(meepo) {
    var im = {};
    im.getMessage = function(__do, __post){
        console.log('getMessage');
        //初始化
        meepo.util.post(__do ? __do : 'message.getMessage',formsData,function(res){
            var list = res.info;
            list.map(res=>{
                res['mopenid'] = openid;
            });
            var html = meepo.util.html('im-message-list',list);
            $("#im-message-list-container").html(html);
            $("#im-message-list-empty-container").hide();
        },function(res){});
        // 监听最新消息
    }
    meepo['im'] = im;
})(meepo || {});