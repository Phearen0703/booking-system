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
                    <!-- Login Button -->
                    <li class="nav-item" id="loginButton">
                        <a class="nav-link" href="/booking-system/admin/auth/login.php">Login</a>
                    </li>

                    <!-- Profile Dropdown (Initially empty, will be populated by JavaScript) -->
                    <li class="nav-item dropdown" id="profileDropdown">
                        <!-- Profile dropdown will be populated by JavaScript if user is logged in -->
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
        button.addEventListener('click', async function () {
            try {
                // Check if the user is logged in
                let response = await fetch("server/check_login.php");
                let data = await response.json();

                if (!data.loggedIn) {
                    alert("You must be logged in to book.");
                    window.location.href = "/booking-system/admin/auth/login.php";
                    return;
                }

                // Fetch hotel data attributes from the clicked button
                const hotelName = this.getAttribute("data-name");
                const hotelId = this.getAttribute("data-id");
                const contact = this.getAttribute("data-contact");

                // Set values in the modal (Check if elements exist before setting values)
                const hotelNameInput = document.getElementById("hotelName");
                const hotelIdInput = document.getElementById("hotelId");
                const locationInput = document.getElementById("location");
                const contactInput = document.getElementById("contact");

                if (hotelNameInput) hotelNameInput.value = hotelName;
                if (hotelIdInput) hotelIdInput.value = hotelId;
                if (contactInput) contactInput.value = contact;

                // Fetch and populate room types
                await fetchRoomTypes(hotelId);

                // Show the modal using Bootstrap
                const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
                bookingModal.show();

            } catch (error) {
                console.error("Error:", error);
            }
        });
    });
}


// Function to fetch and populate room types
async function fetchRoomTypes(hotelId) {
    try {
        let response = await fetch(`server/get_room_types.php?hotel_id=${hotelId}`);
        let roomTypes = await response.json();

        const roomTypeSelect = document.getElementById("roomType");
        if (!roomTypeSelect) {
            console.error("Error: Room Type select element not found!");
            return;
        }

        roomTypeSelect.innerHTML = ''; // Clear previous options

        // Default "Select Room Type" option
        const defaultOption = document.createElement("option");
        defaultOption.value = '';
        defaultOption.textContent = "Select Room Type";
        roomTypeSelect.appendChild(defaultOption);

        // Populate dropdown with room types
        roomTypes.forEach(room => {
            const option = document.createElement("option");
            option.value = room.id; // Use room ID for backend processing
            option.textContent = `${room.room_type} - $${room.price}`;
            roomTypeSelect.appendChild(option);
        });

    } catch (error) {
        console.error("Error fetching room types:", error);
    }
}





document.addEventListener("DOMContentLoaded", function () {
    console.log("JavaScript loaded!");

    const bookingForm = document.getElementById("bookingForm");

    if (bookingForm) {
        bookingForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission
            handleBooking(this); // Call the booking function
        });
    } else {
        console.error("Error: Booking form not found!");
    }
});

// Function to handle the booking submission
async function handleBooking(form) {
    const formData = new FormData(form);

    try {
        let response = await fetch("server/insert_booking.php", {
            method: "POST",
            body: formData,
        });

        let text = await response.text(); // Get raw response
        console.log("Raw response:", text); // Debugging

        let data = JSON.parse(text);

        if (data.success) {
            // Close the modal
            const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
            bookingModal.hide();

            // Show success message
            alert("Booking Successful!");
            window.location.reload(); // Optionally reload the page to show the updated data
        } else {
            alert("Booking Failed: " + data.message); // Show error message if any
        }

    } catch (error) {
        console.error("Error booking:", error);
    }
}

// check login 
document.addEventListener("DOMContentLoaded", function () {
    checkLoginStatus();
});

function checkLoginStatus() {
    fetch('server/show_info.php')
        .then(response => response.json())
        .then(data => {
            console.log("Login status data:", data);
            updateNavbar(data);
        })
        .catch(error => console.error('Error checking login status:', error));
}

function updateNavbar(data) {
    const loginButton = document.getElementById("loginButton");
    const profileDropdown = document.getElementById("profileDropdown");

    if (data.loggedIn) {
        loginButton.style.display = 'none';
        profileDropdown.innerHTML = `
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="${data.user_image}" alt="Profile" class="rounded-circle border border-light shadow-sm" width="40" height="40">
                    <span class="fw-semibold">${data.username}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" href="profile.php">
                        <i class="bi bi-person-circle"></i> My Profile</a></li>
                    <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" href="settings.php">
                        <i class="bi bi-gear"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger" href="/booking-system/admin/auth/action_logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        `;
    } else {
        loginButton.style.display = 'block';
        profileDropdown.innerHTML = '';
    }
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