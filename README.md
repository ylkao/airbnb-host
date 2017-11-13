# airbnb-host
SEE LIVE WEBSITE HERE: airbnb-host.herokuapp.com

OVERVIEW <br />
I used JavaScript, Bootstrap, and PHP to create a website, Airbnb for Hosts, to visualize the data and perform price estimation, bookings optimization, and popularity calculations. I wrote several PHP scripts to process the data we were given and generate JSON files ready-for-use, so I wouldn't have to do the calculations every time the page loaded. I then used Bootstrap for the layout of the website and JavaScript to do animations, display the data as Google Charts, and process PHP forms and input.

DELIVERABLES <br />
1. Visualize the data: see DATA section of website 
- Pie chart of Available Amenities: top 10 most listed amenities and their frequency
- Bar chart of Most Frequently Used Words in Reviews: most used words and their frequency, excludes small and irrelevant words like "I", "a", "San Francisco", "room", etc.
- Scatter plot of Number of People Accommodated vs, Price: number of people that can be accommodated given a certain listing and the listing's price in dollars 
2. Price Estimation: see SERVICES section of website
- Calculates the estimated weekly income by finding the nearest locations, multiplying price by number of reviews per week, and taking the average
- Assumptions: number of reviews = number of bookings
- One month = 4 weeks (divided reviews per month by 4 to get reviews/bookings per week)
3. Bookings Optimization: see SERVICES section of website
- Calculates the ideal price per night to maximize bookings by finding the nearest locations, multiplying price by number of reviews, and taking the average 
- Assumptions: number of reviews = number of bookings
- Reasoning: use nearby locations, the ones with the most number of bookings should be weighted more

BONUS <br />
1. Animate: 
- see DATA section "carousel" of charts
- glyphicon images and service tool boxes slide in upon first loading the web page
2. Investment: see DATA->BONUS subsection
 -Calculates the best place to invest $100 million using value (based off price and number of reviews) and estimated monthly income (similar to price estimation)
- Assumption: median home price in San Francisco is $841,600
3. Popularity: see DATA->BONUS subsection
- Calculates the most popular neighborhood in San Francisco based off of review_ratings or the zipcode with the highest average rating
- Note: ignored negligible amount of listings without a zipcode and single Chicago listing
- Found the neighborhood based off of zipcode

See more details below.

BACK-END <br />

Getting the Data <br />
- listings_all.json: Initially, I tried to download all the data at once and had trouble compressing everything into a file that wouldn't crash my computer. To create listings_all.json, containing all the listings, I first deleted all information I wouldn't be using and then used an online converter, http://www.convertcsv.com/csv-to-json.htm to create  a JSON file from the CSV file. 
- reviews_clean.txt: I wanted to count the most frequently used words in reviews so hosts would have a better idea of what guests are looking for. To do this, I needed to create a large string of all the reviews and process that string. Reading from a csv file lead to memory issues with my code, so I first used Excel to remove small words, verbs, and irrelevant words  like, "I, the, is, am, San Francisco, etc." and reduce the file size. I then copied and pasted everything into a .txt file and used PHP to process it.
- reviews.json, amenities.json, ratings.json, price_people.json, words.json: Holds all the data needed for quick processing on the live website. See below for more information.

Creating the Data <br />
- price_people.php: This PHP script generates the data needed for the scatter chart of number of people accommodated vs. price per night. I iterated through all the listings in listings_all.json and created an array of array pairs to hold the "accommodates" and "price" information. I then stored this array in price_people.json for website use.
- amenities.php: This PHP script generates the data needed for the pie chart of the amenities and the frequency at which they show up in listings. I iterated through all the listings in listings_all.json, and for each listing, I converted the string of amenities into an array. Then, for each amenity, I added it into an associative array mapping the name of the amenity to a starting frequency of 1, or if it was already in the array, I incremented the frequency count. I then took the top 10 most frequent amenities and stored those as an array in amenities.json for website use.
- reviews.php: This PHP script generates the data needed for the reviews bar chart of words to frequency on the website. I read data from reviews_clean.txt line by line to avoid memory issues, and I split each line into an array of words. For each word, I added it to an associative array mapping the word to a starting frequency of 1, or it it was already in the array, I incremented the frequency count. I then took the top 24 most frequently used words and stored that data in reviews.json for website use.
- ratings.php: This PHP script generates the data needed to calculate the average rating based on zipcode. I iterated through all the listings in listings_all.json and used an associative array to map zipcode to the average rating in that zipcode. To do this, I first mapped zipcode to an array containing all the "review_scores_rating" for each listing in that zipcode. I then used the array_sum and count function to average the value for that array and get the average rating. I excluded listings that did not have a zipcode, of which there were about 110, since I deemed them inconsequential out of the 8500+ listings available. I also noticed a Chicago zipcode in my array and removed that as well, since I only wanted to focus on San Francisco. Finally, I stored this array in ratings.json for website use.

