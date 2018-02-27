<?php

if (!function_exists('route_class')) {
    // 将路由名称转换为 CSS 类名
    function route_class()
    {
        return str_replace('.', '-', Route::currentRouteName());
    }
}

if (!function_exists('make_excerpt')) {
    // 根据内容自动摘录 (SEO)
    function make_excerpt($value, $length = '200')
    {
        $excerpt = preg_replace('/\r\n|\r|\n+/', ' ', $value);

        return str_limit($excerpt, $length);
    }
}

if (!function_exists('model_admin_link')) {
    function model_admin_link($title, $model)
    {
        return model_link($title, $model, 'admin');
    }
}

if (!function_exists('model_link')) {
    function model_link($title, $model, $prefix = '')
    {
        // 获取数据模型的复数蛇形命名
        $model_name = model_plural_name($model);

        // 初始化前缀
        $prefix = $prefix ? "/$prefix/" : '/';

        // 使用站点 URL 拼接全量 URL
        $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

        // 拼接 HTML A 标签，并返回
        return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
    }
}

if (!function_exists('model_plural_name')) {
    function model_plural_name($model)
    {
        // 从实体中获取完整类名，例如：App\Models\User
        $full_class_name = get_class($model);

        // 获取基础类名，例如：传参 `App\Models\User` 会得到 `User`
        $class_name = class_basename($full_class_name);

        // 蛇形命名，例如：传参 `User`  会得到 `user`, `FooBar` 会得到 `foo_bar`
        $snake_case_name = snake_case($class_name);

        // 获取子串的复数形式，例如：传参 `user` 会得到 `users`
        return str_plural($snake_case_name);
    }
}

if (!function_exists('send_sms')) {
    // 发送验证码短信
    function send_sms($mobile)
    {
        $sms = app('easysms');

        try {
            $sms->send($mobile, [
                'content' => '【' . config('app.name') . '】您的验证码是 1234。如非本人操作，请忽略本短信',
            ]);

            $result = [
                'status' => true,
                'data' => null,
            ];
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();

            $result = json_decode($response->getBody()->getContents(), true);

            $result = [
                'status' => false,
                'data' => $result,
            ];
        }

        return $result;
    }
}
