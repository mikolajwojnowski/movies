let currentPage = 1;
let currentLimit = 10;  // Default to 10 movies per page
let loading = false;
let searchQuery = '';
let sortOption = 'name';  // Default sorting by name

// Function to load movies from the server
async function fetchMovies() {
    if (loading) return;  // Prevent multiple simultaneous fetch requests
    loading = true;

    const loadingIndicator = document.getElementById('loading');
    loadingIndicator.style.display = 'block';  // Show loading indicator

    try {
        const response = await fetch(`fetch_movies.php?limit=${currentLimit}&page=${currentPage}&sort=${encodeURIComponent(sortOption)}&search=${encodeURIComponent(searchQuery)}`);
        const movies = await response.json();

        if (movies.length === 0) {
            if (currentPage === 1) {
                document.getElementById('catalog').innerHTML = 'No movies found.';
            } else {
                loadingIndicator.textContent = 'No more movies available.';
            }
        } else {
            movies.forEach(movie => {
                const movieCard = `
                <div class="movie-card">
                    <img src="images/${movie.url_pic}" alt="${movie.title}" onerror="this.onerror=null;this.src='images/default.jpg';">  
                    <div class="movie-card-content">
                        <h2><a href="movie_details.php?movie_id=${movie.id}">${movie.title}</a></h2>
                        <p>${movie.desc}</p>
                        <p class="release-date">Release Date: ${movie.date}</p>
                        <p class="rating">Rating: ${movie.rating || 'No rating yet'}</p>
                    </div>
                </div>
            `;
                document.getElementById('catalog').innerHTML += movieCard;
            });
        }
    } catch (error) {
        console.error('Error fetching movies:', error);
    } finally {
        loading = false;
        loadingIndicator.style.display = 'none';  // Hide loading indicator
    }
}

// Ensure correct sort mapping
function sortMovies() {
    const sortValue = document.getElementById('sort-by').value;
    if (sortValue === 'name-desc') {
        sortOption = 'name-desc';
    } else if (sortValue === 'rating') {
        sortOption = 'rating';
    } else {
        sortOption = 'name';
    }
    currentPage = 1;  // Reset to the first page when sorting changes
    document.getElementById('catalog').innerHTML = '';  // Clear the current catalog
    fetchMovies();  // Reload movies with new sort option
}


// Function to handle search input
function searchMovies() {
    searchQuery = document.getElementById('search-input').value;
    currentPage = 1;  // Reset to the first page when searching
    document.getElementById('catalog').innerHTML = '';  // Clear the current catalog
    fetchMovies();  // Reload movies with new search query
}

// Function to detect when user reaches the bottom of the page (for infinite scrolling)
window.onscroll = function() {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
        currentPage++;
        fetchMovies();  // Load more movies when user scrolls near the bottom
    }
};

// Initial load
window.onload = fetchMovies;
