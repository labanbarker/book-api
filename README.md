NOTES:

- main functionality is in /app/Http/Controllers/Api/BookAPIController.php
- api route is in /routes/api.php


DEMO:

Below link should get the first 1-20 results:
https://sacramento-piano-lessons.com/api/1/nyt/best-sellers

To get the next 20 results: 
https://sacramento-piano-lessons.com/api/1/nyt/best-sellers?offset=20

Next 20 results, etc:
https://sacramento-piano-lessons.com/api/1/nyt/best-sellers?offset=40

Get results by an author:
https://sacramento-piano-lessons.com/api/1/nyt/best-sellers?author=Diana%20Palmer

Get results by isbn:
https://sacramento-piano-lessons.com/api/1/nyt/best-sellers?isbn=0373776055

(I noticed the NYT api doesn't work with 2 isbn numbers even though it should according to the documentation)
This link will NOT work:
https://sacramento-piano-lessons.com/api/1/nyt/best-sellers?isbn=0373776055;9780373776054

Get results by title:
https://sacramento-piano-lessons.com/api/1/nyt/best-sellers?title=SEASONS


TESTING:

TEST results on localhost WITHOUT internet connection:
http://127.0.0.1:8000/api/1/nyt/best-sellers?test=1
NOTE: link above ^ needs the app installed locally to work

NOTE: regarding testing the API using some TEST data in storage, the following works. For example:
https://sacramento-piano-lessons.com/api/1/nyt/best-sellers?test=1
However, the test data will only show AS IS - it doesn't sort (by author, title, etc) since the sorting by query parameters ONLY happens on the NYT API side.