Processing the Data <br />
- price_estimation.php: Price estimation. This PHP script calculates the weekly average income the homeowner can make with Airbnb given a geo-location (latitude and longitude) from user-entered input from the webpage, index.php, I first validate the input by making sure it is numeric, then I create an array of the 10 nearest locations by iterating through all the listings in listings_all.json, calculating their distance using the haversine formula, and updating the array as needed with the listings that have the shortest distance from the given geo-location. I then iterate through the array of the 10 nearest locations and find their price per night and number of bookings per week using reviews_per_month, as I assumed number of reviews = number of bookings. My reasoning overall was to base my price off of nearby locations, calculate their average income based off price and number of bookings, and use this as my estimate.
- bookings_optimization.php: Bookings Optimization. This PHP script calculates the ideal price per night that will yield maximum bookings/revenue given a geo-location (latitude and longitude) from user-entered input from the webpage, index.php, I first validate the input by making sure it is numeric, then I create an array of the 10 nearest locations by iterating through all the listings in listings_all.json, calculating their distance using the haversine formula, and updating the array as needed with the listings that have the shortest distance from the given geo-location. I then iterate through the array of the 10 nearest locations and multiply their price by the number of reviews, which I equated to number of bookings. I then averaged this price and returned it as the ideal price. My reasoning overall was to base my price off of nearby locations, especially those with a lot of reviews, because that means more people booked that property, and the aim is to maximize bookings. Again, I assumed number of reviews was equivalent to number of bookings.
- calc_popularity.php: BONUS: Popularity. This PHP script calculates the average rating given a valid zipcode in San Francisco from user-entered input from the webpage, index.php. I read data from the already created reviews_rating.json file and simply pull the average rating from that associative array of zipcodes to average ratings. I also looked at the zipcode with the highest average reviews, found the neighborhood, and identified it as the neighborhood with the most positive reviews.
- investment.php: BONUS: Investment. This PHP script calculates the best places to invest. I first iterate through all the listings in listings_all.json and create an associative array of the "value" mapped to a listing, where "value" is calculated by multiplying the price by a listing's number of reviews (assumed this was equivalent to number of bookings). I then found the listings with the highest values and printed out the number of months it would take them to break even. To calculate the break even point, I used my "estimate weekly income" function but in months to find the estimated monthly income. Assuming the average home in San Francisco costs $841,000, I calculated the number of properties one can purchase with $100 million. I then multiplied this by the average monthly income per property. Finally, I divided my $100 million investment by this monthly income to calculate how many months it would take to break even. I used the property with the highest value and low break even point as the location to invest in. There were locations with similar values but I only listed the first on the website, Potrero Hill.

WEBSITE: FRONT-END
- index.php: Modified Bootstrap template (credits: w3schools) to build a single-page web app, containing all 3 data visualizations in the DATA section, price estimation, bookings optimization, and (bonus) popularity calculations in the SERVICES section, as well as an embedded Google Map of San Francisco for easy searching, a CONTACT section, and other graphics for aesthetics.

About: <br />
- Describe the purpose of the web app -- to help hosts create the perfect Airbnb listing -- and how DATA and SERVICES were implemented.

Data: <br />
- Visualizations: Pie chart of amenities and their frequency, scatter plot of the number of people accommodated vs. price per night, and a bar chart of the most commonly used words in reviews. 
- Reasoning: I included this data because I thought it was both interesting and relevant for hosts. They can learn about the most common amenities available in current listings by looking at the pie chart and thus determine what kinds of amenities they should include in their own listing. 
- They can also see what guests notice the most about a booking based off of the most common words used in reviews. For example, "location" and "clean" are among the top 3 words used, which implies that guests appreciate, or at least notice, those two things the most. 
- They can also get a general idea of pricing by looking at the scatter plot. The prices have lots of variation, but in general, the more people a listing can accommodate, the higher the price per night.
- Source: Google Charts API

Services: <br />
- Price Estimation: Given a latitude and longitude, displays the estimated average weekly income a host can make with Airbnb. Sends user input to price_estimation.php for processing.
- Popularity: Given a zipcode,  displays the average rating of that area. Sends user input to calc_popularity.php for processing.
- Bookings Optimization: Given a latitude and longitude, displays the optimal price to maximize bookings/revenue. Sends user input to bookings_optimization.php for processing.
- embedded Google Maps for a visual of the San Francisco area


Contact: <br />
- Contact form for users of the website to leave feedback (Note: purely for visual purposes)
- Contact information (Note: purely for visual purposes, not real information)

FILE ORGANIZATION <br />
Webpage <br />
- index.php the home page of the website
- bookings_optimization.php performs the calculation for bookings optimization
- price_estimation.php performs the calculation for price estimation
- calc_popularity.php performs the calculation for popularity/average rating
- composer.json, vendor folder used to build the Heroku app

Generating data: <br />
- amenities.json generates the data for amenities.json for the pie chart
- investment.php calculates where best to invest $100 million in San Francisco
- ratings.php generates the data for  ratings.json for ​the average rating tool
- reviews.php generates the data for reviews.json and words.json for the bar chart
- price_people.php generates the data for price_people.json for the scatter plot

Data: <br />
- amenities.json holds the data needed to create a pie chart of amenities and frequency
- listings_all.json holds all the relevant data for Airbnb listings in San Francisco
- reviews.json holds the data needed to create a bar chart of the most frequently used words in reviews
- review_clean.txt holds all the reviews with small/obvious words like "it", "San Francisco", and "a" removed
- ratings.json holds all the zipcodes matched to their average rating
- price_people.json holds the data needed to create a scatter chart of the number of people accommodated vs. nightly price
- words.json holds all the words in reviews mapped to their frequencies
