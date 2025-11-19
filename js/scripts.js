/*!
* Start Bootstrap - Resume v7.0.6 (https://startbootstrap.com/theme/resume)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-resume/blob/master/LICENSE)
*/
//
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    newFunction();

    // Activate Bootstrap scrollspy on the main nav element
    const sideNav = document.body.querySelector('#sideNav');
    if (sideNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#sideNav',
            rootMargin: '0px 0px -40%',
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

});




function newFunction() {
    const modal = document.getElementById("trajetModal");
    const openBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementById("closeModalBtn");

    openBtn.onclick = () => modal.style.display = "block";
    closeBtn.onclick = () => modal.style.display = "none";

    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}



// Modale profilePass

function openModal(covoiturage_id) {
    const modal = document.getElementById('avisModal');
    document.getElementById('covoiturage_id_input').value = covoiturage_id;
    document.getElementById('commentaire_input').value = '';
    document.getElementById('avisModalTitle').textContent = 'Laisser un avis sur le trajet';
    modal.style.display = 'block';
}


function closeModal() {
    document.getElementById('avisModal').style.display = 'none';
}

document.getElementById('avisModalClose').addEventListener('click', closeModal);
window.addEventListener('click', function (e) {
    const modal = document.getElementById('avisModal');
    if (e.target === modal) closeModal();
});




// Modale btn ajouter voituer


function openVehiculeModal() {
    const modal = document.getElementById('vehiculeModal');
    document.getElementById('vehiculeModalTitle').textContent = 'Enregistrer un véhicule';
    modal.style.display = 'block';
}

function closeVehiculeModal() {
    document.getElementById('vehiculeModal').style.display = 'none';
}

document.getElementById('vehiculeModalClose').addEventListener('click', closeVehiculeModal);
window.addEventListener('click', function (e) {
    const modal = document.getElementById('vehiculeModal');
    if (e.target === modal) closeVehiculeModal();
});


// Note


window.onload = () => {

    const stars = document.querySelectorAll(".stars i");

    const note = document.querySelector("#note");

    for (star of stars) {
        star.addEventListener("mouseover", function () {
            resetStars();
            this.style.color = "red";
            this.classList.add("las");
            this.classList.remove("lar");

            let previousStar = this.previousElementSibling;

            while (previousStar) {
                previousStar.style.color = "red";
                previousStar.classList.add("las");
                previousStar.classList.remove("lar");
                previousStar = previousStar.previousElementSibling;
            }
        });

        star.addEventListener("click", function () {
            note.value = this.dataset.value;
        });

        star.addEventListener("mouseout", function () {
            resetStars(note.value);
        });

    }


    function resetStars(nb = 0) {
        for (star of stars) {
            if (parseInt(star.dataset.value) <= nb) {
                star.style.color = "red";
                star.classList.add("las");
                star.classList.remove("lar");
            } else {
                star.style.color = "black";
                star.classList.add("lar");
                star.classList.remove("las");
            }
        }
    }



}










