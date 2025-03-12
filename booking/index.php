<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: #1a1a2e;
            color: white;
        }
        .navbar {
            background: rgba(0, 0, 0, 0.8) !important;
        }
        .card {
            background: #222;
            color: white;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(255, 215, 0, 0.5);
        }
        .card img {
            height: 220px;
            object-fit: cover;
        }
        .star-rating {
            color: gold;
        }
        .btn-primary {
            background: #ffcc00;
            border: none;
            color: black;
            font-weight: bold;
        }
        .btn-primary:hover {
            background: #ffdb4d;
        }
        footer {
            background: black;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">🏨 Luxury Stays</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <form class="d-flex ms-auto">
                    <input class="form-control me-2" type="search" placeholder="Search hotels...">
                    <select class="form-select me-2">
                        <option value="">Filter by Location</option>
                        <option>Phnom Penh</option>
                        <option>Siem Reap</option>
                        <option>Sihanoukville</option>
                    </select>
                    <button class="btn btn-primary" type="button">Search</button>
                </form>
                <ul class="navbar-nav ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Find Your Dream Stay</h2>
        <div class="row" id="hotel-list">
            <!-- Hotel cards will be loaded here -->
        </div>
        <nav>
            <ul class="pagination justify-content-center mt-4" id="pagination"></ul>
        </nav>
    </div>

    <footer class="text-light text-center py-3 mt-5">
        <p>&copy; 2024 Luxury Stays. All Rights Reserved.</p>
    </footer>

    <script>
        const hotels = [
            { id: 1, name: "The Royal Palace Hotel", location: "Phnom Penh", price: "$120/night", rating: 5, roomType: "Deluxe Suite", image: "https://source.unsplash.com/400x300/?luxury-hotel" },
            { id: 2, name: "Angkor Paradise Resort", location: "Siem Reap", price: "$200/night", rating: 5, roomType: "Presidential Suite", image: "https://source.unsplash.com/400x300/?resort" },
            { id: 3, name: "Ocean Breeze Retreat", location: "Sihanoukville", price: "$150/night", rating: 4, roomType: "Beachfront Villa", image: "https://source.unsplash.com/400x300/?beach-resort" }
        ];

        let currentPage = 1;
        const itemsPerPage = 2;

        function loadHotels(page = 1) {
            const hotelList = document.getElementById("hotel-list");
            hotelList.innerHTML = "";
            let start = (page - 1) * itemsPerPage;
            let end = start + itemsPerPage;
            let paginatedItems = hotels.slice(start, end);
            
            paginatedItems.forEach(hotel => {
                let stars = '★'.repeat(hotel.rating) + '☆'.repeat(5 - hotel.rating);
                hotelList.innerHTML += `
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-lg">
                            <img src="${hotel.image}" class="card-img-top" alt="Hotel Image">
                            <div class="card-body text-center">
                                <h5 class="card-title">${hotel.name}</h5>
                                <p class="card-text">📍 ${hotel.location}</p>
                                <p class="card-text">🏨 Room Type: ${hotel.roomType}</p>
                                <p class="card-text">💲 ${hotel.price}</p>
                                <p class="star-rating">${stars}</p>
                                <button class="btn btn-primary">Book Now</button>
                            </div>
                        </div>
                    </div>`;
            });
            loadPagination();
        }

        function loadPagination() {
            const pagination = document.getElementById("pagination");
            pagination.innerHTML = "";
            let totalPages = Math.ceil(hotels.length / itemsPerPage);
            for (let i = 1; i <= totalPages; i++) {
                pagination.innerHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" onclick="changePage(${i})">${i}</a></li>`;
            }
        }

        function changePage(page) {
            currentPage = page;
            loadHotels(page);
        }

        document.addEventListener("DOMContentLoaded", () => loadHotels());
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>