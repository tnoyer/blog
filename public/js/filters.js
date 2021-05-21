const FiltersForm = document.querySelector("#filters");

//on boucle sur les input
document.querySelectorAll("#filters input").forEach(input => {
    input.addEventListener("change", function () {
        //on intercepte les clicks sur les input
        //on récupère les données du formulaire
        const Form = new FormData(FiltersForm);

        //on fabrique la query string
        const Params = new URLSearchParams();

        Form.forEach(((value, key) => {
            Params.append(key, value);
        }))

        //on récupère l'url active
        const Url = new URL(window.location.href);
        console.log(Url);
    });
});