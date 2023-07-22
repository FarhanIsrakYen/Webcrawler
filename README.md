QA Doc
Purpose of this task was to create a scrapper that retrieves data from https://rekvizitai.vz.lt/en/company-search/ based on a registration code. 
In order to test this task, follow the given steps please –
•	At first, we need to create database. I’m using DBeaver for DB connection. Here database is “symfony” , user is “root” and password is also “root”  
•	Once done, move to project and run “make build”. Make sure your docker desktop is running. 
•	Later on, run “make up”.  
•	Now go to http://localhost/company/details/. 
•	Now click “Fetch Company Data” button
•	Provide the registration code here and click “Submit”. You can also provide multiple codes. It’ll avoid scrapping the data of turnover of bankrupting companies.
We will get all company details data including financial turnover data. Now you can update, delete or view the data. Also search the required data. Pagination has also been provided. Also the order
•	To view the data, click on “View” 
You can check all the details of company from here
Also you can update company details. You can also delete any comapny
•	From the home, you can do multi search also by providing comma separated registration codes  
