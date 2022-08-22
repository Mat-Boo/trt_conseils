let addCv = document.querySelector('.add_cv');
let deleteCv = document.querySelector('.delete_cv');
let cvBtn = document.querySelector('.cv_btn');
let alertCvMess = document.querySelector('.alert_cv_mess');
let submitBtn = document.querySelector('.submit_btn');
let cvDeleted = false;
let registerCandidateCv = document.querySelector('#register_candidate_cv');

deleteCv.addEventListener('click', () => {
    let confirmDelCvMessage = confirm('Êtes-vous sûr de vouloir supprimer votre CV ? Vous devrez impérativement en ajouter un autre');
    if (confirmDelCvMessage) {
        cvDeleted = true;
        cvBtn.classList.remove('d-flex');
        cvBtn.classList.add('d-none');
        addCv.classList.remove('d-none');
        alertCvMess.classList.add('d-none');
    }
})


submitBtn.addEventListener('click', ((e) => {
    if (cvDeleted && !registerCandidateCv.value) {
        let submitFormMessage = confirm('Le CV est obligatoire, si vous n\'en ajouté pas un nouveau, l\'existant restera sur votre compte');
        if (!submitFormMessage) {
            e.preventDefault();
        }
    }
}))