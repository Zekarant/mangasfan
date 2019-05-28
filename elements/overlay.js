function displayConnexionOverlay(event) {
    if (event === 'connexionLink') {
        $('.overlayConnexionInsc').show();
    }
    else if (event === 'inscriptionLink'){
        $('.overlayConnexionInsc').show();
        swapOnglet('inscription');
    }
}

$('.bouton_connexion').click(function (event) {
    displayConnexionOverlay(event.target.classList.item(0));
});

$('.conteneurConnexionInsc').click(function (event) {
    swapOnglet(event.target.classList.item(0));
    hideConnexionOverlay(event.target.classList.item(0));
})

function swapOnglet(event) {
    if (event === "inscription" || event === "connexion") {
        $('.formulairesConnexionInsc > form').hide();
        $('.bandeauConteneur > h2').hide();
        $('.ongletsConnexionInsc > li').removeClass('buttonGreenBG');
        $('.' + event).addClass('buttonGreenBG');
        $('.' + event + 'Formulaire').show();
        $('.' + event + 'Disp').show();
    }
}

function hideConnexionOverlay(event) {
    if (event === 'closeOverlay') {
        $('.overlayConnexionInsc').hide();
    }
}