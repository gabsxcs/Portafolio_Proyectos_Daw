window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (window.scrollY > 100) {
      header.style.backgroundColor = 'rgba(26, 26, 26, 0.95)';
      header.style.padding = '0.8em 5%';
    } else {
      header.style.backgroundColor = 'rgba(26, 26, 26, 0.95)';
      header.style.padding = '1.2em 5%';
    }
  });

  const menuToggle = document.querySelector('.menu-toggle');
  const navLinks = document.querySelector('.nav-links');
  const icon = menuToggle.querySelector('i');

  menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    menuToggle.classList.toggle('active');
  });