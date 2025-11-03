function validateSignupForm(event) {
  event.preventDefault(); // Stop form from submitting

  const name = document.getElementById("signupName").value.trim();
  const email = document.getElementById("signupEmail").value.trim();
  const phone = document.getElementById("signupPhone").value.trim();
  const role = document.getElementById("signupRole").value;
  const address = document.getElementById("signupAddress").value.trim();
  const password = document.getElementById("signupPassword").value;
  const confirm = document.getElementById("signupConfirm").value;

  if (
    name === "" ||
    email === "" ||
    phone === "" ||
    address === "" ||
    password === "" ||
    confirm === ""
  ) {
    alert("⚠️ All fields are required!");
    return false;
  }

  // Email format check
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    alert("Please enter a valid email address.");
    return false;
  }

  // Phone validation (10 digits)
  if (!/^\d{10}$/.test(phone)) {
    alert("Phone number must be 10 digits.");
    return false;
  }

  // Role validation
  if (role === "") {
    alert("Please select your role (Customer or Contractor).");
    return false;
  }

  // Password match
  if (password !== confirm) {
    alert("Passwords do not match!");
    return false;
  }

  // Everything is okay
  alert("✅ Registration successful!");
  event.target.submit();
}
function validateLoginForm(event) {
  event.preventDefault();

  const email = document.getElementById("loginEmail").value.trim();
  const password = document.getElementById("loginPassword").value.trim();

  if (email === "" || password === "") {
    alert("Please fill in both fields!");
    return false;
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    alert("Enter a valid email address.");
    return false;
  }

  event.target.submit();
}
