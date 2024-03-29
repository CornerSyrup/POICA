# Teacher

Version 1.1
Last Edit: 11 Feb 2021

## Introduction

Entry point of teacher info manipulation API.

Accept CRUD actions. (read only for now)

## URL

`/teachers/`

## Response

### Status

#### success status

| code  | action          |
| :---: | --------------- |
|   1   | obtain (GET)    |
|   2   | insert (POST)   |
|   3   | update (PUT)    |
|   4   | delete (DELETE) |

#### error status

| code  | reason                |
| :---: | --------------------- |
|  10   | Unknown error         |
|  11   | Authentication        |
|  12   | HTTP request method   |
|  13   | Json encoding         |
|  14   | Invalid data supplied |
|  20   | Fail to obtain        |

### GET response

Extra fields for GET response.

Array of teacher object in JSON format, in name of `teachers`.

Teacher object as follow.

| field | description                |       type       |
| :---: | -------------------------- | :--------------: |
|  tid  | Teacher ID, 6 digit.       | number (6 digit) |
| fname | First name of the teacher. |      string      |
| lname | Last name of the teacher.  |      string      |
