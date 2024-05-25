<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav>
        <div class="background"></div>
        <button class="menu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <p class="logo">Auto<span>Nation</span></p>
        <div class="navItems">
            <ul>
                <li><a href="#">Services</a></li>
                <li><a href="#">About us</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
        <div class="buttons">
            <button class="loginBtn"><a href="../php/jQFormsRegister.php">login</a></button>
            <button class="signupBtn"><a href="../php/jQFormsLogin.php">sign up</a></button>
        </div>
    </nav>

    <section class="home">
        <div class="homeTxt">
            <h1>Find Your Freedom</h1>
            <p>Drive the Car of Your Dreams</p>
        </div>
        <img src="../images/homeImage.jpg" alt="">
    </section>

<section class="services">
    <h1>Our Services</h1>
    <div class="servicesContainer">
        <div class="service">
            <img src="https://www.carrentals.com/blog/wp-content/uploads/2020/09/cars-for-rent.jpg" alt="Car Rental">
            <h4>Car Rental</h4>
            <p>We provide the best car rental services at very reasonable and cheap prices.</p>
        </div>
        <div class="service">
            <img src="https://carwow-uk-wp-3.imgix.net/wp-content/uploads/2020/11/30090709/BMW-3-Series-buyers-guide-2021-hero-front.jpg" alt="Car Sales">
            <h4>Car Sales</h4>
            <p>Explore our wide range of cars for sale, from economy to luxury models.</p>
        </div>
        <div class="service">
            <img src="https://st.depositphotos.com/1705804/5022/i/450/depositphotos_50229401-stock-photo-car-financing.jpg" alt="Car Financing">
            <h4>Car Financing</h4>
            <p>We offer flexible financing options to help you get the car you want.</p>
        </div>
        <div class="service">
            <img src="https://images.unsplash.com/photo-1597003272954-7c9cce56e66e" alt="Car Servicing">
            <h4>Car Servicing</h4>
            <p>Keep your car in top condition with our professional servicing and maintenance.</p>
        </div>
    </div>
</section>



    <h1 style="margin-top: 80px; margin-left: 40px;">About Us</h1>

    <section class="aboutus">
        <div class="statsContainer">
            <div class="stats">
                <h1>2k + Customers</h1>
            </div>
            <div class="stats">
                <h1>1k + cars Sold</h1>
            </div>
            <div class="stats">
                <h1>5k + cars rented</h1>
            </div>
        </div>

        <div class="mapContainer"></div>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3520974.823218074!2d80.05820036510013!3d28.383844953438!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3995949e8255dfe3%3A0xb7f41e7c32794434!2sNepal!5e0!3m2!1sen!2snp!4v1711115579072!5m2!1sen!2snp"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

    <section class="contact">
        <h1>Contact</h1>
        <div class="contactContainer">
            <form action="" class="contactForm">
                <h2>We'd love to hear from you!...</h2>
                <h3>Dive into our world of creativity and fun—don't hesitate to reach out and connect with us!</h3>

                <input type="text" placeholder="Full name">
                <input type="text" placeholder="Email">
                <textarea name="" id="" cols="30" rows="10" placeholder="Enter your valuable message here.."></textarea>
                <button class="contactBtn">Submit</button>
            </form>
            <img src="../images/contact.jpg" alt="">
        </div>
    </section>

    <footer>
        <div class="footerLeft">
            <p class="logo">Auto<span>Nation</span></p>
            <p>Your Car, Your Journey</p>
        </div>
        <div class="footerRight">
            <div class="footerUpper">
                <ul>
                    <li>About Us</li>
                    <li>How it works</li>
                    <li>Services</li>
                    <li>Contact</li>
                    <li>Why us?</li>
                    <li>signup</li>
                </ul>
            </div>

            <div class="footerLower"></div>
        </div>
        <div class="socials">
            <p>Follow us on our social medias</p>
            <div class="icons">
                <i class="fa-brands fa-facebook"></i>
                <i class="fa-brands fa-instagram"></i>
                <i class="fa-brands fa-discord"></i>
                <i class="fa-brands fa-twitter"></i>
            </div>
    </footer>
</body>

</html>
