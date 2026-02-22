function validateSignupForm(event) {
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
    event.preventDefault(); // only stop if error
    return;
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    alert("Please enter a valid email address.");
    event.preventDefault();
    return;
  }

  if (!/^\d{10}$/.test(phone)) {
    alert("Phone number must be 10 digits.");
    event.preventDefault();
    return;
  }

  if (role === "") {
    alert("Please select your role.");
    event.preventDefault();
    return;
  }

  if (password !== confirm) {
    alert("Passwords do not match!");
    event.preventDefault();
    return;
  }

  
}
