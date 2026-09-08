</main>

  <!-- ======================= FOOTER (cream, with Contact Us) ======================= -->
  <footer class="footer" id="contact">
    <div class="container footer-main">
      <div class="footer-brand reveal">
        <a href="<?php echo ($page ?? "home") === "home" ? "#home" : "index.php"; ?>" class="logo footer-logo" aria-label="Art Terre Creations — Home">
          <img class="logo-img" src="images/logo.png" alt="Art Terre Creations logo" />
        </a>

        <p class="footer-mission">
          To bridge artists and collectors worldwide by creating a sustainable platform
          that showcases authentic creativity and opens opportunities for every artist to thrive.
        </p>

        <div class="socials">
          <a href="#" aria-label="Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r="1.1" fill="currentColor" stroke="none"/></svg>
            Instagram
          </a>
          <a href="#" aria-label="LinkedIn">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1V21h-4v-5.5c0-1.31-.02-3-1.83-3-1.83 0-2.11 1.43-2.11 2.9V21H9z"/></svg>
            LinkedIn
          </a>
          <a href="#" aria-label="Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.5-3.89 3.78-3.89 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.45 2.89h-2.33v6.99A10 10 0 0 0 22 12Z"/></svg>
            Facebook
          </a>
        </div>
      </div>

      <div class="footer-contact reveal">
        <h2>Contact Us</h2>
        <p>
          Have a question, want to collaborate, or simply want to learn more about
          Art Terre? We’d love to hear from you.
        </p>

        <form class="contact-form" id="contact-form" novalidate>
          <div class="line-field">
            <label for="c-name">Name :</label>
            <input type="text" id="c-name" name="name" autocomplete="name" required />
          </div>
          <div class="line-field">
            <label for="c-email">Email :</label>
            <input type="email" id="c-email" name="email" autocomplete="email" required />
          </div>
          <div class="line-field line-field--message">
            <label for="c-message">Message :</label>
            <input type="text" id="c-message" name="message" required />
          </div>
          <div class="line-field line-field--bare">
            <input type="text" id="c-message2" name="message2" aria-label="Message continued" />
          </div>
          <div class="contact-submit">
            <button type="submit" class="btn btn-accent">Send Us a Message</button>
          </div>
          <p class="form-msg" id="contact-msg" role="status" aria-live="polite"></p>
        </form>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container footer-bottom-inner">
        <div class="fb-left">
          <a href="#">Support & Help</a>
          <a href="#">FAQ</a>
        </div>
        <div class="fb-right">
          <a href="#">Terms of services</a>
          <span class="divider" aria-hidden="true"></span>
          <a href="#">Privacy Policy</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Back to top -->
  <button class="to-top" id="to-top" aria-label="Back to top">↑</button>

  <!-- JavaScript -->
  <script src="js/script.js?v=6"></script>
</body>
</html>