# Apply

Version: 1.1
Last Edit: 8 Jan 2021

## Introduction

Entry point of application form data handling.

## Entry Point

| name  | description                              |  type  |
| :---: | ---------------------------------------- | :----: |
|  id   | Entry id of form, from RewriteEngin QSA. | number |

## GET Handler

| name  | description            |  type  |
| :---: | ---------------------- | :----: |
|  id   | Entry id of form data. | number |

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

| field | description                               |
| :---: | ----------------------------------------- |
|  fn   | Japanese first name.                      |
|  fk   | Japanese first name kana.                 |
|  ln   | Japanese last name.                       |
|  lk   | Japanese last name kana.                  |
|  si   | Student ID.                               |
|  cc   | Class code of the student.                |
|  ct   | Teacher ID of class teacher of the class. |

#### form type: doc

| field | description                                              |
| :---: | -------------------------------------------------------- |
|  db   | Date of birth. As unix timestamp.                        |
|  st   | Status of the applicant.                                 |
|  pp   | Purpose of apply.                                        |
|  dc   | Copies of documents to apply.                            |
|  en   | English name of the applicant, optional.                 |
|  lg   | True for English, false for Japanese. Only 1~4 accepted. |
|  gs   | Sub form for applicant who status is 2. JSON.            |
|  is   | Sub form for applicants who applied doc type 6. JSON.    |

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

| field | description                                                |
| :---: | ---------------------------------------------------------- |
|  dp   | Department which the applicant is graduated from, in abbr. |
|  gy   | Year of graduation.                                        |
|  gm   | Month of graduation.                                       |
|  pc   | Postal code of the applicant's address.                    |
|  ad   | Applicant's address.                                       |
|  tn   | Phone no. of to contact the applicant.                     |

##### sub form: int

| field | description                                       |
| :---: | ------------------------------------------------- |
|  ar   | Applicant's address.                              |
|  na   | Nation of the applicant. ISO 3166-1, 2 char attr. |
|  rc   | Card number of the resident card.                 |
|  gn   | True for male, false for female.                  |
|  st   | Status of stay, default `student`.                |
|  id   | Immigrant date, in UNIX timestamp.                |
|  ad   | Admission date, in UNIX timestamp.                |
|  es   | Expiration date of stay, in UNIX timestamp.       |
|  gd   | Expected graduation date, in UNIX timestamp.      |

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
