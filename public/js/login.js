// Toggle Login/Register
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');
const loginSwitch = document.getElementById('loginSwitch');
const registerSwitch = document.getElementById('registerSwitch');

registerSwitch.addEventListener('click', () => {
  registerForm.classList.add('active');
  loginForm.classList.remove('active');
  registerSwitch.classList.add('active');
  loginSwitch.classList.remove('active');
});

loginSwitch.addEventListener('click', () => {
  loginForm.classList.add('active');
  registerForm.classList.remove('active');
  loginSwitch.classList.add('active');
  registerSwitch.classList.remove('active');
});

// Carousel Logic
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
let index = 0;

function showSlide(i) {
  slides.forEach((slide, idx) => {
    slide.classList.toggle('active', idx === i);
    dots[idx].classList.toggle('active', idx === i);
  });
}

function nextSlide() {
  index = (index + 1) % slides.length;
  showSlide(index);
}

dots.forEach((dot, i) => {
  dot.addEventListener('click', () => {
    index = i;
    showSlide(index);
  });
});

// Auto-slide every 5 seconds
setInterval(nextSlide, 5000);
