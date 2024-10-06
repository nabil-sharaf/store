<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We'll Be Back Soon!</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

    <!-- Custom CSS for Maintenance Page -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .maintenance-container {
            text-align: center;
            padding: 50px;
            background-color: white;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .maintenance-container h1 {
            font-size: 2.5em;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .maintenance-container p {
            font-size: 1.2em;
            font-weight: 300;
            margin-bottom: 30px;

        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background: #eee;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 70%; /* You can animate this width based on actual maintenance progress */
            background: #007bff;
            border-radius: 5px;
            animation: loading 3s ease-in-out infinite;
        }

        @keyframes loading {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        .social-links {
            margin-top: 20px;
        }

        .social-links a {
            margin: 0 10px;
            text-decoration: none;
            color: #007bff;
            font-size: 1.5em;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Full height of the viewport */
            margin: 0; /* Remove any default margin */
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            font-family: 'Poppins', sans-serif;
            color: #333;
            padding: 0 20px; /* Adding padding for smaller screens */
        }

        .maintenance-container {
            text-align: center;
            padding: 50px;
            background-color: white;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 500px; /* Limit width for larger screens */
            width: 100%; /* Ensure it takes full width on mobile */
        }

        /* Adjustments for smaller screens */
        @media (max-width: 576px) {
            .maintenance-container {
                padding: 30px;
            }
        }

    </style>
</head>
<body>

<div class="maintenance-container">
    <img src="{{ asset('front/assets/img/logo.png') }}" style="width:160px; max-height:68px;" />'    <h1>مرحبا بكم في ماما ستور</h1>
    <p>الموقع تحت التطوير الآن نأسف على إزعاجكم   سنعود قريبا جدا </p>

    <!-- Progress Bar -->
    <div class="progress-bar"></div>

    <!-- Social Media Links -->
    <div class="social-links">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
</div>

</body>
</html>
