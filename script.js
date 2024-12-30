let movies = [];
let filteredMovies = [];

async function fetchMovies() {
    try {
        const response = await fetch("fetch_movies.php");
        console.log("Raw response:", await response.clone().text());
        movies = await response.json();
        console.log("Movies fetched:", movies);

        if (movies.length === 0) {
            console.error("No movies found in the database.");
        }

        filteredMovies = [...movies]; // Kopiuj dane do zmiennej filteredMovies
        loadMovies();
    } catch (error) {
        console.error("Error fetching movies:", error);
    }
}

function loadMovies() {
    const catalog = document.getElementById("catalog");
    catalog.innerHTML = "";
    filteredMovies.forEach(movie => {
        const movieCard = `
            <div class="movie-card">
                <img src="${movie.url_pic}" alt="${movie.title}">
                <div class="movie-card-content">
                    <h2><a href="movie.html?title=${encodeURIComponent(movie.title)}">${movie.title}</a></h2>
                    <p>${movie.desc}</p>
                    <p class="release-date">Release Date: ${movie.date}</p>
                </div>
            </div>
        `;
        catalog.innerHTML += movieCard;
    });
}

function searchMovies() {
    const searchInput = document.getElementById("search-input").value.toLowerCase();
    filteredMovies = movies.filter(movie =>
        movie.title.toLowerCase().includes(searchInput)
    );
    loadMovies();
}

function sortMovies() {
    const sortBy = document.getElementById("sort-by").value;
    if (sortBy === "name") {
        filteredMovies.sort((a, b) => a.title.localeCompare(b.title));
    } else if (sortBy === "rating") {
        filteredMovies.sort((a, b) => b.rating - a.rating);
    }
    loadMovies();
}

window.onload = fetchMovies;
