<?php

return [

    'title' => 'پنل ادمین ریسمان',

    'heading' => 'ورود',

    'actions' => [

        'register' => [
            'before' => 'or',
            'label' => 'sign up for an account',
        ],

        'request_password_reset' => [
            'label' => 'Forgotten your password?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'آدرس ایمیل',
        ],

        'password' => [
            'label' => 'رمز عبور',
        ],

        'remember' => [
            'label' => 'مرا به خاطر بسپار',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'ورود',
            ],

        ],

    ],

    'messages' => [

        'failed' => 'مشخصات وارد شده اشتباه می باشد',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Too many login attempts',
            'body' => 'Please try again in :seconds seconds.',
        ],

    ],

];
