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
