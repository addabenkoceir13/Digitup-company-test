 DigitupCompany API
 =============================
 ## Description
DigitupCompany RESTful API clone.

## Technologies Used 
- Laravel v10
- MySQL v8

## Feature
  - Authentication
  - Task Management
  
### Download Repository


##### Clone this repository
```bash
 git clone https://github.com/addabenkoceir13/Digitup-company-test.git
```
##### Create file .env
```bash
 cp .env.example .env.
```
##### Generate Key Of .env
```bash
 php artisan key:generate.
```
### Create Database && Migration && Seeding

> create  database if not exists in MYSQL

```bash
 php artisan migrate
```
```bash
 php artisan db:seed
```

### Run Project
 Run the project
```bash
 php artisan serve
```

### Account 
Admin 
```bash
email: admin@digitup.dz
password: test@admin123
```
User
```bash
email: user1@digitup.dz
password: test@user123
```
 ### Testing
>  User Testing 

```bash
php artisan test --filter=UserTest
```
> Tasks Testing

```bash
php artisan test --filter=TaskAPITest
```
