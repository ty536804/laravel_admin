<?php

\Validator::extend('flow_money', function ($attribute, $value, $parameters) {
        if (is_numeric($value)) {
            if ($value <= 0) {
                return false;
            }
            if ($value % 100 == 0) {
                return true;
            }
        }
        return false;
    });
Validator::extend('select', function ($attribute, $value, $parameters) {
    if($value==""){
        return false;
    }elseif (is_numeric($value)) {
        if ($value > 0) {
            return true;
        }
    }elseif($value!=""){
        return true;
    }
    return false;
});


Validator::extend('password_geshi', function ($attribute, $value, $parameters) {
    $pattern = '/^(?=.*[0-9])(?=.*[a-zA-Z])/';
    if (preg_match($pattern, $value)) {
        return true;
    }
    return false;
});

Validator::extend('phone', function ($attribute, $value, $parameters) {
    $pattern = '/^(0\d{2,3}-\d{7,8})$/';
    if (preg_match($pattern, $value)) {
        return true;
    }
    return false;
});
//最多2位小数
Validator::extend('money', function ($attribute, $value, $parameters) {
    $pattern = '/^\d+(\.\d{1,2})?$/';
    if (preg_match($pattern, $value)) {
        return true;
    }
    return false;
});
Validator::extend('number', function ($attribute, $value, $parameters) {
    $pattern = '/^[0-9]*$/';
    if (preg_match($pattern, $value)) {
        return true;
    }
    return false;
});

//登陆账号格式  字母 数字 下划线 减号  4-16位
Validator::extend('loginname', function ($attribute, $value, $parameters) {
    $pattern = '/^[a-zA-Z0-9_-]{4,16}$/';
    if (preg_match($pattern, $value)) {
        return true;
    }
    return false;
});
//短信验证码格式 4位数字
Validator::extend('sms_number', function ($attribute, $value, $parameters) {
    if (is_numeric($value)) {
        if ($value <= 0) {
            return false;
        }
        if ($value % 5000 == 0) {
            return true;
        }
    }
    return false;
});
//中国手机格式
Validator::extend('zh_mobile', function ($attribute, $value, $parameters) {
    return preg_match('/^(\+?0?86\-?)?((13\d|14[57]|15[^4,\D]|17[678]|18\d)\d{8}|170[059]\d{7})$/', $value);
});

//邮箱验证
Validator::extend('email', function ($attribute, $value, $parameters) {
    $preg_email='/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';
    return preg_match($preg_email,$value);
});