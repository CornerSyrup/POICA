# Sign In

Version: 1.1
Last Edit: 11 Feb 2021

## Introduction

Entry point of sign in handling.

This entry have 2 handler, which handle form sign-in and suica sign-in respectively. Where form sign-in could only be requested with script.

The script for form sign-in need to determine what to do after. Like redirect to landing page after receiving success sign-in signal.

## URL

`/signin/`

## Form Handler

| name  | description               |             type             |
| :---: | ------------------------- | :--------------------------: |
|  usr  | Student ID or Teacher ID. | string (5 or 6 numeric char) |
|  pwd  | password.                 |            string            |

## Respond

in JSON format.

| field  | description         | type  |
| :----: | ------------------- | :---: |
| status | Status of handling. | Enum  |

### Status

#### success status

| code  | action          |
| :---: | --------------- |
|   0   | fail to sign in |
|   1   | form sign-in    |
|   2   | suica sign-in   |

#### error status

| code  | reason                |
| :---: | --------------------- |
|  10   | Unknown error         |
|  11   | HTTP request method   |
|  12   | Json encoding         |
|  13   | Invalid data supplied |
|  21   | Account not found     |
|  22   | Suica data not found  |

## Implementation

### Form sign-in

Form sign-in require client side script to determine what to do next. Server side script will not redirect to landing page or else. Client side script should redirect to landing page or other target page after receiving the signal of success sign-in.
