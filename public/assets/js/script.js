let burger = document.querySelector("#burger")

let menu = [document.querySelector("#menu1"), document.querySelector("#menu2")]

let flexBreak = document.createElement('div')
flexBreak.style.flexBasis = "100%"
flexBreak.id = "flexbreak"
burger.addEventListener("click", function () {
    if (burger.classList.contains("fa-bars")) {
        burger.classList.replace("fa-bars", "fa-times")
        menu.forEach(element => {
            element.classList.remove("dnone")
            element.style.display = "flex"
        })
        // insertAfter(burger.parentElement, flexBreak)
    } else {
        burger.classList.replace("fa-times", "fa-bars")
        menu.forEach(element => {
            element.classList.add("dnone")
            element.style.display = ""
        })
        flexBreak.remove()
    }
})

window.addEventListener('resize', function () {
    if (window.screen.width > 820 && document.querySelector("#flexbreak")) {
        flexBreak.remove()
    } else if (window.screen.width > 820 && menu[0].classList.contains("dnone") && menu[1].classList.contains("dnone")) {
        menu.forEach(element => {
            element.classList.remove("dnone")
            element.style.display = "flex"
        })
    } else if (window.screen.width == 820 && burger.classList.contains("fa-bars") && !menu[0].classList.contains("dnone") && !menu[1].classList.contains("dnone")) {
        menu.forEach(element => {
            element.classList.add("dnone")
            element.style.display = ""
        })
    }
});

window.onload = () => {
    // On cherches toutes les étoiles
    const stars = document.querySelectorAll(".fa-star");
    // On cherche la classe de l'icone star

    const note = document.querySelector("#note");
    // On boucle sur les étoiles pour leurs ajouter des écouteurs d'évênement 
    for (let star of stars) {
        // On écoute le survol
        star.addEventListener("mouseover", function () {
            resetStars();
            this.style.color = "yellow";
            // avec this on prends la valeur de l'étoile qu'on survole

            let previousStar = this.previousElementSibling;
            // Cible l'élément précedent dans le DOM (la balise soeur)
            while (previousStar) {
                // On passe l'étoile qui prècede en jaune
                previousStar.style.color = "yellow";
                previousStar = previousStar.previousElementSibling
            }
            // Tant qu'il y à des étoiles

        });
        star.addEventListener("click", function () {
            // On écoute le click
            note.value = this.dataset.value;
        });
        star.addEventListener("mouseout", function () {
            resetStars(note.value);

        });
    }


    /**
     * Reset les étoiles en vérifiant la note dans l'input hidden
     * @param {number} note 
     */

    function resetStars(note = 0) {
        for (star of stars) {
            if (star.dataset.value > note) {
                star.style.color = "black";
            } else {
                star.style.color = "yellow";
            }
        }
    }

        /** COMMENTAIRES */

        document.querySelectorAll("[data-reply]").forEach(element => {
            // J'initialise un ecouteur d'evenements sur les boutons de réponse
            element.addEventListener("click", function () {
                // Recupere le contexte de l'élément
                document.getElementById("comments_parentid").value = this.dataset.id;
            });
        });


}

