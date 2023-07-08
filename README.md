
## How to set up the mortgate calculator app

- `composer install`
- create a database (name like mortgage) with utf8_general_ci collation
- `cp .env.example .env` copy and update env with database variables e.g change DB_DATABASE=laravel to the database you created (like mortgage)
- `php artisan key:generate` to generate key in the env
- `php artisan migrate` migrate the database structure
- `php artisan serve` inorder to run the server locally
- there is an api which generate loan schedule `POST api/calculate-mortgage`
    - these are sample parameters with loan amount 10,000, Interest Rate 5%, Term 10 years and Extra Payment Monthly 50
        ```
        {
            "amount": 10000,
            "interest": 5,
            "term": 10,
            "extra": 50
        }
        ```
    - these are sample output schedule
        ```
        {
            "schedule": [
                {
                    "principal": "64.40",
                    "interest": "41.67",
                    "balance": "9,885.60",
                    "remainingTerm": 79
                },
                {
                    "principal": "64.67",
                    "interest": "41.40",
                    "balance": "9,770.93",
                    "remainingTerm": 78
                },
                {
                    "principal": "64.94",
                    "interest": "41.13",
                    "balance": "9,655.99",
                    "remainingTerm": 77
                },
                {
                    "principal": "65.21",
                    "interest": "40.86",
                    "balance": "9,540.78",
                    "remainingTerm": 76
                },
                {
                    "principal": "65.48",
                    "interest": "40.59",
                    "balance": "9,425.30",
                    "remainingTerm": 75
                },
                .....
                {
                    "principal": "87.97",
                    "interest": "18.10",
                    "balance": "455.59",
                    "remainingTerm": 4
                },
                {
                    "principal": "88.34",
                    "interest": "17.73",
                    "balance": "317.25",
                    "remainingTerm": 3
                },
                {
                    "principal": "88.71",
                    "interest": "17.36",
                    "balance": "178.54",
                    "remainingTerm": 2
                },
                {
                    "principal": "89.08",
                    "interest": "16.99",
                    "balance": "39.46",
                    "remainingTerm": 1
                },
                {
                    "principal": "39.46",
                    "interest": "16.62",
                    "balance": "0.00",
                    "remainingTerm": 0
                }
            ],
            "monthlyPayment": 106.07,
            "totalInterest": 2385.62,
            "totalTerm": 80,
            "effectiveInterestRate": 23.86
        }
        ```
