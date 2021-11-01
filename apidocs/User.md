# User

## Object Model:

    - id       (number)
    - name     (string)

## Routes:

PUT /api/user/ - PUT a new object

Returns

- the updated user object on success,
- 404 if the user is not found, with a JSON message
- 400 if the request is invalid, with a JSON message

GET /api/parent/child - GET an object by its key

- the requested user object on success,
- 404 if the user is not found, with a JSON message
- 400 if the request is invalid, with a JSON message

Example Requests:
PUT /api/parent/child:

### Example 1

JSON Body:

```json
{
    "id": 1,
    "data": {
        "id": 1,
        "name": "test"
    }
}
```

Query Params: None

Response: JSON

```json
{
    "id": 1,
    "name": "test"
}
```

### Example 2

JSON Body:

```json
{
    "id": 1
}
```

Query Params: None

Response: JSON

```json
{
    "error": true,
    "message": "Invalid User Data Provided"
}
```

Footnotes:

When updating a user, the ID must be passed at the root of the JSON body, and in the data. If these do not match, the
API will return an error.