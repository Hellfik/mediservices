//Execute un appel AJAX GET
//Prend en parametre l'url cible et la fonction callback appelee en cas de succes
function ajaxGet(url, callback) {
    let req = new XMLHttpRequest();
    req.open('GET', url);

    req.addEventListener('load', function() {
        if (req.status >= 200 && req.status < 400) {
            //Appel de la fonction callback en lui passant la reponse de la requete
            callback(req.responseText);
        } else {
            console.error(req.status + ' ' + req.statusText + ' ' + url);
        }
    });

    req.addEventListener('error', function() {
        console.error('Erreur reseau avec l\'url ' + url);
    });
    req.send(null);
}