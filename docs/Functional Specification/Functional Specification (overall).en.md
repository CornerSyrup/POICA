# Functional Specification (overall)

## Model

```mermaid
classDiagram

class DBAdaptor {
    +mysqli appDBConnection()$
}

class DBConnectible {
    <<interface>>
    +mysqli_result obtainRecord()
    +mysqli_result insertRecord()
}
```
