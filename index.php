<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task Management Platform</title>
  <link rel="stylesheet" href="./static/styles/home.css" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>

<body>
  <div class="container">

    <header>
      <img src="./static/images/hero.png" alt="Hero">
      <div class="overlay">
        <nav>
          <a href="<?php echo $path . "/"; ?>" id="logo">
            <svg viewBox="0 0 58 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M33.656 6.814C33.788 6.97 33.854 7.132 33.854 7.3C33.854 7.372 33.848 7.45 33.836 7.534C33.836 7.606 33.83 7.672 33.818 7.732C33.782 7.864 33.704 7.99 33.584 8.11C33.488 8.182 33.356 8.236 33.188 8.272C33.104 8.296 33.02 8.314 32.936 8.326C32.852 8.338 32.762 8.356 32.666 8.38C32.582 8.392 32.498 8.398 32.414 8.398C32.33 8.398 32.24 8.398 32.144 8.398H31.226C31.01 8.41 30.794 8.416 30.578 8.416C30.374 8.416 30.17 8.416 29.966 8.416C29.978 8.788 29.984 9.19 29.984 9.622C29.984 10.042 29.978 10.534 29.966 11.098C29.966 11.662 29.96 12.316 29.948 13.06C29.948 13.804 29.948 14.668 29.948 15.652V17.02C29.948 17.14 29.954 17.23 29.966 17.29C29.966 17.338 29.966 17.386 29.966 17.434C29.966 17.47 29.96 17.518 29.948 17.578C29.924 17.686 29.906 17.812 29.894 17.956C29.894 18.016 29.888 18.076 29.876 18.136C29.864 18.184 29.846 18.238 29.822 18.298C29.774 18.346 29.75 18.382 29.75 18.406L29.696 18.514C29.636 18.634 29.522 18.748 29.354 18.856C29.09 18.988 28.796 19.018 28.472 18.946C28.268 18.922 28.112 18.868 28.004 18.784C27.896 18.676 27.818 18.568 27.77 18.46C27.698 18.328 27.656 18.202 27.644 18.082C27.608 17.974 27.59 17.86 27.59 17.74V8.488C27.518 8.488 27.368 8.488 27.14 8.488C26.912 8.488 26.666 8.488 26.402 8.488C26.15 8.476 25.91 8.464 25.682 8.452C25.454 8.44 25.298 8.428 25.214 8.416C25.142 8.404 25.028 8.386 24.872 8.362C24.728 8.338 24.608 8.314 24.512 8.29C24.26 8.218 24.062 8.104 23.918 7.948C23.786 7.792 23.72 7.618 23.72 7.426C23.72 7.354 23.72 7.282 23.72 7.21C23.72 7.138 23.732 7.072 23.756 7.012C23.792 6.868 23.888 6.748 24.044 6.652C24.092 6.616 24.164 6.574 24.26 6.526C24.368 6.466 24.47 6.424 24.566 6.4C24.71 6.352 24.89 6.316 25.106 6.292C25.202 6.28 25.298 6.274 25.394 6.274C25.49 6.274 25.592 6.274 25.7 6.274C26.12 6.274 26.516 6.274 26.888 6.274C27.272 6.274 27.674 6.268 28.094 6.256C28.79 6.244 29.48 6.244 30.164 6.256C30.86 6.256 31.55 6.268 32.234 6.292C32.39 6.304 32.54 6.322 32.684 6.346C32.828 6.37 32.972 6.412 33.116 6.472C33.224 6.508 33.326 6.556 33.422 6.616C33.518 6.664 33.596 6.73 33.656 6.814ZM46.2484 17.704C46.2364 17.848 46.2184 17.998 46.1944 18.154C46.1704 18.31 46.1284 18.454 46.0684 18.586C46.0084 18.73 45.9304 18.838 45.8344 18.91C45.7024 19.03 45.5464 19.096 45.3664 19.108C45.1984 19.168 45.0364 19.186 44.8804 19.162C44.7244 19.138 44.5804 19.096 44.4484 19.036C44.2804 18.928 44.1544 18.802 44.0704 18.658C43.9984 18.502 43.9444 18.34 43.9084 18.172C43.8724 18.004 43.8544 17.83 43.8544 17.65C43.8544 17.47 43.8544 17.302 43.8544 17.146V15.742L43.8364 12.988C43.8364 12.796 43.8364 12.646 43.8364 12.538C43.8364 12.43 43.8364 12.334 43.8364 12.25C43.8364 12.154 43.8304 12.052 43.8184 11.944C43.8184 11.824 43.8184 11.656 43.8184 11.44C43.8184 11.404 43.8184 11.344 43.8184 11.26C43.8304 11.164 43.8244 10.918 43.8004 10.522C43.3324 11.302 42.9424 11.932 42.6304 12.412C42.3304 12.88 42.0724 13.246 41.8564 13.51C41.6524 13.762 41.4724 13.936 41.3164 14.032C41.1604 14.116 40.9984 14.164 40.8304 14.176C40.7344 14.188 40.6384 14.17 40.5424 14.122C40.4464 14.074 40.3504 14.014 40.2544 13.942C40.1704 13.87 40.0864 13.798 40.0024 13.726C39.9304 13.642 39.8704 13.564 39.8224 13.492C39.5344 13.132 39.2104 12.7 38.8504 12.196C38.4904 11.692 38.1184 11.08 37.7344 10.36C37.7344 10.372 37.7284 10.57 37.7164 10.954C37.7164 11.338 37.7164 11.818 37.7164 12.394C37.7164 12.958 37.7104 13.57 37.6984 14.23C37.6984 14.878 37.6924 15.484 37.6804 16.048C37.6804 16.612 37.6744 17.086 37.6624 17.47C37.6504 17.842 37.6384 18.028 37.6264 18.028C37.6264 18.232 37.5784 18.424 37.4824 18.604C37.3864 18.772 37.2544 18.91 37.0864 19.018C36.9304 19.126 36.7444 19.186 36.5284 19.198C36.3124 19.21 36.0784 19.15 35.8264 19.018C35.6704 18.922 35.5504 18.784 35.4664 18.604C35.3944 18.424 35.3464 18.256 35.3224 18.1C35.3104 17.932 35.2984 17.77 35.2864 17.614C35.2864 17.458 35.2864 17.296 35.2864 17.128L35.3044 15.724L35.3224 12.952V10.54C35.3224 9.496 35.3584 8.47 35.4304 7.462C35.4304 7.33 35.4424 7.21 35.4664 7.102C35.5024 6.982 35.5444 6.868 35.5924 6.76C35.6884 6.568 35.8144 6.424 35.9704 6.328C36.1504 6.22 36.3424 6.166 36.5464 6.166C36.6304 6.166 36.7084 6.172 36.7804 6.184C36.8644 6.184 36.9424 6.19 37.0144 6.202C37.2544 6.25 37.4584 6.394 37.6264 6.634C37.8064 6.862 37.9744 7.06 38.1304 7.228C38.2264 7.336 38.3284 7.468 38.4364 7.624C38.5444 7.78 38.6524 7.942 38.7604 8.11C38.8684 8.278 38.9704 8.44 39.0664 8.596C39.1624 8.752 39.2464 8.89 39.3184 9.01C39.6304 9.514 39.9064 9.976 40.1464 10.396C40.3984 10.816 40.6324 11.176 40.8484 11.476C40.8844 11.44 40.9264 11.392 40.9744 11.332C41.0224 11.26 41.0704 11.188 41.1184 11.116C41.1784 11.032 41.2264 10.954 41.2624 10.882C41.3104 10.81 41.3464 10.75 41.3704 10.702C41.4904 10.51 41.6104 10.324 41.7304 10.144C41.8504 9.952 41.9764 9.754 42.1084 9.55C42.2524 9.334 42.3964 9.1 42.5404 8.848C42.6964 8.596 42.8764 8.308 43.0804 7.984C43.1644 7.84 43.2724 7.666 43.4044 7.462C43.5364 7.258 43.6864 7.06 43.8544 6.868C44.0224 6.676 44.1964 6.514 44.3764 6.382C44.5684 6.238 44.7604 6.166 44.9524 6.166C45.0484 6.166 45.1324 6.172 45.2044 6.184C45.2764 6.184 45.3484 6.19 45.4204 6.202C45.5644 6.214 45.7024 6.28 45.8344 6.4C45.9304 6.496 46.0024 6.598 46.0504 6.706C46.0984 6.862 46.1284 6.994 46.1404 7.102C46.1524 7.186 46.1584 7.264 46.1584 7.336C46.1704 7.408 46.1764 7.48 46.1764 7.552C46.1764 8.224 46.1824 8.89 46.1944 9.55C46.2064 10.21 46.2124 10.876 46.2124 11.548C46.2124 12.364 46.2124 13.204 46.2124 14.068C46.2244 14.932 46.2304 15.772 46.2304 16.588C46.2304 16.756 46.2364 16.936 46.2484 17.128C46.2604 17.32 46.2604 17.512 46.2484 17.704ZM57.0381 13.762C57.0981 13.93 57.1401 14.122 57.1641 14.338C57.2001 14.542 57.2181 14.752 57.2181 14.968C57.2181 15.184 57.2001 15.4 57.1641 15.616C57.1401 15.82 57.1041 16 57.0561 16.156C56.8641 16.828 56.4801 17.392 55.9041 17.848C55.3281 18.292 54.6681 18.616 53.9241 18.82C53.1921 19.024 52.4301 19.096 51.6381 19.036C50.8581 18.976 50.1621 18.778 49.5501 18.442C49.1661 18.226 48.8361 17.962 48.5601 17.65C48.2841 17.338 48.0501 16.99 47.8581 16.606C47.7861 16.474 47.7381 16.324 47.7141 16.156C47.7021 15.976 47.7201 15.808 47.7681 15.652C47.8161 15.484 47.8941 15.34 48.0021 15.22C48.1101 15.088 48.2601 14.998 48.4521 14.95C48.5721 14.926 48.7041 14.914 48.8481 14.914C49.0041 14.914 49.1541 14.938 49.2981 14.986C49.4421 15.022 49.5741 15.088 49.6941 15.184C49.8141 15.268 49.9101 15.388 49.9821 15.544C50.1141 15.796 50.3001 16.03 50.5401 16.246C50.7921 16.45 51.0561 16.618 51.3321 16.75C51.7281 16.882 52.1241 16.936 52.5201 16.912C52.9281 16.888 53.2941 16.798 53.6181 16.642C53.9541 16.486 54.2241 16.276 54.4281 16.012C54.6321 15.736 54.7341 15.424 54.7341 15.076C54.7341 14.788 54.6921 14.554 54.6081 14.374C54.5241 14.182 54.4041 14.026 54.2481 13.906C54.1041 13.774 53.9241 13.666 53.7081 13.582C53.5041 13.498 53.2761 13.426 53.0241 13.366C52.7361 13.306 52.4421 13.264 52.1421 13.24C51.8541 13.216 51.5601 13.186 51.2601 13.15C50.9601 13.114 50.6661 13.066 50.3781 13.006C50.0901 12.946 49.8081 12.856 49.5321 12.736C48.9801 12.508 48.5601 12.16 48.2721 11.692C47.9841 11.224 47.8101 10.72 47.7501 10.18C47.7021 9.64 47.7681 9.106 47.9481 8.578C48.1281 8.038 48.4161 7.576 48.8121 7.192C49.1961 6.844 49.6521 6.598 50.1801 6.454C50.7201 6.298 51.3141 6.208 51.9621 6.184C52.3221 6.172 52.6821 6.184 53.0421 6.22C53.4141 6.256 53.7681 6.316 54.1041 6.4C54.4521 6.484 54.7701 6.592 55.0581 6.724C55.3581 6.856 55.6161 7.012 55.8321 7.192C55.9881 7.312 56.1441 7.474 56.3001 7.678C56.4561 7.87 56.5761 8.08 56.6601 8.308C56.7441 8.524 56.7681 8.734 56.7321 8.938C56.7081 9.142 56.5821 9.316 56.3541 9.46C56.1621 9.58 55.9761 9.634 55.7961 9.622C55.6281 9.598 55.4661 9.544 55.3101 9.46C55.1541 9.364 55.0041 9.256 54.8601 9.136C54.7161 9.016 54.5841 8.908 54.4641 8.812C54.1401 8.572 53.7381 8.41 53.2581 8.326C52.7781 8.242 52.3281 8.224 51.9081 8.272C51.6441 8.308 51.3981 8.368 51.1701 8.452C50.9421 8.536 50.7441 8.65 50.5761 8.794C50.4201 8.938 50.2941 9.118 50.1981 9.334C50.1141 9.538 50.0841 9.772 50.1081 10.036C50.1201 10.228 50.1741 10.39 50.2701 10.522C50.3781 10.654 50.5041 10.762 50.6481 10.846C50.8041 10.93 50.9721 10.996 51.1521 11.044C51.3321 11.092 51.5121 11.128 51.6921 11.152C52.2321 11.224 52.7721 11.278 53.3121 11.314C53.8521 11.35 54.3801 11.47 54.8961 11.674C55.4241 11.878 55.8741 12.154 56.2461 12.502C56.6181 12.85 56.8821 13.27 57.0381 13.762Z" fill="white" />
              <path d="M6.47027 9.48638C7.62752 7.49982 10.4975 7.49982 11.6547 9.48638L14.2835 13.999C15.4486 15.999 14.0059 18.5091 11.6913 18.5091H6.43368C4.1191 18.5091 2.67638 15.999 3.84145 13.999L6.47027 9.48638Z" fill="white" />
              <path d="M18.3287 14.2693C17.2203 16.3248 14.3029 16.4025 13.1128 14.4083L10.2082 9.54135C9.03298 7.57208 10.427 5.05297 12.7259 4.99171L18.3357 4.84224C20.6346 4.78098 22.1287 7.22314 21.034 9.25289L18.3287 14.2693Z" fill="white" />
            </svg>
          </a>
          <a href="./user/login.php">
            Signin
          </a>
        </nav>
        <section>
          <!-- <h1>Archive HealthCare Iot Threat System</h1> -->
          <h1>Stay Organized, Stay Productive</h1>
          <p>Efficiently manage your tasks and boost your productivity</p>
          <a href="./user/create.php">Get Started</a>
        </section>
      </div>

    </header>



    <section class="features" id="features">
      <h2>Features</h2>

      <ul>
        <li>
          <strong>Task Assignment and Delegation</strong>
          Easily create, assign, and delegate tasks based on authorization levels.
        </li>
        <li style="background-color: #4ecfec;">
          <strong>Progress Monitoring</strong>
          Track progress in real-time with dashboards and Gantt charts.
        </li>
        <li style="background-color: #ecb54e;">
          <strong>Deadline Tracking</strong>
          Receive alerts and notifications to meet deadlines efficiently.
        </li>
        <li style="background-color: rgb(232, 102, 102);">
          <strong>Collaborative Tools</strong>
          Enjoy messaging, file sharing, and commenting functionalities for seamless communication.
        </li>
      </ul>
    </section>

    <section class="testimonials" id="testimonials">
      <h2>What Our Users Say</h2>
      <div class="swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <p>"This platform has transformed the way our team works together."</p>
            <strong>- Alex Johnson</strong>
          </div>
          <div class="swiper-slide">
            <p>"We've seen a significant increase in productivity since using this tool."</p>
            <strong>- Maria Rodriguez</strong>
          </div>
          <div class="swiper-slide">
            <p>"An indispensable tool for our project management needs."</p>
            <strong>- John Doe</strong>
          </div>
          <div class="swiper-slide">
            <p>"Task Manager has streamlined our workflow and improved our efficiency."</p>
            <strong>- Sarah Williams</strong>
          </div>
          <div class="swiper-slide">
            <p>"The collaborative features have made team communication much easier."</p>
            <strong>- Michael Brown</strong>
          </div>
          <div class="swiper-slide">
            <p>"I love the deadline tracking feature; it keeps us on schedule."</p>
            <strong>- Emily Davis</strong>
          </div>
          <div class="swiper-slide">
            <p>"The progress monitoring tools are fantastic for keeping track of project milestones."</p>
            <strong>- David Smith</strong>
          </div>
        </div>

        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </section>




    <section id="faq">
      <h2>Frequently Asked Question</h2>
      <ul>
        <li class="faq-item">
          <strong>What is Task Manager?</strong>
          <p class="show-p">Task Manager is a platform designed to help teams efficiently manage their tasks and boost productivity.</p>
        </li>
        <li class="faq-item">
          <strong>How does the progress monitoring feature work?</strong>
          <p>Our platform provides real-time tracking with dashboards and Gantt charts to help you monitor progress effectively.</p>
        </li>

        <li class="faq-item">
          <strong>Can I delegate tasks to other team members?</strong>
          <p>Yes, you can easily create, assign, and delegate tasks based on authorization levels within your team.</p>
        </li>

        <li class="faq-item">
          <strong>What collaborative tools are available?</strong>
          <p>We offer messaging, file sharing, and commenting functionalities to ensure seamless communication among team members.</p>
        </li>

        <li class="faq-item">
          <strong>How do I get started?</strong>
          <p>Simply click the "Sign Up Now" button to create your account and start managing your tasks like a pro.</p>
        </li>
      </ul>
    </section>

    <!-- Call-to-Action Section -->
    <section class="cta" id="signup">
      <h2>Ready to Get Started?</h2>
      <p>Sign up today and start managing your tasks like a pro.</p>
      <a href="./user/create.php" class="cta-button">Sign Up Now</a>
    </section>

    <footer>
      <svg viewBox="0 0 25 23" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6.47027 9.48638C7.62752 7.49982 10.4975 7.49982 11.6547 9.48638L14.2835 13.999C15.4486 15.999 14.0059 18.5091 11.6913 18.5091H6.43368C4.1191 18.5091 2.67638 15.999 3.84145 13.999L6.47027 9.48638Z" fill="white" />
        <path d="M18.3287 14.2693C17.2203 16.3248 14.3029 16.4025 13.1128 14.4083L10.2082 9.54135C9.03298 7.57208 10.427 5.05297 12.7259 4.99171L18.3357 4.84224C20.6346 4.78098 22.1287 7.22314 21.034 9.25289L18.3287 14.2693Z" fill="white" />
      </svg>

      <div>
        &copy; <?php echo date("Y") ?>· Task Management System (TMS)
      </div>
    </footer>

  </div>
</body>



<script>
  const faqItem = document.querySelectorAll(".faq-item");

  for (let index = 0; index < faqItem.length; index++) {
    faqItem[index].querySelector("strong").addEventListener('click', function() {
      faqItem[index].querySelector("p").classList.toggle('show-p')
    })

  }

  const swiper = new Swiper('.swiper', {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 10,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },

    breakpoints: {
      768: {
        slidesPerView: 2,
        spaceBetween: 30,
      },
    },

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });
</script>

</html>