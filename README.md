# Saving quote package

Save user's selected quotes on GBLIE or Intact

## Installation

Install using composer
    - Add this repository as a [vcs source](https://getcomposer.org/doc/05-repositories.md#vcs) using `"url": "https://github.com/jauntin/saving-quote"`
    - `composer require jauntin/saving-quote`

## Usage

`POST /api/v1/quote/progress`
```
{
    "email": "daryna@jauntin.com",
    "data": [
        {
            "key1": "value1"
        }, {
            "key2": "value2"
        }
    ]
}
```

Validation:

```
email - required, email format
data - required, valid json
```

`GET /api/v1/quote/progress/{hash}`

Response example:
```
{
    "email": "daryna@jauntin.com",
    "data": [
        {
            "test": "test"
        }
    ]
}
```

If link was created more than 1 week ago, GET endpoint will return 404 error (link is expired)
