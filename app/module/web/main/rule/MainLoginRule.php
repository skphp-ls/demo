<?php
$rule['index'] = array(

    'rule'      =>array(
        'userno'    =>      array(
                'rule'  =>  array('isLen', 1, 10),
                'msg'   =>  '帐号不能为空'
                ),

        'pass'      =>      array(
                'rule'  =>  array('isLen', 1, 10),
                'msg'   =>  '密码不能为空'
            ),

        'vcode'      =>      array(
                'rule'  =>  array('isLen', 1, 10),
                'msg'   =>  '验证码不能为空'
            )
    ),

    'json'     =>array(

        'fail'      =>      array(
                'ret'  =>   false,
                'msg'   =>  '登陆失败'
            ),
        'ok'      =>      array(
                'ret'  =>   true,
                'msg'   =>  '登陆成功'
            )
    )   
);
return $rule;