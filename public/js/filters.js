window.onload = function() {
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

            //On lance la requète Ajax
            fetch(Url.pathname + "?" + Params.toString() + "&ajax=1", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response =>
                response.json()
            ).then(data => {
                //zone de listing des articles
                const content = document.querySelector("#content");
                //on remplace le contenu
                content.innerHTML = data.content;
                //on met à jour l'url
                history.pushState({}, null, Url.pathname + "?" + Params.toString());
            }).catch(e => alert(e))
        });
    });
};

