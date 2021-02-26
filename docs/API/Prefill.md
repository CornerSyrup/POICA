# Student

Version 1.0
Last Edit: 11 Feb 2021

## Introduction

Entry point of student info manipulation API.

Accept CRUD actions. (read only for now)

## URL

`/prefill/`

## Get Handler

`/prefill/user/` will return user basic info.

[see also](#get-user-info)

## Response

### Status

#### success status

| code | action       |
| :--: | ------------ |
|  1   | obtain (GET) |

#### error status

| code | reason                |
| :--: | --------------------- |
|  10  | Unknown error         |
|  11  | Authentication        |
|  12  | HTTP request method   |
|  13  | Json encoding         |
|  14  | Invalid data supplied |
|  20  | Fail to obtain        |

### GET response

Extra fields for GET response.

#### GET User Info

User info object, in name of `data`.

Fields as follow.

| field | description               |       type       |
| :---: | ------------------------- | :--------------: |
|  sid  | student ID                | string (5 digit) |
| fname | First name of the student |      string      |
| lname | Last name of the student  |      string      |
| fkana | First name kana           |      string      |
| lkana | Last name kana            |      string      |
