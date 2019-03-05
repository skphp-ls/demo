<link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_960341_wj0m7h3c50l.css" />
<style type="text/css">
.html{font-size: 16px;}
.page.msg, .page.msg_success, .page.msg_warn, .page.toast {
    background-color: #fff;
}
.page.js_show {
    opacity: 1;
    padding: 3rem;
}
.page {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    opacity: 0;
    z-index: 1;
}
.container, .page {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}
.page, body {
    background-color: #f8f8f8;
}
* {
    margin: 0;
    padding: 0;
}
body {
    font-family: -apple-system-font,Helvetica Neue,Helvetica,sans-serif;
}
.weui-msg {
    text-align: center;
}
.icon-zhengque {
    font-size: 100px;
    color: #09BB07;
}
.icon-cuowu {
    font-size: 10rem;
    color: red;
}
.weui-msg__title {
    margin-top: 3rem;
    margin-bottom: 1rem;
    font-weight: 400;
    font-size: 2rem;
}
.weui-msg__desc {
    font-size: 2rem;
    color: #808080;
}
.weui-btn_primary {
    background-color: #1AAD19;
}
.weui-btn {
    position: relative;
    display: block;
    margin-left: auto;
    margin-right: auto;
    box-sizing: border-box;
    font-size: 2rem;
    text-align: center;
    text-decoration: none;
    color: #FFFFFF;
    line-height: 5rem;
    border-radius: 0.2rem;
}
</style>
<div class="page msg_success js_show">
    <div class="weui-msg">
        <?php
        if($result)
        {
        ?>
            <div class="weui-msg__icon-area"><i class="iconfont icon-zhengque"></i></div>
            <div class="weui-msg__text-area">
                <h2 class="weui-msg__title">操作成功</h2>
                <p class="weui-msg__desc">邀请绑定成功，可以扫码登录代理商系统。</p>
            </div>
        <?php }else{?>
            <div class="weui-msg__icon-area"><i class="iconfont icon-cuowu"></i></div>
            <div class="weui-msg__text-area">
                <h2 class="weui-msg__title">操作失败</h2>
                <p class="weui-msg__desc">请联系管理员，重新绑定。</p>
            </div>  
        <?php }?>
        <div style="margin-top: 2rem;">
            <p class="weui-btn-area">
                <a href="javascript:WeixinJSBridge.call('closeWindow');" class="weui-btn weui-btn_primary">关闭本页</a>
            </p>
        </div>
        <div  style="margin-top: 3rem;font-size: 1rem;color: #808080;">
            <div class="weui-footer">
                <p class="weui-footer__links">
                   湖南巨得锂科技有限公司
                </p>
                <p class="weui-footer__text">Copyright © 2018 jdlxny.com</p>
            </div>
        </div>
    </div>
</div>