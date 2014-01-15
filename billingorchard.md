# BillingOrchard Wrapper v.2 #

A PHP Class that will interact with the BillingOrchard.com API.
Allows for exchanges with all relevent database tables through Add, Update, View, and Delete Functions.

## Requirements ##

* PHP 4+
* CURL

## Compatibility ##

* v.2 - PHP 4+

## Version History ##

.1 - 
Initial Version

.2 - 
Changed Edit to Update.
View and Delete no longer require primary field definition, can pass ID only

## Features ##

* View BillingOrchard Users
* Add, Update, View and Delete BillingOrchard Clients
* Add, Update, View and Delete BillingOrchard HourlyServices
* Add, Update, View and Delete BillingOrchard Invoices
* Add and View BillingOrchard Payments
* Add, Update, View and Delete BillingOrchard BilledMisc
* Add, Update, View and Delete BillingOrchard MiscItems
* Add, Update, View and Delete BillingOrchard Subscribers
* Add, Update, View and Delete BillingOrchard RecurringBilling

## Installation ##

1. Include BillingOrchard.php
2. From your BillingOrchard.com account settings, retrieve and update the ApiKey and UserID in BillingOrchard.php.

## Documentation ##
All of the primary function for the BillingOrchard.com API are included in this wrapper. Full field definitions can be found at http://billingorchard.com/api_documentation.cfm. Field naming conventions and required fields are included inside each of the Add functions. All data is returned from BillingOrchard's API as a JSON string and is converted to an Array that can then be parsed by the controller. The following convention is universal to all available functions. All bad requests will be returned with an error key with the value set to 1.

Example Add Client:
```
$BillingOrchard = new BillingOrchard();
$client = array(
	'ClientLogin' => 'ClientLoginName',
	'ClientPassword' => 'Password',
	'Client' => 'Actual Client Name',
	'Email' => 'full@emailaddress.com'
);
$BillingOrchard->AddClients($client);
```

Example Update Client:
```
$BillingOrchard = new BillingOrchard();
$client = array(
	'ClientID' => 7893 //Sample,
	'Tel' => '777-777-7777',
	'Address' => '123 Address Street',
	'Address2' => 'APT 123',
	'City' => 'GreatCity',
	'State' => 'GA'
);
$BillingOrchard->UpdateClients($client);
```

Example View Client:
```
$BillingOrchard = new BillingOrchard();
$BillingOrchard->ViewClients(7893); //ID is optional
```

Example Delete Client:
```
$BillingOrchard = new BillingOrchard();
$BillingOrchard->DeleteClients(7893); //ID is required
```

## Sample Responses ##
Invalid API Key:
```
Array
(
    [Message] => Error with apikey
    [error] => 1
)
```

Invalid Request:
```
Array
(
	[Message] => Error selecting service
	[error] => 1
)
```

ViewClients Success:
```
Array
(
    [0] => Array
        (
            [ClientID] => 
            [ClientLogin] => 
            [ClientPassword] => 
            [Client] => 
            [Email] => 
            [Tel] => 
            [Fax] => 
            [Contact] =>
            [Address] => 
            [City] => 
            [State] => 
            [Zip] => 
            [Country] => USA
            [CustomField] => 
            [CustomFieldValue] => 
            [CustomField2] => 
            [CustomFieldValue2] => 
            [CustomField3] => 
            [CustomFieldValue3] => 
            [CustomField4] => 
            [CustomFieldValue4] => 
        )

)
```

ViewClients Error:
```
Array
(
	[Message] => Error with view selection
	[error] => 1
)
```

UpdateClients Success:
```
Array
(
    [Message] => success
    [Response_code] => 0
)

```

UpdateClients Error:
```
Array
(
	[Message] => UpdateFail
	[error] => 1
)
Array(
	[Message] => Missing Required Values
	[error] => 1
)
```

AddClients Success:
```
Array
( 
	[Message] => success 
	[ID] => 12345 
	[Response_code] => 0 
) 
```

AddClients Error:
```
Array(
	[Message] => AddFail
	[error] => 1
)
Array(
	[Message] => Missing Required Values
	[error] => 1
)
```

DeleteClients Success:
```
Array
(
	[Message] => success
	[Response_code] => 0 
) 
```

DeleteClients Error:
```
Array(
	[Message] => DeleteFail
	[error] => 1
)
Array(
	[Message] => Missing Required Values
	[error] => 1
)
```