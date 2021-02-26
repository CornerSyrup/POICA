# Suica

Version: 1.2
Last Edit: 12 Feb 2021

## URL

`/suica/`

## POST Handler

| name  | description             |    type    |
| :---: | ----------------------- | :--------: |
|  idm  | IDm code of suica card. | string(64) |

## Respond

in JSON format.

| field  | description         | type  |
| :----: | ------------------- | :---: |
| status | Status of handling. | Enum  |

### Status

#### success status

| code  | action              |
| :---: | ------------------- |
|   2   | register (POST)     |
|   4   | deregister (DELETE) |

#### error status

| code  | reason                |
| :---: | --------------------- |
|  10   | unknown               |
|  11   | unauthorized          |
|  12   | HTTP request method   |
|  13   | Json encoding         |
|  14   | Invalid data supplied |
|  30   | Fail to insert        |
|  50   | Fail to reset         |
