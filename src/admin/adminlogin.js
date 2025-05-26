const form = document.querySelector('.form');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const inputGroups = document.querySelectorAll('.input-group');


function showMessage(inputGroup, message, type) {
  inputGroup.classList.remove('error', 'success');
  inputGroup.classList.add(type);

  const msg = inputGroup.querySelector('.msg');
  if (msg) {
    msg.style.display = 'block';
    msg.textContent = message;
  }
}

function resetMessages() {
  inputGroups.forEach(group => {
    group.classList.remove('error', 'success');
    const msg = group.querySelector('.msg');
    if (msg) {
      msg.style.display = 'none';
      msg.textContent = '';
    }
  });
}

form.addEventListener('submit', async (event) => {
  event.preventDefault();

  resetMessages();

  const formData = new FormData();
  formData.append('username', usernameInput.value.trim());
  formData.append('password', passwordInput.value.trim());

  try {
    const response = await fetch('adminLogin.php', {
      method: 'POST',
      body: formData,
    });

    const result = await response.json();

    if (response.ok && result.success) {
      window.location.href = 'admin.php';
    } else {
      const errorMsg = result.message || 'Invalid username or password';
      showMessage(passwordInput.parentElement, errorMsg, 'error');
      passwordInput.parentElement.classList.add('error');
    }
  } catch (error) {
    showMessage(passwordInput.parentElement, 'An error occurred. Please try again.', 'error');
  }
});

inputGroups.forEach(group => {
  const input = group.querySelector('input');
  input.addEventListener('input', () => {
    group.classList.remove('error', 'success');
    const msg = group.querySelector('.msg');
    if (msg) {
      msg.style.display = 'none';
      msg.textContent = '';
    }
  });
});
