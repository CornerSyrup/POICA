# Student

Version 1.0
Last Edit: 11 Feb 2021

## Introduction

Entry point of student info manipulation API.

Accept CRUD actions. (read only for now)

## URL

`/students/`

## Get Handler

`/students/:lesson` will return list of student which study that lesson, and this is teacher only api.

e.g. `/students/ih22/`

[see also](#get-response)

## POST Handler

|      name       | description                      |      type       |
| :-------------: | -------------------------------- | :-------------: |
|      code       | Class code of the list           | string (4 char) |
| [attend](#list) | list of sid of attended students |      Array      |
| [leave](#list)  | list of sid of leaved students   |      Array      |

### list

including attend and leave, are array as follow.

| field | description                    |       type       |
| :---: | ------------------------------ | :--------------: |
|  sid  | student id                     | string (5 digit) |
| time  | unix timestamp of attend/leave |      number      |

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

Array of student object in JSON format, in name of `students`.

Teacher object as follow.

| field | description               |       type       |
| :---: | ------------------------- | :--------------: |
|  sid  | student ID                | string (5 digit) |
| fname | First name of the student |      string      |
| lname | Last name of the student  |      string      |
| suica | suica hash, optional      | string (64 char) |
