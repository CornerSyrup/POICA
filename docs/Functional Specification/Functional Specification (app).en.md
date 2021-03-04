# Document Issue Application

## Database

```mermaid
erDiagram

Students }o--|| Applications: applies
Application ||--o| INter_Result_Attend: ""
Students  }|--o{ Classes: "belongs to"

Classes }|--|| Class_Teacher: ""
Teachers ||--o{ Class_Teacher: ""
```

## Processes

### Submission process

```mermaid
sequenceDiagram
autonumber

participant b as Browser
participant s as Server
participant d as Database

b->>b: marshalling
b->>+s: form data
s->>s: verification
s->>s: process

s->>+d: data
d->>d: insert/rollback
d-->>-s: result/error
s-->>-b: status

activate b
b->>b: view transition
deactivate b
```

### Payment process

```mermaid
sequenceDiagram
autonumber

participant b as Browser
participant s as Server
participant d as Database
participant f as Financial Institution

b->>b: prompt payment dialogue
b->>b: marshalling
b->>+s: payment method
s->>+f: payment request
f->>f: payment handling
f-->>-s: handling result

alt is success
    s->>+d: transaction detail
    d->>d: insert/rollback
    d-->>-s: result/error
    s-->>b: result
    b->>b: prompt succeed
else is fail
    s-->>b: result
    b->>b: prompt failure
end

deactivate s
b->>b: view transition
```

## Status

### Application status

```mermaid
stateDiagram
[*] --> Requested
Requested --> Paid
Paid --> Queuing
Queuing --> Processing

Processing --> Completed
Completed --> [*]

Processing --> Rejected: reason
Rejected --> [*]
```

## Client

### page transition

```mermaid
stateDiagram

[*] --> Overview
Overview --> Apply
Apply --> Overview: error
Overview --> Detail
```

### view transition

view transition for apply page

```mermaid
stateDiagram

[*] --> Form
Form --> Confirm: complete and valid
Form --> Form: invalid
Confirm --> Form: edit
Confirm --> Payment: no edit
Payment --> Complete: succeed
Payment --> Payment: fail and retry
Complete --> [*]
```
