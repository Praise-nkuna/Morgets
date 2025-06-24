<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Morgets Bookshop - Book a Service</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-image: url('book.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .overlay-box {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 10px;
            max-width: 600px;
            margin: 80px auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        footer {
            background-color: #00264d;
            color: white;
            text-align: center;
            padding: 15px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        #responseMessage {
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #004080;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <img src="logo.jpg" alt="Logo" width="40" height="40" class="me-2 rounded-circle" />
                Morgets Bookshop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="services.html">Services</a></li>
                    <li class="nav-item"><a class="nav-link active" href="booking.php">Book Service</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="announcement.html">Announcements</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Booking Form -->
    <div class="overlay-box">
        <h1 class="mb-4 text-center">Book a Service</h1>
        <form id="bookingForm" method="post" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control" required pattern="^[A-Za-z\s]+$" placeholder="Your full name" />
                <div class="invalid-feedback">Name must contain only letters and spaces.</div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="you@example.com" />
                <div class="invalid-feedback">Enter a valid email address.</div>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Cell Number <span class="text-danger">*</span></label>
                <input type="tel" id="phone" name="phone" class="form-control" required pattern="0[6-8][0-9]{8}" placeholder="e.g. 0712345678" />
                <div class="invalid-feedback">Enter a valid 10-digit South African number starting with 06–08.</div>
            </div>

            <div class="mb-3">
                <label for="service" class="form-label">Service Required <span class="text-danger">*</span></label>
                <select id="service" name="service" class="form-select" required>
                    <option value="" disabled selected>Select a service</option>
                    <option value="Buy a Book">Buy a Book</option>
                    <option value="Order a Book">Order a Book</option>
                    <option value="Academic Writing">Academic Writing</option>
                    <option value="Creative Writing">Creative Writing</option>
                    <option value="Web Development">Web Development</option>
                    <option value="IT Diploma Assistance">IT Diploma Assistance</option>
                    <option value="Other">Other</option>
                </select>
                <div class="invalid-feedback">Please select a service.</div>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Preferred Date <span class="text-danger">*</span></label>
                <input type="date" id="date" name="date" class="form-control" required />
                <div class="invalid-feedback">Please select a valid date.</div>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Additional Information</label>
                <textarea id="message" name="message" rows="4" class="form-control" placeholder="Optional details..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Submit Booking</button>
        </form>

        <div id="responseMessage"></div>
    </div>

      <!-- Footer -->
    <footer class="mt-auto">
        <p class="mb-0">
            &copy; 2025 Morgets Bookshop. All rights reserved.<br />
            Created by
            <a href="https://github.com/Praise-nkuna" target="_blank">Praise Nkuna</a>
        </p>
        <div style="margin-top: 10px;">
            <a href="https://www.linkedin.com/in/praise-nkuna-341378127/" target="_blank" style="margin-right: 15px;">
                <i class="fab fa-linkedin fa-lg"></i>
            </a>
            <a href="https://www.facebook.com/profile.php?id=100089129395370" target="_blank">
                <i class="fab fa-facebook fa-lg"></i>
            </a>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Set min date to today
        document.addEventListener("DOMContentLoaded", () => {
            const dateInput = document.getElementById("date");
            const today = new Date().toISOString().split("T")[0];
            dateInput.min = today;
        });

        // Bootstrap form validation + AJAX
        $(document).ready(function () {
            const form = $('#bookingForm');

            form.on('submit', function (e) {
                e.preventDefault();

                if (!this.checkValidity()) {
                    this.classList.add('was-validated');
                    return;
                }

                const formData = form.serialize();

                $('#responseMessage').html('<div class="text-info">Sending...</div>');

                $.post('send_booking.php', formData, function (data) {
                    if (data.success) {
                        $('#responseMessage').html('<div class="alert alert-success">' + data.message + '</div>');
                        form[0].reset();
                        form.removeClass('was-validated');
                    } else {
                        $('#responseMessage').html('<div class="alert alert-danger">' + data.message + '</div>');
                    }
                }).fail(function () {
                    $('#responseMessage').html('<div class="alert alert-danger">Server error. Please try again later.</div>');
                });
            });
        });
    </script>
</body>
</html>
