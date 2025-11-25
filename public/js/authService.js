const API_BASE_URL = "http://trator.test/api/auth";

async function handleResponse(response) {
  const data = await response.json();
  if (!response.ok) {
	const message = data?.message || "Request failed";
	throw new Error(message);
  }
  return data;
}

/**
 * REGISTER
 * @param {string} name
 * @param {string} email
 * @param {string} password
 * @param {string} passwordConfirmation
 */
export async function register(name, email, password, passwordConfirmation) {
  const response = await fetch(`${API_BASE_URL}/register`, {
	method: "POST",
	headers: {
	  "Content-Type": "application/json",
	  Accept: "application/json",
	},
	body: JSON.stringify({
	  name,
	  email,
	  password,
	  password_confirmation: passwordConfirmation,
	}),
  });

  const data = await handleResponse(response);

  const token = data?.data?.auth?.token;
  if (token) localStorage.setItem("auth_token", token);

  return data;
}

/**
 * LOGIN
 * @param {string} email
 * @param {string} password
 */
export async function login(email, password) {
  const response = await fetch(`${API_BASE_URL}/login`, {
	method: "POST",
	headers: {
	  "Content-Type": "application/json",
	  Accept: "application/json",
	},
	body: JSON.stringify({ email, password }),
  });

  const data = await handleResponse(response);

  const token = data?.data?.auth?.token;
  if (token) localStorage.setItem("auth_token", token);

  return data;
}

/**
 * LOGOUT
 */
export async function logout() {
  const token = localStorage.getItem("auth_token");
  if (!token) throw new Error("No token found, please login first");

  const response = await fetch(`${API_BASE_URL}/logout`, {
	method: "POST",
	headers: {
	  Authorization: `Bearer ${token}`,
	  "Content-Type": "application/json",
	  Accept: "application/json",
	},
  });

  const data = await handleResponse(response);

  localStorage.removeItem("auth_token");

  return data;
}
