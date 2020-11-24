# Exceptions

## Model

### Authentication

| Exception | Location | Code  | Message                                             | Inner Exception |
| :-------: | -------- | :---: | --------------------------------------------------- | :-------------: |
| Exception |          |   0   | Credential for student id [sid] was not registered. | RecordNotFound  |
| Exception |          |   0   | Suica [idm] was not registered.                     | RecordNotFound  |

### DBAdapter

|        Exception        | Location          | Code  | Message                                          | Inner Exception |
| :---------------------: | ----------------- | :---: | ------------------------------------------------ | :-------------: |
|        Exception        | constructor       |   0   | Fail to connect to database for unknown reason.  |       N/A       |
| RecordNotFoundException | obtain_credential |   0   | Fail to obtain credential with student ID [sid]. |       N/A       |
| RecordNotFoundException | obtain_suica      |   0   | Fail to obtain credential with suica ID [code].  |       N/A       |
|  RecordInsertException  | insert_credential |   0   | (internal message generated from pg database)    |       N/A       |