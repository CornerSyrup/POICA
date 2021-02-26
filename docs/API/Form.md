# Form

Version: 1.1
Last Edit: 8 Jan 2021

## Introduction

Entry point of application form data handling.

## URL

`/forms/`

## Entry Point

| name  | description                              |  type  |
| :---: | ---------------------------------------- | :----: |
|  id   | Entry id of form, from RewriteEngin QSA. | number |

## GET Handler

| name  | description            |  type  |
| :---: | ---------------------- | :----: |
|  id   | Entry id of form data. | number |

as URL form `/forms/:id/`

## POST Handler

|          name          | description   | type  |
| :--------------------: | ------------- | :---: |
| [typ](#post-form-type) | Type of form. | Enum  |
| [frm](#post-form-data) | Form data.    | JSON  |

### POST form type

| value | description    |
| :---: | -------------- |
|  doc  | 証明書等発行願 |

### POST form data

Below are common fields, stored in field named `bc` as JSON:

| field | description                               |  type  |
| :---: | ----------------------------------------- | :----: |
|  fn   | Japanese first name.                      | string |
|  fk   | Japanese first name kana.                 | string |
|  ln   | Japanese last name.                       | string |
|  lk   | Japanese last name kana.                  | string |
|  si   | Student ID.                               | string |
|  cc   | Class code of the student.                | string |
|  ct   | Teacher ID of class teacher of the class. | string |

#### form type: doc

| field | description                                     |  type  |
| :---: | ----------------------------------------------- | :----: |
|  db   | Date of birth. As unix timestamp.               | number |
|  st   | Status of the applicant.                        |  Enum  |
|  pp   | Purpose of apply.                               |  Enum  |
|  dc   | Copies of documents to apply.                   | Array  |
|  en   | English name of the applicant, optional.        | string |
|  lg   | True for English, false for Japanese. Only 1~4. | Array  |
|  gs   | Sub form for applicant who status is 2.         |  JSON  |
|  is   | Sub form for applicants who applied doc type 6. |  JSON  |

- field `st` is enum, which values is
    1. 在校生
    2. 卒業生
    3. 休学者
    4. 退学者
- field `pp` is enum, which value is
    1. 進学
    2. 国家試験
    3. Visa申請手続
    4. 旅行
- field `dc` is array, which index is
    1. 在学証明書
    2. 成績証明書
    3. 卒業証明書
    4. 卒業見込証明書
    5. 勤労学生控除に関する証明書
    6. 留学生学業成績および出席状況調書
    7. 所属機関フォーム

##### sub form: grd

| field | description                             |     type      |
| :---: | --------------------------------------- | :-----------: |
|  dp   | Department the applicant graduated from | string (abbr) |
|  gy   | Year of graduation.                     |    number     |
|  gm   | Month of graduation.                    |    number     |
|  pc   | Postal code of the applicant's address. |    string     |
|  ad   | Applicant's address.                    |    string     |
|  tn   | Phone no. of to contact the applicant.  |    string     |

##### sub form: int

| field | description                                       |  type   |
| :---: | ------------------------------------------------- | :-----: |
|  ar   | Applicant's address.                              | string  |
|  na   | Nation of the applicant. ISO 3166-1, 2 char attr. | string  |
|  rc   | Card number of the resident card.                 | string  |
|  gn   | True for male, false for female.                  | boolean |
|  st   | Status of stay, default `student`.                | string  |
|  id   | Immigrant date, in UNIX timestamp.                | number  |
|  ad   | Admission date, in UNIX timestamp.                | number  |
|  es   | Expiration date of stay, in UNIX timestamp.       | number  |
|  gd   | Expected graduation date, in UNIX timestamp.      | number  |

## Respond

in JSON format.

| field  | description         | type  |
| :----: | ------------------- | :---: |
| status | Status of handling. | Enum  |

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
|  30   | Fail to insert        |
|  31   | Form incomplete       |

### GET respond

extra fields for GET respond.

|         field          | description              | type  |
| :--------------------: | ------------------------ | :---: |
|          frm           | Form data.               | JSON  |
| [typ](#post-form-type) | Type of the application. | Enum  |

### GET catalogue respond

extra fields for GET catalogue respond.

| field | description                     | type  |
| :---: | ------------------------------- | :---: |
|  cat  | Entry ids of applied form data. | array |

fields included in `cat` object array.

|          field          | description                  |  type  |
| :---------------------: | ---------------------------- | :----: |
|           id            | Entry id of the application. | number |
| [type](#post-form-type) | Type of the application.     |  Enum  |
|         status          | Status of the apply process. | binary |
|          date           | Apply date.                  |  date  |

## Implementation

`apply_entry` is the entry point of form apply handling process. Which will only take care of the global exceptions (code 1x) and unknown exceptions(code 0).
The local exception for each request method should be handle in the method handler, and stop bubble up to entry point.
The exceptions from sub-handler of method handlers should be handled in method handler, but not sub-handler.
