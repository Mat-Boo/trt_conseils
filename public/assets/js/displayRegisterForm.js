let userSelect = document.querySelector('#user_select');
let nextBtn = document.querySelector('.next_btn');
let formCandidate = document.querySelector('.formCandidate');
let formRecruiter = document.querySelector('.formRecruiter');
let hrSignup = document.querySelector('.hrSignup');
let alertDanger = document.querySelector('.alert-danger');
let alertSuccess = document.querySelector('.alert-success');

nextBtn.addEventListener('click', () => {
    if (userSelect.value === 'candidate') {
        hrSignup.style.display = 'block'
        formCandidate.style.display = 'block';
        formRecruiter.style.display = 'none';
    }
    if (userSelect.value === 'recruiter') {
        hrSignup.style.display = 'block'
        formRecruiter.style.display = 'block';
        formCandidate.style.display = 'none';
    }
    if (userSelect.value === '') {
        hrSignup.style.display = 'none'
        formRecruiter.style.display = 'none';
        formCandidate.style.display = 'none';
    }
})

console.log(formCandidate);

if (keyPost !== null) {
    if (keyPost === 'register_candidate') {
        userSelect.value = 'candidate';
        hrSignup.style.display = 'block'
        formCandidate.style.display = 'block';
    }
    if (keyPost === 'register_recruiter') {
    userSelect.value = 'recruiter';
    hrSignup.style.display = 'block'
    formRecruiter.style.display = 'block';
    }
}

