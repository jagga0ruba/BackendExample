By: João Fardilha
Email: joao.d.fardilha@gmail.com
Date 14/01/2018

Backend Example

Requirements: 
    
    1. Mysql 5.7
    2. php 7.1+
    3. composer

#Installation:

1. Clone the contents of this repository to your server
2. On the root of the project there is a file called DatabaseMigration.sql run it on your MySQL installation/workbench
    
    a) On the config folder there is a database.yaml file that contains the config parameters for the connection to the database, configure them please.
    
3. on the root of the folder run composer install
4. run `./bin/console server:run` or, if you are on Windows `php bin/console server:run`
5. navigate to localhost:8000 you should see a welcoming message.

All the following functions should theoretically work if the parameters are passed as POST, but this was not tested.
Therefore I will provide GET links for these functions.

#Add New Customer

```
http://localhost:8000/Customer/addNew/?FirstName=FIRSTNAME&amp;LastName=LASTNAME&EmailAddress=EMAILADDRESS&amp;Country=COUNTRY&amp;Gender=GENDER
```
Caveats:

* FirstName and LastName shall have a maximum of 15 characters.
* EmailAddress shall be unique (you will get an error if it isn't) and a valid email address
* Country needs to be represented as an International Country Code (example ES for Spain)
* Gender needs to be one of Male | Female | Other 
* All parameters need to be filled (you will get an error if this does not occur)


Result:

A successfull call will return a json string in the following format:
```
{
    "Success":"true",
    "Contents":
    {
        "CustomerId":CUSTOMERID,
        "EmailAddress":EMAILADDRESS
    }
}
```

 

#Edit Customer

```
http://localhost:8000/Customer/editUser/?IdCustomer=IDCUSTOMER&amp;FirstName=FIRSTNAME&amp;LastName=LASTNAME&amp;EmailAddress=EMAILADDRESS&amp;Gender=GENDER&amp;Country=COUNTRY
```
Caveats
* You can opt for not declaring the fields you don't want to change in the URL
* You can also have them empty (this will also not change their value in the db)
* Caveats from Add New Customer apply to Gender, Country and Email Address

Result :

A successful call will return a json string in the following format:
```
{
    "Success":"true",
    "Contents":
    {
        "IdCustomer":IDCUSTOMER,
        "FirstName":FIRSTNAME,
        "LastName":LASTNAME,
        "EmailAddress":EMAILADDRESS,
        "Country":COUNTRY,
        "Gender":GENDER
    }
}
```

#Deposit

```http://localhost:8000/Customer/deposit/?CustomerId=CUSTOMERID&amp;Amount=AMOUNT```

Caveats
* Customer Id needs to exist
* Amount can either be integer or have two decimal places (separated by `,`)

Result

A successful call will return a json string in the following format:
```
{
    "Success":"true",
    "Contents":
    {
        "IdCustomer":IDCOSTUMER,
        "TotalBalance":TOTALBALANCE,
        "BonusBalance":BONUSBALANCE
    }
}
```
Warning: Total Balance includes both Regular Balance and Bonus Balance

#Withdraw

```http://localhost:8000/Customer/withdraw/?CustomerId=CUSTOMERID&Amount=AMMOUNT```

Caveats 
* If you try to withdraw more than available on Regular Balance you shall get the following message 
`This Customer does not have enough balance to withdraw this ammount`

Result

A successful call will return a json string in the following format:
```
{
    "Success":"true",
    "Contents":
    {
        "IdCustomer":IDCOSTUMER,
        "TotalBalance":TOTALBALANCE,
        "BonusBalance":BONUSBALANCE
    }
}
```

 #Report
 
 ```http://localhost:8000/Report/```
 
 Caveats
 * Search Date Not implemented
 
 Result
 
 A successful call will return a json string in the following format:
 
 ```
 {
    "Success":"true",
    "Contents":
        [
            {
                "Country":COUNTRY,
                "No Of Costumers":NUMBEROFCOSTUMER,
                "No Of Deposits":NUMBEROFDEPOSITS,
                "Total Deposit Amount":DEPOSITSTOTAL,
                "No Of Withdrawals":NUMBEROFWITHDRAWALS,
                "Total Withdrawal Amount": WITHDRAWALTOTAL
            },
            {
                "Country":COUNTRY2,
                "No Of Costumers":NUMBEROFCOSTUMER2,
                "No Of Deposits":NUMBEROFDEPOSITS2,
                "Total Deposit Amount":DEPOSITSTOTAL2,
                "No Of Withdrawals":NUMBEROFWITHDRAWALS2,
                "Total Withdrawal Amount": WITHDRAWALTOTAL2
            }
        ]
}
```