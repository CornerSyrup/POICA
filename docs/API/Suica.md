# Suica

Version: 1.1
Last Edit: 8 Jan 2021

## POST Handler

| name  | description             |    type     |
| :---: | ----------------------- | :---------: |
|  idm  | IDm code of suica card. | string (16) |

## Respond

in JSON format.

| field  | description         | type  |
| :----: | ------------------- | :---: |
| status | Status of handling. | Enum  |

### Status

#### success status

| code  | action          |
| :---: | --------------- |
|   2   | register (POST) |

#### error status

| code  | reason                |
| :---: | --------------------- |
|  10   | unknown               |
|  11   | unauthorized          |
|  12   | HTTP request method   |
|  13   | Json encoding         |
|  14   | Invalid data supplied |
|  30   | Fail to insert        |
