import { login, register } from "./authService.js";

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

// === HANDLE LOGIN ===
loginForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const email = document.getElementById("loginEmail").value;
  const password = document.getElementById("loginPassword").value;

  try {
    const data = await login(email, password);
    console.log("Login response:", data);

    window.location.href = "contact.html";
  } catch (err) {
    alert("Login gagal: " + err.message);
  }
});

// === HANDLE REGISTER ===
registerForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const name = document.getElementById("regUsername").value;
  const email = document.getElementById("regEmail").value;
  const password = document.getElementById("regPassword").value;
  const passwordConfirmation = document.getElementById("regConfirm").value;

  try {
    const data = await register(name, email, password, passwordConfirmation);
    console.log("Register response:", data);

    window.location.href = "contact.html";
  } catch (err) {
    alert("Registrasi gagal: " + err.message);
  }
});