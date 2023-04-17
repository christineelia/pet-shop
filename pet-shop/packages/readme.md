Currency Exchange Package for Laravel
This package provides a simple API for converting currency amounts using current exchange rates.

Installation
You can install the package using Composer:


composer require Christine/currency-exchange

After installation, you will need to register the service provider in your config/app.php file:

php
Copy code
'providers' => [
    // ...
    Christine\CurrencyExchange\CurrencyExchangeServiceProvider::class,
],

Usage
To use the currency exchange API, you can make a POST request to the /api/currency-exchange endpoint, passing the amount and currency code in body. The API will return the converted amount in the default currency (Euro).

For example, to convert 100 US dollars to Euros, you can make a request to:


POST /api/currency-exchange
{
    "amount": 100,
    "currency_code": "USD"
}

The response will be in JSON format, with the converted amount and exchange rate:

{
    "success": "true",
    "baseCurrency": "EUR",
    "exchangeCurrency": "USD",
    "rate": 1.1057,
    "exchangedAmount": 6081.349999999999
}

Configuration
By default, the package uses the European Central Bank's daily reference rates to convert currencies. 

Contributing
If you find a bug or have a feature request, please open an issue or submit a pull request on GitHub.

License
This package is open-sourced software licensed under the MIT license.