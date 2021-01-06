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
|  gs   | Sub form for applicant who applied doc type 3, 4. JSON.  |
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

| field | description                                       |
| :---: | ------------------------------------------------- |
|  dp   | Department which the applicant is graduated from. |
|  gy   | Year of graduation.                               |
|  gm   | Month of graduation.                              |
|  pc   | Postal code of the applicant's address.           |
|  ad   | Applicant's address.                              |
|  tn   | Phone no. of to contact the applicant.            |

##### sub form: int

| field | description                          |
| :---: | ------------------------------------ |
|  ar   | Applicant's address.                 |
|  na   | Nation of the applicant. ISO 3166-1. |
|  rc   | Card number of the resident card.    |
|  gn   | True for male, false for female.     |
|  st   | Status of stay, default `student`.   |
|  id   | Immigrant data.                      |
|  ad   | Admission data.                      |
|  es   | Expiration data of stay.             |
|  gd   | Expected graduation date.            |

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
