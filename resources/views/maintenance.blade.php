<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We'll Be Back Soon!</title>

    <!-- Google Fonts -->
    {{--    font cairo--}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom CSS for Maintenance Page -->
    <style>
        /* Existing CSS styles */
        /* ... */

        /* New CSS Adjustments */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Cairo,sans-serif;
            background: linear-gradient(57deg, #007bffab, #0c00ff26);
        }

        .maintenance-container {
            text-align: center;
            padding: 50px;
            background-color: #FFFFFF;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 500px;
            width: 80%;
        }

        .maintenance-container h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1E2B4F;
            margin-bottom: 15px;
            margin-top: 10px;
        }

        .maintenance-container p {
            font-size: 18px;
            font-weight: 300;
            color: #5C6085;
            margin-bottom: 30px;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: #EDF2F7;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 70%;
            background: #007BFF;
            border-radius: 6px;
            animation: loading 3s ease-in-out infinite;
        }

        @keyframes loading {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        .social-links{
            padding-top:8px;
        }
        .social-links a {
            margin: 0 12px;
            text-decoration: none;
            color: #007BFF;
            font-size: 20px;
        }

        /* Adjustments for smaller screens */
        @media (max-width: 576px) {
            .maintenance-container {
                padding: 30px;
            }

            .maintenance-container h1 {
                font-size: 28px;
            }

            .maintenance-container p {
                font-size: 18px;
            }

            .progress-bar {
                height: 10px;
                border-radius: 5px;
            }

            .social-links a {
                font-size: 18px;
            }
        }

        img.coming-soon{
            margin-top:-20px;
        }
    </style>
</head>
<body>

<div class="maintenance-container">
    <img src="{{ asset('front/assets/img/logo.png') }}" style="width:300px; max-height:68px;" />
    <h1>مرحبا بكم في ماما ستور</h1>
    <p>الموقع تحت التطوير الآن نأسف على إزعاجكم   سنعود قريبا جدا </p>
    <img class="coming-soon" src="{{ asset('front/assets/img/coming.png') }}" style=" max-height:100px;" />'

    <!-- Progress Bar -->
    <div class="progress-bar"></div>

    <!-- Social Media Links -->
    <div class="social-links" dir="rtl">
        <a href="{{\App\Models\Admin\Setting::getValue('facebook')}}"><i class="fab fa-facebook"></i></a>
        <a href="{{\App\Models\Admin\Setting::getValue('whats-app')}}"><i class="fab fa-whatsapp"></i></a>
        <a href="{{\App\Models\Admin\Setting::getValue('insta')}}"><i class="fab fa-instagram"></i></a>
    </div>
</div>

</body>
</html>
