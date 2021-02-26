# Lesson

Version 1.0
Last Edit: 11 Feb 2021

## Introduction

Entry point of lesson info manipulation API.

Accept CRUD actions. (read only for now)

## URL

`/lessons/`

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

Array of lesson object in JSON format, in name of `lessons`.

Teacher object as follow.

| field  | description                  |        type        |
| :----: | ---------------------------- | :----------------: |
|  code  | code of the lesson           | string (\w{2}\d\w) |
| total  | total students of the lesson |       number       |
| attend | no. of students has attended |       number       |
