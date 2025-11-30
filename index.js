function toSignUp() {
    let loginForm = document.querySelector('.loginForm');
    let signUpForm = document.querySelector('.signUpForm');
    
    if (loginForm && signUpForm) {
        loginForm.classList.add('hidden');
        signUpForm.classList.remove('hidden');
    }
    return false; 
}

function toLogin() {
    let loginForm = document.querySelector('.loginForm');
    let signUpForm = document.querySelector('.signUpForm');
    
    if (loginForm && signUpForm) {
        loginForm.classList.remove('hidden');
        signUpForm.classList.add('hidden');
    }
    return false; 
}