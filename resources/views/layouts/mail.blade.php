<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- <link rel="stylesheet" href="{{asset('css/mail.css')}}" type="text/css"> -->
        <style>
            * {
    font-family: "Nunito", sans-serif;
    margin: 0px;
    padding: 0px;
}
body {
    background-color: #edf0f3;
}
.mail-header {
    background-color: transparent;
    height: 70px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
}
hr {
    margin: 20px 0px 20px 0px;
}
.mail-header > img {
    object-fit: cover;
}
.mail-body {
    display: flex;
    justify-content: center;
    align-items: center;
}
.email-container {
    background: white;
    width: 50vw;
    min-height: 80vh;
    border-radius: 10px;
    padding: 40px;
}
.email-title {
    text-align: center;
    font-size: 30px;
    font-weight: 400;
    background-color: none;
    padding: 10px;
}
.mail-footer {
    width: 80vw;
    margin: auto;
    display: flex;
    padding: 40px;
}
.footer-item {
    flex: 1;
}

li {
    list-style: none;
    padding: 5px;
}
.link {
    color: rgb(25, 25, 78);
}
.subtitle {
    color: rgb(88, 80, 80);
}
.footer-title {
    color: grey;
}
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}
td,
th {
    text-align: left;
    padding: 10px;
}

th {
    background-color: #dddddd;
}
.thank-title {
    text-align: center;
    font-weight: bold;
    padding: 10px;
}

        </style>
    </head>
    <body style="background-color: none;">
        <div class="mail-header">
            <img height="70px" src="https://system.onininternational.com/images/logo.jpeg" alt="logo" >
        </div>
        <div class="mail-body">
            <div class="email-container">
                <div class="email-title">
                    @yield('title')
                </div>
                <div class="mail-main-body">
                    @yield('content')
                </div>
            </div>
        </div>
        <div class="mail-footer">
            
            <div class="footer-item">
                <h3 class="footer-title">Customer Service</h3>
                <ul>
                    <li class="link">Help & FAQ</li>
                    <li class="link">Terms & Condition</li>
                    <li class="link">Privary Policy</li>
                    <li class="link">
                        Contact US
                    </li>
                </ul>
            </div>
            <div class="footer-item">
                <h3 class="footer-title">Contact us</h3>
                <li class="link">New Road, Kathmandu, Nepal</li>
                <li class="link">+977-9876543213</li>
                <li class="link">onin.international@gmail.com</li>
            </div>
            <div class="footer-item">
                <h3 class="footer-title">About Us</h3>
                <div class="subtitle">It is a long established fact that a 
                    reader will be distracted by the readable 
                    content of a page when looking at its layout. 
                    The point of using Lorem Ipsum is that it has a 
                    more-or-less normal distribution of letters</div>
            </div>
        </div>
    </body>
</html>
