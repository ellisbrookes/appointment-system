<aside class="sidebar" id="sidebar">
  <h2>Navigation</h2>

  <ul class="sidebar-menu">
    <li>
      <a href="/">
        <i class="fas fa-calendar-check"></i> Appointments
      </a>
    </li>
    <li>
      <a href="#">
        <i class="fas fa-user"></i> Profile
      </a>
    </li>
    <li>
      <a href="#">
        <i class="fas fa-tools"></i> Admin Dashboard
      </a>
    </li>
  </ul>

  <button class="toggle-button" id="closeButton">
    <i class="fas fa-arrow-left"></i>
  </button>
</aside>

<button class="reopen-button" id="reopenButton">
  <i class="fas fa-bars"></i>
</button>

<script>
  const sidebar = document.getElementById('sidebar');
  const closeButton = document.getElementById('closeButton');
  const reopenButton = document.getElementById('reopenButton');
  const mainContent = document.getElementById('mainContent');

  closeButton.addEventListener('click', () => {
    sidebar.classList.add('closed');
    mainContent.classList.add('shifted');
    reopenButton.style.display = 'block';
  });

  reopenButton.addEventListener('click', () => {
    sidebar.classList.remove('closed');
    mainContent.classList.remove('shifted');
    reopenButton.style.display = 'none';
  });
</script>
