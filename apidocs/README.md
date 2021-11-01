# API Docs

This directory contains the documentation used in the API.

Documents will follow this structure;

```
Object Name

Object Model:
    - key      (type)
    - field_1  (type)
    - field_2  (type)
    - field_3  (type)

Routes:

PUT /api/parent/child/ - PUT a new object
Returns X on success, Y if the object is not found, Z if the request is invalid

GET /api/parent/child - GET an object by its key
Returns X on success, Y if the object is not found, Z if the request is invalid

Example Requests:
PUT /api/parent/child:
JSON Body: {
    "id": 1,
    "field_1": "test"
}
Query Params: None
Response: JSON {
    "id": 1,
    "field_1": "test"
}

Footnotes:
Any footnotes about the object / its routes
```

All errors returned by this API have the boolean `error` set to `true` and an associated `message` string, an example
error response is:

```json
{
    "error": true,
    "message": "Param X was not passed"
}
```

In cases of internal errors, the returned data is undefined, but the status code will always be 500+. When such status
codes are recieved, all returned data should be treated as garbage.