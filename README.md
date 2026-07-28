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
    "additionalEmails": ["second@jauntin.com", "third@jauntin.com"],
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
additionalEmails - optional, array of emails, max length set by max_additional_emails config, each entry must be unique and different from email
data - required, valid json
```

`additionalEmails` is optional; when omitted, the mailable is only sent to `email`.

`GET /api/v1/quote/progress/{hash}`

Response example (for the request above):
```
{
    "email": "daryna@jauntin.com",
    "data": [
        {
            "key1": "value1"
        }, {
            "key2": "value2"
        },
        {
            "additionalEmails": ["second@jauntin.com", "third@jauntin.com"]
        }
    ]
}
```

If link was created more than 1 week ago, GET endpoint will return 404 error (link is expired)
If data isn't valid endpoint will return 422 status code

## Configuration

Publish the config file with:
```
php artisan vendor:publish --provider="Jauntin\SavingQuote\SavingQuoteServiceProvider"
```

| Key | Env variable | Default | Description |
| --- | --- | --- | --- |
| `expire.unit` | `SAVING_QUOTE_EXPIRE_UNIT` | `day` | Unit passed to `Carbon::add()` to compute expiry |
| `expire.value` | `SAVING_QUOTE_EXPIRE_VALUE` | `7` | Amount of `expire.unit` before a saved quote expires |
| `expire.grace_period` | `SAVING_QUOTE_EXPIRE_GRACE_PERIOD` | `0` | Extra `expire.unit` added on top of `expire.value` before the link actually expires |
| `max_additional_emails` | `SAVING_QUOTE_MAX_ADDITIONAL_EMAILS` | `4` | Max number of entries allowed in `additionalEmails` on create |
| `mailable` | - | `null` | Class name implementing `QuoteProgressAwareMailable` to send after a quote is saved |
| `validator` | - | `null` | Class name implementing `QuoteProgressValidationRules` |

## Upgrading

This package ships its own migrations (auto-loaded via the service provider), so after updating the composer dependency, run:
```
php artisan migrate
```

## Mailable

To send email on project side should be created class which implements: `Jauntin\SavingQuote\Interfaces\QuoteProgressAwareMailable`.

## Validation rules

To validate data depends on main project rules, should be created class which implements
`Jauntin\SavingQuote\Interfaces\QuoteProgressValidationRules`
