# Functional Specification (notif)

## Database

```mermaid
erDiagram

Notifications }o--|| Users: "belongs to"
```

## Model

```mermaid
classDiagram

Action <|.. ConcreteAction
Action o-- Notification

class Action {
    +user initiator
    +void execute()
}

class Notification {
    +string title
    +string body
    +string icon_path
}
```

## Processes

notification file is a plain text JSON file and will named in `[Session ID].notif.tmp` form.

### Periodical Fetch

```mermaid
sequenceDiagram

participant c as Client
participant s as Server

c->>+s: fetch request
s->>s: look-up notification file
s-->>-c: notification
```

### Read Notification

```mermaid
sequenceDiagram

participant c as Client
participant s as Server

c->>c: display notification
c->>+s: notify read
s->>s: update notification file
```

### Update Action

```mermaid
sequenceDiagram

participant c as Client
participant s as Server

c->>+s: action request
s->>s: process
alt is succeed
    s->>s: update notification file
end
s-->>-c: result
```
