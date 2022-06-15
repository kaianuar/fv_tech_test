## About this app

This is a simple web app created using Laravel 8 that has an API endpoint that will format long addresses into 3 lines as expected by DHL.

## Running locally

To run this app locally fork this repository and run `./vendor/bin/sail up -d`. [Laravel Sail](https://laravel.com/docs/9.x/sail) will automatically boot up a docker container with the necessary environment needed to test this app on your local environment.

## Using the address form

A simple UI form has been created to test that the API endpoint is working correctly. The form can be accessed by opening the `/address-form` URL from localhost. 

## Testing using Postman

The API endpoint can also be tested directly using Postman. The url to use to send a `POST` request is `/api/v1/address/verify`.

In the Postman app, set the Body to `raw` and `JSON` from the dropdown and enter the following json string in the window below it:

    {
        "address1" : "Business Office, Malcolm Long 92911 Proin Road Lake Charles Maine"
    }

Clicking on send will then return the formatted address.

## Disclaimer

Due to the timeframe allocated for this task, the API endpoint doesn't cover every possible usage scenario. For example, it will not handle address that has a total length larger than 90 characters. 

During the development I also had to make some assumptions as how it should behave for certain edge cases. For example, consider the below scenario if this was submitted by the user:

    Address1 : Business Office,
    Address2 : Malcolm Long 92911 Proin Road Lake Charles Maine

Would the expected result be :

    Address1 : Business Office,
    Address2 : Malcolm Long 92911 Proin Road
    Address3 : Lake Charles Maine

or

    Address1 : Business Office, Malcolm Long
    Address2 : 92911 Proin Road Lake Charles
    Address3 : Maine

For simplicity, I chose the latter approach although a user might prefer the first option.



