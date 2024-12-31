# Movie Catalog Web Application

This web application mimics the operation of a simplified movie catalog, similar to FilmAffinity. The application provides a platform where users can view movie details, rate movies, add comments, and receive recommendations. The application is built using PHP for the backend, HTML/CSS/JavaScript for the frontend, and MATLAB for data analysis and recommendations.

## Features

- **Movie Catalog**: Browse a catalog of movies with detailed information such as poster, release date, and genre.
- **User Registration**: Users can register for an account to interact with the application.
- **Ratings**: Registered users can rate movies, with average ratings displayed.
- **Comments**: Users can leave comments on movies they have watched.
- **Recommendation System**: Based on user ratings, the application offers movie recommendations to users.

## Datasets

The movie catalog is populated using the following datasets:
- **Movielens 10k**: A dataset containing user ratings and movie information. (Available at [GroupLens](http://grouplens.org/datasets/movielens/))
- **IMDB Data**: Additional movie metadata sourced from IMDB. (Available at [IMDB](http://www.imdb.com/))
- **Fictitious User Names**: A list of randomly generated usernames used to simulate user interactions.

## Technologies Used

- **Backend**: PHP (for application logic and user authentication)
- **Frontend**: HTML, CSS, JavaScript (for the user interface and interactivity)
- **Database**: MySQL (for storing user data, ratings, comments, etc.)
- **Data Analysis**: MATLAB (for building a recommendation algorithm based on user ratings)

## Requirements

- PHP 7.4 or higher
- MySQL database
- MATLAB for running data analysis scripts
- Web server (e.g., Apache, Nginx)

