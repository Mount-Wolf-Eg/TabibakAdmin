<?php
return [
    'file' => [
        'image' => 'image|mimes:png,jpg,jpeg,svg',
        'mixed' => 'mimes:png,jpg,jpeg,svg,pdf,doc,docx,xlsx,xls,txt,csv,odt,ods,mp4,avi,webm,svg'
    ],
    'string' => [
        'req' => 'required|string|min:3|max:250',
        'null' => 'nullable|string|min:3|max:250',
    ],
    'verification_code' => [
        'req' => 'required|string|min:4|max:4',
        'null' => 'nullable|string|min:4|max:4',
    ],
    'smallString' => [
        'req' => 'required|string|min:1|max:250',
        'null' => 'nullable|string|min:1|max:250',
    ],
    'unique' => 'unique:%s,%s',
    'email' => [
        'req' => 'required|string|email|min:3|max:250|unique:%s,%s',
        'null' => 'nullable|string|email|min:3|max:250|unique:%s,%s',
    ],
    'boolean' => [
        'req' => 'required|boolean',
        'null' => 'nullable|boolean',
    ],
    'url' => [
        'req' => 'required|url',
        'null' => 'nullable|url',
    ],
    'phone' => [
        'req' => 'required|numeric|digits_between:6,20',
        'null' => 'nullable|numeric|digits_between:6,20'
    ],
    'text' => [
        'req' => 'required|string|min:3', // |max:500
        'null' => 'nullable|string|min:3', // |max:500
    ],
    'long_text' => [
        'req' => 'required|string|min:3|max:2000',
        'null' => 'nullable|string|min:3|max:2000',
    ],
    'extra_long_text' => [
        'req' => 'required|string|min:10|max:10000',
        'null' => 'nullable|string|min:10|max:10000',
    ],
    'model' => [
        'req' => 'required|integer|exists:%s,id',
        'null' => 'nullable|integer|exists:%s,id',
        'active_req' => 'required|integer|exists:%s,id,is_active,1',
        'active_null' => 'nullable|integer|exists:%s,id,is_active,1',
    ],
    'soft_deleted_model' => [
        'req' => 'required|integer|exists_with_soft_deleted:%s',
        'null' => 'nullable|integer|exists_with_soft_deleted:%s',
    ],
    'date' => [
        'req' => 'required|date',
        'req_after' => 'required|date|after:%s',
        'null' => 'nullable|date',
    ],
    'datetime' => [
        'req' => 'required|date_format:Y-m-d H:i',
        'null' => 'nullable|date_format:Y-m-d H:i',
    ],
    'day' => [
        'req' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
        'null' => 'nullable|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
    ],
    'time' => [
        'req' => 'required|date_format:H:i',
        'null' => 'nullable|date_format:H:i'
    ],
    'integer' => [
        'req' => 'required|integer|min:0',
        'null' => 'nullable|integer|min:0',
    ],
    'tiny_int' => [
        'req' => 'required|integer|min:0|max:50',
        'null' => 'nullable|integer|min:0|max:50',
    ],
    'numeric' => [
        'req' => 'required|numeric',
        'null' => 'nullable|numeric',
    ],
    'array' => [
        'req' => 'required|array',
        'null' => 'nullable|array',
    ],
    'password' => [
        'req' => 'required|string|min:6|max:250|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        'null' => 'nullable|string|min:6|max:250|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
    ],
    'double' => [
        'req' => 'required|decimal:0.0,1000000000.0',
        'null' => 'nullable|decimal:0.0,1000000000.0',
    ],
];
