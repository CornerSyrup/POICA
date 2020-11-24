# Exceptions

## Model

### Authentication

| Exception               | Location           | Code  | Message                                                                 | Inner Exception |
| ----------------------- | ------------------ | :---: | ----------------------------------------------------------------------- | :-------------: |
| AuthenticationException | authenticate_form  |   0   | Authentication process failed, for student id [sid] was not registered. | RecordNotFound  |
| AuthenticationException | authenticate_suica |   0   | Authentication process failed, for suica [idm] was not registered.      | RecordNotFound  |

### DBAdapter

| Exception               | Location          | Code  | Message                                          | Inner Exception |
| ----------------------- | ----------------- | :---: | ------------------------------------------------ | :-------------: |
| Exception               | constructor       |   0   | Fail to connect to database for unknown reason.  |       N/A       |
| RecordNotFoundException | obtain_credential |   0   | Fail to obtain credential with student ID [sid]. |       N/A       |
| RecordNotFoundException | obtain_suica      |   0   | Fail to obtain credential with suica ID [code].  |       N/A       |
| RecordInsertException   | insert_credential |   0   | (internal message generated from pg database)    |       N/A       |

## Controller

|       Exception        | Location | Code  | Message                                                                 | Inner Exception |
| :--------------------: | -------- | :---: | ----------------------------------------------------------------------- | :-------------: |
| RequestMethodException |          |   0   | Sign attempted, but request method is [REQUEST_METHOD] instead of POST. |                 |
|  ValidationException   |          |   0   | Sign in form data is not valid.                                         |                 |
