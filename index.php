<?php
require_once("protect.php");
protect_guest_folder();

var_dump($SESSION['role_id']) ?>


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

        /* Modal Styling */
        .modal-content {
            background-color: #f9f9f9;
            border-radius: 12px; /* Rounded corners for modern feel */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
            overflow: hidden; /* Clean edges */
        }

        .modal-header {
            background-color: #fff;
            border-bottom: 0;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .modal-body {
            background-color: #fff;
            padding: 2rem;
        }

        .form-label {
            font-size: 0.95rem;
            font-weight: 500;
            color: #777;
        }

        .form-control, .form-select {
            border-radius: 8px; /* Rounded input fields */
            border: 1px solid #ddd;
            font-size: 1rem;
            padding: 0.75rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25); /* Blue focus ring */
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Button shadow effect */
        .shadow-sm {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design for Modal */
        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 90%;
            }
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
            // Fetch hotels and display them
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

    // Attach event listeners AFTER hotels are loaded
    attachBookEventListeners();
}

// Function to attach "Book Now" button event listeners
function attachBookEventListeners() {
    document.querySelectorAll('.book-btn').forEach(button => {
        button.addEventListener('click', function () {
            fetch("server/check_login.php")
                .then(response => response.json())
                .then(data => {
                    if (!data.loggedIn) {
                        alert("You must be logged in to book.");
                        window.location.href = "/booking-system/admin/auth/login.php";
                    } else {
                        // Fetch hotel data attributes from the clicked button
                        const hotelName = this.getAttribute("data-name");
                        const hotelId = this.getAttribute("data-id");
                        const location = this.getAttribute("data-location");
                        const contact = this.getAttribute("data-contact");

                        // Set values in the modal
                        document.getElementById("hotelName").value = hotelName;
                        document.getElementById("hotelId").value = hotelId;
                        document.getElementById("location").value = location;
                        document.getElementById("contact").value = contact;

                        // Fetch room types for the hotel and populate the dropdown
                        fetch(`server/get_room_types.php?hotel_id=${hotelId}`)
                            .then(response => response.json())
                            .then(roomTypes => {
                                const roomTypeSelect = document.getElementById("roomType");
                                roomTypeSelect.innerHTML = ''; // Clear any existing options
                                
                                // Add the default "Select Room Type" option
                                const defaultOption = document.createElement("option");
                                defaultOption.value = '';
                                defaultOption.textContent = "Select Room Type";
                                roomTypeSelect.appendChild(defaultOption);
                                
                                // Populate the dropdown with room types
                                roomTypes.forEach(room => {
                                    const option = document.createElement("option");
                                    option.value = room.room_type;
                                    option.textContent = room.room_type;
                                    roomTypeSelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error("Error fetching room types:", error));

                        // Show the modal using Bootstrap
                        const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
                        bookingModal.show();
                    }
                })
                .catch(error => console.error("Error checking login:", error));
        });
    });
}




    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-dark" id="bookingModalLabel">Book Your Stay</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form id="bookingForm">
                    <input type="hidden" id="hotelId" name="hotelId">

                    <!-- Hotel Name -->
                    <div class="mb-3">
                        <label for="hotelName" class="form-label text-muted">Hotel Name</label>
                        <input type="text" class="form-control shadow-sm" id="hotelName" name="hotelName" readonly>
                    </div>


                    <!-- Room Type -->
                    <div class="mb-3">
                        <label for="roomType" class="form-label text-muted">Room Type</label>
                        <select class="form-select shadow-sm" id="roomType" name="roomType" required>
                            <!-- Options will be populated dynamically here -->
                        </select>
                    </div>


                    <!-- Location -->
                    <div class="mb-3">
                        <label for="location" class="form-label text-muted">Location</label>
                        <input type="text" class="form-control shadow-sm" id="location" name="location" readonly>
                    </div>

                    <!-- Contact -->
                    <div class="mb-3">
                        <label for="contact" class="form-label text-muted">Contact</label>
                        <input type="text" class="form-control shadow-sm" id="contact" name="contact" readonly>
                    </div>

                    <!-- Check-in Date -->
                    <div class="mb-3">
                        <label for="checkInDate" class="form-label text-muted">Check-in Date</label>
                        <input type="date" class="form-control shadow-sm" id="checkInDate" name="checkInDate" required>
                    </div>

                    <!-- Check-out Date -->
                    <div class="mb-3">
                        <label for="checkOutDate" class="form-label text-muted">Check-out Date</label>
                        <input type="date" class="form-control shadow-sm" id="checkOutDate" name="checkOutDate" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm">Confirm Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>


</body>

</html>