# Apply

Version: 1.0
Last Edit: 6 Jan 2021

## GET Handler

| name  | description            |  type  |
| :---: | ---------------------- | :----: |
|  id   | Entry id of form data. | number |

## POST Handler

|          name          | description   | Type  |
| :--------------------: | ------------- | :---: |
| [typ](#post-form-type) | Type of form. | Enum  |
| [frm](#post-form-data) | Form data.    | JSON  |

### POST form type

| value | description    |
| :---: | -------------- |
|  doc  | 証明書等発行願 |

### POST form data

Below are common fields:

| field | description                 |
| :---: | --------------------------- |
|  jfn  | Japanese first name.        |
|  jfk  | Japanese first name kana.   |
|  jln  | Japanese last name.         |
|  jlk  | Japanese last name kana.    |
|  sid  | Student ID.                 |
|  clc  | Class code of the student.  |
|  clt  | Teacher ID of class teacher of the class. |

#### form type: doc

| field | description                                              |
| :---: | -------------------------------------------------------- |
|  dob  | Date of birth.                                           |
|  sta  | Status of the applicant.                                 |
|  pur  | Purpose of apply.                                        |
|  doc  | Copies of documents to apply.                            |
|  ena  | English name of the applicant, optional.                 |
|  lng  | True for English, false for Japanese. Only 1~4 accepted. |
|  grd  | Sub form for applants who applied doc type 3, 4. JSON.   |
|  int  | Sub form for applicants who applied doc type 6. JSON.    |

- field `sta` is enum, which values is
    1. 在校生
    2. 卒業生
    3. 休学者
    4. 退学者
- field `pur` is enum, which value is
    1. 進学
    2. 国家試験
    3. Visa申請手続
    4. 旅行
- field `doc` is array, which index is
    1. 在学証明書
    2. 成績証明書
    3. 卒業証明書
    4. 卒業見込証明書
    5. 勤労学生控除に関する証明書
    6. 留学生学業成績および出席状況調書
    7. 所属機関フォーム

##### sub form: grd

| field | description                                       |
| :---: | ------------------------------------------------- |
|  dep  | Department which the applicant is graduated from. |
|  yer  | Year of graduation.                               |
|  mon  | Month of graduation.                              |
|  pos  | Postal code of the applicant's address.           |
|  add  | Applicant's address.                              |
|  tel  | Phone no. of to contact the applicant.            |

##### sub form: int

| field | description                          |
| :---: | ------------------------------------ |
|  add  | Applicant's address.                 |
|  nat  | Nation of the applicant. ISO 3166-1. |
|  res  | Card number of the resident card.    |
|  sex  | True for male, false for female.     |
|  sta  | Status of stay, default `student`.   |
|  img  | Immigrant data.                      |
|  adm  | Admission data.                      |
|  exp  | Expiration data of stay.             |
|  grd  | Expected graduation date.            |

## Respond

in JSON format.

| field  | description         | Type  |
| :----: | ------------------- | :---: |
| status | Status of handling. | Enum  |

### Status

Positive for success; Negative for fail.

#### success status

| Code  | Action          |
| :---: | --------------- |
|   1   | obtain (GET)    |
|   2   | insert (POST)   |
|   3   | update (PUT)    |
|   4   | delete (DELETE) |

#### error status

| Code  | Error               |
| :---: | ------------------- |
|   0   | Unknown error       |
|  11   | Authentication      |
|  12   | HTTP request method |
|  13   | Json encoding       |
|  21   | Fail to obtain      |
|  22   | Fail to insert      |

### GET respond

extra fields for GET respond.

| field | description | type  |
| :---: | ----------- | :---: |
|  frm  | Form data.  | JSON  |

### GET catalogue respond

extra fields for GET catalogue respond.

| field | description                     | type  |
| :---: | ------------------------------- | :---: |
|  cat  | Entry ids of applied form data. | array |
