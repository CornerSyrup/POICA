# Document Issue Application

## Data

This function handles form data and documents supporting the application for certification documents issuing.

### Database

Database design for this function will document here.

```mermaid
```

## Business Logic

### Form Data Handling

```mermaid
sequenceDiagram

participant c as Client
participant s as Server
participant d as Database

c->>c: marshalling
c->>+s: form data
s->>s: verification

s->>+d: data
d->>d: insert/rollback
d-->>-s: error/result
s-->>-c: status

activate c
c->>c: view transition
deactivate c
```

### Payment

```mermaid
sequenceDiagram

participant b as Browser
participant u as User
participant s as Server
participant d as Database
participant f as Financial Institution

b->>b: prompt payment dialogue

activate u
u->>b: payment detail
deactivate u

b->>b: marshalling
b->>+s: payment method

alt Release Service
    s->>+f: payment request
    f->>f: payment handling
    f-->>s: handling result
    deactivate f

    alt is success
        activate s
        s->>+d: result detail
        d->>d: insert/rollback
        d-->>s: result
        s->>b: result
        deactivate s
        b->>b: view transition
    else is fail
        activate s
        s-->>b: request & result
        deactivate s
        b->>b: prompt failure
        activate u
        u->>b: payment method for retry
        deactivate u
    end
else
    b->>b: prompt succeed
    b->>b: view transition
end
```

## Status

### Application

```mermaid
stateDiagram
[*] --> Accepted
Accepted --> Processing

Processing --> Completed
Completed --> [*]

Processing --> Rejected: reason
Rejected --> [*]
```
