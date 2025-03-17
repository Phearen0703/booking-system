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

        .carousel-item img {
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">BOOKING HOTEL</a>
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

    <!-- Carousel -->
    <div id="hotelCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
        <div class="carousel-inner" id="carousel-hotels">
            <!-- Carousel items will be loaded here -->
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#hotelCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#hotelCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

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
        <p>&copy; 2025 Booking System. All Rights Reserved.</p>
    </footer>

    <script>
        fetch("server/hotels.php")
            .then(response => response.json())
            .then(data => {
                console.log(data); // Check the response in console
                loadHotels(data);
            })
            .catch(error => console.error("Error fetching hotels:", error));

            function loadHotels(hotels) {
    const hotelList = document.getElementById("hotel-list");
    const carouselHotels = document.getElementById("carousel-hotels");
    hotelList.innerHTML = "";
    carouselHotels.innerHTML = "";

    hotels.forEach((hotel, index) => {
        let stars = '★'.repeat(hotel.rating) + '☆'.repeat(5 - hotel.rating);

        hotelList.innerHTML += `
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg">
                    <img src="${hotel.image}" class="card-img-top" alt="Hotel Image">
                    <div class="card-body text-center">
                        <h5 class="card-title">${hotel.name}</h5>
                        <p class="card-text">📍 ${hotel.location}</p>
                        <p class="card-text">💲 ${hotel.price}</p>
                        <p class="star-rating">${stars}</p>
                        <button class="btn btn-primary book-btn" data-id="${hotel.id}" data-name="${hotel.name}">Book Now</button>
                    </div>
                </div>
            </div>`;

        carouselHotels.innerHTML += `
            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                <img src="${hotel.image}" class="d-block w-100" alt="Hotel Image">
                <div class="carousel-caption">
                    <h6>${hotel.name}</h6>
                    <p>${stars}</p>
                </div>
            </div>`;
    });

    // Attach event listeners to the "Book Now" buttons
    document.querySelectorAll('.book-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById("hotelId").value = this.getAttribute("data-id");
            document.getElementById("hotelName").value = this.getAttribute("data-name");
            var bookingModal = new bootstrap.Modal(document.getElementById("bookingModal"));
            bookingModal.show();
        });
    });
}



        //filter feature
        document.addEventListener("DOMContentLoaded", function () {
    loadProvinces();

    document.getElementById("filter-province").addEventListener("change", function () {
        loadCities(this.value);
    });

    document.getElementById("filter-city").addEventListener("change", function () {
        loadDistricts(this.value);
    });
});

// Fetch all provinces
function loadProvinces() {
    fetch("server/get_locations.php?type=provinces")
        .then(response => response.json())
        .then(provinces => {
            const provinceDropdown = document.getElementById("filter-province");
            provinceDropdown.innerHTML = `<option value="">Filter by Province</option>`;
            provinces.forEach(province => {
                provinceDropdown.innerHTML += `<option value="${province.id}">${province.name}</option>`;
            });
        })
        .catch(error => console.error("Error fetching provinces:", error));
}

// Fetch cities based on selected province
function loadCities(province_id) {
    const cityDropdown = document.getElementById("filter-city");
    const districtDropdown = document.getElementById("filter-district");

    if (!province_id) {
        cityDropdown.innerHTML = `<option value="">Filter by City</option>`;
        cityDropdown.disabled = true;
        districtDropdown.innerHTML = `<option value="">Filter by District</option>`;
        districtDropdown.disabled = true;
        return;
    }

    fetch(`server/get_locations.php?type=cities&province_id=${province_id}`)
        .then(response => response.json())
        .then(cities => {
            cityDropdown.innerHTML = `<option value="">Filter by City</option>`;
            cities.forEach(city => {
                cityDropdown.innerHTML += `<option value="${city.id}">${city.name}</option>`;
            });
            cityDropdown.disabled = false;
        })
        .catch(error => console.error("Error fetching cities:", error));
}

// Fetch districts based on selected city
function loadDistricts(city_id) {
    const districtDropdown = document.getElementById("filter-district");

    if (!city_id) {
        districtDropdown.innerHTML = `<option value="">Filter by District</option>`;
        districtDropdown.disabled = true;
        return;
    }

    fetch(`server/get_locations.php?type=districts&city_id=${city_id}`)
        .then(response => response.json())
        .then(districts => {
            districtDropdown.innerHTML = `<option value="">Filter by District</option>`;
            districts.forEach(district => {
                districtDropdown.innerHTML += `<option value="${district.id}">${district.name}</option>`;
            });
            districtDropdown.disabled = false;
        })
        .catch(error => console.error("Error fetching districts:", error));
}


    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- modal for booking info -->

    <!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Book Hotel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
                    <input type="hidden" id="hotelId" name="hotelId">
                    <div class="mb-3">
                        <label for="hotelName" class="form-label">Hotel Name</label>
                        <input type="text" class="form-control" id="hotelName" name="hotelName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="fullName" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="checkInDate" class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" id="checkInDate" name="checkInDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="checkOutDate" class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" id="checkOutDate" name="checkOutDate" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Confirm Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>

</html>