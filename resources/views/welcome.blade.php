<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 650px;
            padding: 40px;
        }

        h1 {
            font-size: 4rem;
            margin-bottom: 20px;
            letter-spacing: 2px;
        }

        p {
            font-size: 1.2rem;
            color: #d1d5db;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        .divider {
            width: 80px;
            height: 4px;
            background: #38bdf8;
            margin: 25px auto;
            border-radius: 50px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            background: #38bdf8;
            color: #0f172a;
            padding: 14px 35px;
            border-radius: 50px;
            font-weight: bold;
            transition: .3s;
        }

        .btn:hover {
            background: #fff;
            transform: translateY(-3px);
        }

        footer {
            margin-top: 50px;
            color: #cbd5e1;
            font-size: 14px;
        }

        @media(max-width:768px){
            h1{
                font-size:2.8rem;
            }

            p{
                font-size:1rem;
            }

            .container{
                padding:25px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🚀 Coming Soon</h1>

    <div class="divider"></div>

    <p>
        We're working hard to launch something amazing.
        Our website is currently under development and will be available soon.
        Thank you for your patience.
    </p>

    <a href="mailto:info@example.com" class="btn">Contact Us</a>

    <footer>
        &copy; 2026 Your Company. All Rights Reserved.
    </footer>
</div>

</body>
</html>