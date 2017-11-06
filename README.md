# airbnb-host
FILE ORGANIZATION

Processing data:
-reviews.php generates the data for review_bar.json and review_words.json
-scatter_pie.php generates the data for scatter.json and amenities.json

Data:
-amenities.json holds the data needed to create a pie chart of amenities and frequency
-listings_all.json holds all the relevant data for Airbnb listings in San Francisco
-review_bar.json holds the data needed to create a bar chart of the most frequently used words in reviews
-review_clean.txt holds all the reviews with small/obvious words like "it", "San Francisco", and "a" removed
-scatter.json holds the data needed to create a scatter chart of the number of people accommodated vs. nightly price

Webpage
-index.php the home page of the website
-optimalPrice.php performs the calculation for bookings optimization
-weeklyIncome.php performs the calculation for price estimation
-composer.json, vendor folder used to build the Heroku app
