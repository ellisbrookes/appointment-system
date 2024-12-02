<?php
require_once "./setup.php";

$title = "Homepage";

include "./partials/shared/head.php";
include "./partials/shared/alerts.php";
include "./partials/shared/navigation.php";
?>

<!-- Main Content -->
<main>
  <!-- Hero Section -->
  <header class="hero">
    <div class="container">
      <h1 class="text-center">Manage Your Appointments Effortlessly</h1>
      <p>
        Streamline your scheduling process and save time with our intuitive
        platform.
      </p>
    </div>
  </header>

  <!-- Features Section -->
  <section id="features" class="features">
    <div class="container">
      <h2>Features</h2>

      <div class="features-grid">
        <div class="feature-item">
          <h3>Feature 1</h3>
          <p>Description of feature 1.</p>
        </div>

        <div class="feature-item">
          <h3>Feature 2</h3>
          <p>Description of feature 2.</p>
        </div>

        <div class="feature-item">
          <h3>Feature 3</h3>
          <p>Description of feature 3.</p>
        </div>

        <div class="feature-item">
          <h3>Feature 4</h3>
          <p>Description of feature 4.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing Section -->
  <section id="pricing" class="pricing">
    <div class="container">
      <h2>Pricing</h2>

      <div class="pricing-table">
        <div class="plan">
          <h3>Basic</h3>
          <p>£10/month</p>

          <ul>
            <li>Feature 1</li>
            <li>Feature 2</li>
            <li>Feature 3</li>
          </ul>

          <button>Choose Plan</button>
        </div>

        <div class="plan">
          <h3>Advanced</h3>
          <p>£20/month</p>

          <ul>
            <li>Feature 1</li>
            <li>Feature 2</li>
            <li>Feature 3</li>
          </ul>

          <button>Choose Plan</button>
        </div>

        <div class="plan">
          <h3>Pro</h3>
          <p>£40/month</p>

          <ul>
            <li>Feature 1</li>
            <li>Feature 2</li>
            <li>Feature 3</li>
          </ul>

          <button>Choose Plan</button>
        </div>
      </div>
    </div>
  </section>

  <!-- Statistics Section -->
  <section id="statistics" class="statistics">
    <div class="container">
      <h2>Our Achievements</h2>

      <div class="stats-grid">
        <div class="stat-item">
          <i class="fas fa-users fa-3x"></i>
          <h3>10,000+</h3>
          <p>Users</p>
        </div>

        <div class="stat-item">
          <i class="fas fa-calendar-check fa-3x"></i>
          <h3>50,000+</h3>
          <p>Appointments Scheduled</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="contact">
    <div class="container">
      <h2>Contact Us</h2>
      <p>Have questions or need support? We're here to help!</p>

      <form class="contact-form">
        <input type="text" placeholder="Your Name" required />
        <input type="email" placeholder="Your Email" required />
        <textarea placeholder="Your Message" rows="5" required></textarea>
        <button type="submit">Send Message</button>
      </form>
    </div>
  </section>
</main>

<?php include "./partials/shared/footer.php"; ?>
