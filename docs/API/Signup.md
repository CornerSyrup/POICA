# Sign Up

Version: 1.0
Last Edit: 19 Jan 2021

## Introduction

Entry point of sign up handling.

This entry have 1 handler, which handle sign up form data. Where sign-up form could only be requested with script.

## POST

| name  | description                   |             type             |
| :---: | ----------------------------- | :--------------------------: |
|  usr  | Student ID or Teacher ID.     | string (5 or 6 numeric char) |
|  pwd  | New password for the account. |            string            |
|  jfn  | Japanese first name           |       string (4 char)        |
|  jln  | Japanese last name            |       string (12 char)       |
|  jfk  | First name kana               |       string (30 char)       |
|  jlk  | Last name kana                |       string (30 char)       |

## Respond

in JSON format.

| field  | description         | type  |
| :----: | ------------------- | :---: |
| status | Status of handling. | Enum  |

### Status

#### success status

| code  | action          |
| :---: | --------------- |
|   0   | fail to sign up |
|   1   | sign up succeed |

#### error status

| code  | reason                        |
| :---: | ----------------------------- |
|  10   | Unknown error                 |
|  11   | HTTP request method           |
|  12   | Json encoding                 |
|  13   | Invalid data supplied         |
|  21   | Fail to insert into database. |
