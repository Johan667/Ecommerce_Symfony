
tarteaucitron.init({
    "privacyUrl" : "confidentialite",
    /* URL page mentions */
    "hashtag": "#tarteaucitron",
    /* Ouvrir le panneau du hashtag */
    "cookieName": "tarteaucitron",
    /* Nom du cookie */
    "orientation": "middle",
    /* Position banniere (top - bottom) */
    "groupServices": false,
    /* Groupe les services par categories */
    "showAlertSmall": false,
    /* Petite alerte en bas a droite */
    "cookieslist": false,
    /* Montre la liste des cookies */
    "closePopup": false,
    /* Fermer le popup */
    "showIcon": true,
    /* Montrer l'icone cookie */
    // "iconSrc": "", /* Optionnel: URL ou base64 image encodé */
    "iconPosition": "BottomLeft",
    /* BottomRight, BottomLeft, TopRight and TopLeft */
    "adblocker": false,
    /* Detecte AD block */
    "DenyAllCta": true,
    /* Montre le bouton refuser tout */
    "AcceptAllCta": true,
    /* Montre accepter tout */
    "highPrivacy": true,
    /* Desactive le contenu automatique */
    "handleBrowserDNTRequest": false,
    /* If Do Not Track == 1, disallow all */
    "removeCredit": true,
    /* Retire le lien vers tarteaucitron */
    "moreInfoLink": true,
    /* Lien Plus d'infos */
    "useExternalCss": false,
    /* Si faut le CSS externe sera charger */
    "useExternalJs": false,
    /* Si faut le JS externe sera charger */
    // "cookieDomain": ".my-multisite-domaine.fr", /* Partage les cookies sur un multidomaine */
    "readmoreLink": "",
    /* Changer le lien "lire plus"*/
    "mandatory": true,
    /* Montre un message a propose des cookies "mandatory" */
});
tarteaucitron.services.mycustomservice = {
    "key": "mycustomservice",
    "type": "ads|analytic|api|comment|other|social|support|video",
    "name": "MyCustomService",
    "needConsent": true,
    "cookies": [
        'cookie', 'cookie2'
    ],
    "readmoreLink": "/custom_read_more", // If you want to change readmore link
    "js": function () {
        "use strict";
        // When user allow cookie
    },
    "fallback": function () {
        "use strict";
        // when use deny cookie
    }
};
