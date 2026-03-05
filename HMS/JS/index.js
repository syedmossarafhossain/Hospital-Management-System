const container = document.querySelector('.container');
const loginBtn = document.querySelector('.login-btn');

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});
