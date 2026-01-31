import requests
import json

# Replace with your Bearer token from TMDB
API_TOKEN = ""
HEADERS = {
    "Authorization": f"Bearer {API_TOKEN}"
}

# Example: list of movie queries to search
movie_queries = [
    "Avatar",
    "Avengers: Endgame",
    "Titanic",
    "Star Wars: The Force Awakens",
    "Spider-Man: No Way Home",
    "Jurassic World",
    "The Lion King",
    "Frozen",
    "Frozen II",
    "Inception",
    "The Dark Knight",
    "Forrest Gump",
    "Pulp Fiction",
    "The Godfather",
    "The Godfather Part II",
    "The Matrix",
    "The Lord of the Rings: The Return of the King",
    "Harry Potter and the Sorcerer's Stone",
    "Jurassic Park",
    "The Avengers",
    "Black Panther",
    "Iron Man 3",
    "Mission: Impossible - Fallout",
    "Despicable Me 4",
    "Deadpool & Wolverine",
    "Inside Out 2",
    "Oppenheimer",
    "Barbie",
    "Moana 2",
    "Top Gun: Maverick",
    "E.T. the Extra-Terrestrial",
    "The Wizard of Oz",
    "King Kong: Skull Island",
    "The Lion King (1994)",
    "Shrek 2",
    "Minions",
    "Star Wars: Episode IV - A New Hope",
    "The Super Mario Bros. Movie",
    "Frozen",
    "Black Panther: Wakanda Forever",
    "Toy Story",
    "Toy Story 3",
    "Up",
    "Coco",
    "Interstellar",
    "La La Land",
    "The Social Network",
    "Goodfellas",
    "Se7en",
    "The Silence of the Lambs"
]

movies_data = []

for query in movie_queries:
    # Search for movie
    response = requests.get(
        "https://api.themoviedb.org/3/search/movie",
        headers=HEADERS,
        params={"query": query}
    )
    results = response.json().get("results", [])
    if not results:
        continue
    
    movie = results[0]  # Take first match
    movie_id = movie['id']

    # Get full movie details including credits
    detail_response = requests.get(
        f"https://api.themoviedb.org/3/movie/{movie_id}",
        headers=HEADERS,
        params={"append_to_response": "credits"}
    )
    detail = detail_response.json()

    # Extract director
    director = next(
        (member['name'] for member in detail['credits']['crew'] if member['job'] == "Director"),
        "Unknown"
    )
    
    # Extract main actors (top 3)
    actors = [member['name'] for member in detail['credits']['cast'][:3]]

    # Thumbnail
    thumbnail = f"https://image.tmdb.org/t/p/w500{detail['poster_path']}" if detail['poster_path'] else ""

    # Append to our list
    movies_data.append({
        "title": detail.get("title"),
        "year": detail.get("release_date", "")[:4],
        "director": director,
        "actors": actors,
        "rating": detail.get("vote_average"),
        "runtime": f"{detail.get('runtime', 0)} min",
        "genre": [g['name'] for g in detail.get('genres', [])],
        "plot": detail.get("overview"),
        "thumbnail": thumbnail
    })

# Save to local JSON file
with open("movies_library.json", "w", encoding="utf-8") as f:
    json.dump({"movies": movies_data}, f, ensure_ascii=False, indent=2)

print(f"Saved {len(movies_data)} movies to movies_library.json")
