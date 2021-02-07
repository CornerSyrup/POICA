# Department

Version 1.0
Last Edit: 4 Feb 2021

## Introduction

Entry point of teacher info manipulation aip.

Accept CRUD actions. (read only for now)

## GET Handler

### Respond

Array of department object in JSON format.

Department object as follow.

| field | description                          |      type       |
| :---: | ------------------------------------ | :-------------: |
| attr  | Abbreviation of department.          | string (2 char) |
| full  | Full name of department in Japanese. |     string      |
