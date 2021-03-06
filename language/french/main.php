<?php
define('_MA_CM_HEADER_SEARCH', 'Recherche :');
    define('_MA_CM_HEADER_MENU', "<a href='index.php'>Accueil</a>&nbsp;|&nbsp;<a href='javascript:history.back()'>Retour</a>");

    define('_MA_CM_INDEX_CATTREEROOT', 'Index');
    define('_MA_CM_INDEX_IDTEXT', 'ID');
    define('_MA_CM_INDEX_NAMETEXT', 'Nom');
    define('_MA_CM_INDEX_DESCTEXT', 'Description');
    define('_MA_CM_INDEX_PREFIXTEXT', 'Préfixe');

    define('_MA_CM_SCATLIST_CDLIST', 'Liste des CD');
    define('_MA_CM_SCATLIST_VIEWRES', 'Visualisation des réservations');
    define('_MA_CM_SCATLIST_COPYTEXT', '(Copie)');
    define('_MA_CM_SCATLIST_IDTEXT', 'ID');
    define('_MA_CM_SCATLIST_NAMETEXT', 'Nom');
    define('_MA_CM_SCATLIST_DESCTEXT', 'Description');
    define('_MA_CM_SCATLIST_PREFIXTEXT', 'Préfixe');
    define('_MA_CM_SCATLIST_LANGTEXT', 'Langage');
    define('_MA_CM_SCATLIST_RENTTEXT', 'Emprunt');
    define('_MA_CM_SCATLIST_OPTIONSTEXT', 'Options');
    define('_MA_CM_SCATLIST_NOTEXT', 'Numéro');
    define('_MA_CM_SCATLIST_HASNOSUBCATTEXT', 'Aucune sous-catégorie');
    define('_MA_CM_SCATLIST_HASNOCD', 'Aucun cd');
    define('_MA_CM_SCATLIST_EDITCD', 'Editer le cd');

    define('_MA_CM_CDDESC_TITLE', 'Informations sur un CD');
    define('_MA_CM_CDDESC_YES', 'Oui');
    define('_MA_CM_CDDESC_NO', 'Non');
    define('_MA_CM_CDDESC_RESERVE', 'Réserver');
    define('_MA_CM_CDDESC_VIEWRES', 'Visualisation des réservations');
    define('_MA_CM_CDDESC_TEXTNO', 'Numéro :');
    define('_MA_CM_CDDESC_TEXTNAME', 'Nom :');
    define('_MA_CM_CDDESC_TEXTDESC', 'Description :');
    define('_MA_CM_CDDESC_TEXTNOSEQ', 'Numéro de séquence :');
    define('_MA_CM_CDDESC_TEXTCOPY', 'Copie :');
    define('_MA_CM_CDDESC_TEXTGROUP', 'Groupe :');
    define('_MA_CM_CDDESC_TEXTLANGUAGE', 'Language :');
    define('_MA_CM_CDDESC_TEXTDATEP', 'Date de parution :');
    define('_MA_CM_CDDESC_TEXTCDKEY', 'CD-Key :');
    define('_MA_CM_CDDESC_TEXTCAT', 'Catégorie :');
    define('_MA_CM_CDDESC_TEXTCD', 'CD ');
    define('_MA_CM_CDDESC_EDITCD', 'Editer le cd');
    define('_MA_CM_CDDESC_INSTOCK', 'Disponible ?');

    define('_MA_CM_CDRESERVE_TITLE', 'Réservation');
    define('_MA_CM_CDRESERVE_RESTEXT1', 'Vous vous apprêtez à réserver le cd ');
    define('_MA_CM_CDRESERVE_RESTEXT2', '. Veuillez confirmer votre réservation en cliquant sur "réserver"');
    define('_MA_CM_CDRESERVE_COPY', 'Copie');
    define('_MA_CM_CDRESERVE_STARTDATE', 'Date de début');
    define('_MA_CM_CDRESERVE_ENDDATE', 'Date de fin');
    define('_MA_CM_CDRESERVE_STARTHOUR', 'Heure de début');
    define('_MA_CM_CDRESERVE_ENDHOUR', 'Heure de fin');
    define('_MA_CM_CDRESERVE_RESERVE', 'Réserver');
    define('_MA_CM_CDRESERVE_GROUPRESERVE', 'Réserver le groupe');
    define('_MA_CM_CDRESERVE_NOTE', 'Note');

    define('_MA_CM_GROUPDESC_TITLE', 'Description d\'un groupe');

    define('_MA_CM_RESDESC_RESTITLE', 'Informations sur la réservation');
    define('_MA_CM_RESDESC_CDTITLE', 'Informations sur le cd');
    define('_MA_CM_RESDESC_TEXTNO', 'Numéro :');
    define('_MA_CM_RESDESC_TEXTNAME', 'Nom :');
    define('_MA_CM_RESDESC_TEXTNOTE', 'Note :');
    define('_MA_CM_RESDESC_TEXTNOSEQ', 'Numéro de séquence :');
    define('_MA_CM_RESDESC_TEXTCOPY', ' Copie ');
    define('_MA_CM_RESDESC_TEXTCDDESC', 'Description : ');
    define('_MA_CM_RESDESC_TEXTCDKEY', 'CD-Key :');
    define('_MA_CM_RESDESC_TEXTDATESTART', 'Date de début :');
    define('_MA_CM_RESDESC_TEXTDATEEND', 'Date de fin :');
    define('_MA_CM_RESDESC_TEXTCD', ' CD ');
    define('_MA_CM_RESDESC_TEXTUSER', 'Emprunté par :');

    define('_MA_CM_VIEWDESC_TEXTCD', ' CD ');
    define('_MA_CM_VIEWDESC_TEXTCOPY', ' - Copie ');

    define('_MA_CM_DONE_ERRORENDHIGHERTHANSTART', 'L\'heure de début est plus grand que l\'heure de fin <br> OU <br> Vous ne pouvez réserver à une date antérieur à celle d\'aujourd\'hui');
    define('_MA_CM_DONE_ERRORCANTRESERVE', 'Vous ne pouvez réserver ce cd pour cette période de temps car il est déjà réservé.');
    define('_MA_CM_DONE_RESAPPROVED', 'Votre réservation est approuvée, il ne vous reste qu\'à imprimer votre relevé et l\'amener aux techniciens pour réclamer votre CD');
    define('_MA_CM_DONE_PRINT', 'Imprimer');
    define('_MA_CM_DONE_MAILSUBJECT', 'Notification d\'une réservation de CD');

    define('_MA_CM_PRINT_TITLE', 'Réservation de CD');
    define('_MA_CM_PRINT_TEXTCOPY', ' (Copie) ');
    define('_MA_CM_PRINT_TEXTCD', ' CD ');
    define('_MA_CM_PRINT_PRINT', 'Imprimer');
    define('_MA_CM_PRINT_COMMAND', 'Bon de commande');
    define('_MA_CM_PRINT_USERNAME', 'Nom de l\'utilisateur : ');
    define('_MA_CM_PRINT_CDNAME', 'Nom du cd : ');
    define('_MA_CM_PRINT_CDNO', 'Numéro : ');
    define('_MA_CM_PRINT_CDDATESTART', 'Date de début : ');
    define('_MA_CM_PRINT_CDDATEBACK', 'Date de fin : ');
    define('_MA_CM_PRINT_NOACCESS', 'Vous n\'avez pas accès à cette page!');

    define('_MA_CM_SEARCH_TITLE', 'Résultat de la recherche');
    define('_MA_CM_SEARCH_SEARCHTEXT', 'Texte recherché : ');
    define('_MA_CM_SEARCH_RESULTS', 'Résultats');
    define('_MA_CM_SEARCH_CDTEXT', ' - CD ');
    define('_MA_CM_SEARCH_COPYTEXT', ' (Copie)');

    define('_MA_CM_VIEWRES_CDRETURNED', 'CD remis');
    define('_MA_CM_VIEWRES_CDLATENAPPROVED', 'CD en retard et non-approuvé');
    define('_MA_CM_VIEWRES_CDLATEAPPROVED', 'CD en retard et approuvé');
    define('_MA_CM_VIEWRES_CDTOCOMENAPPROVED', 'CD en cours/à venir et non-approuvé');
    define('_MA_CM_VIEWRES_CDTOCOMEAPPROVED', 'CD en cours/à venir et approuvé');
    define('_MA_CM_VIEWRES_LEGEND', 'Légende : ');

    //  footer
    define('_MA_CM_FOOTER_COUNTCD', 'Nombre de cd au total : ');

    // général
    define('_MA_CM_GENERAL_TO', ' à ');
    define('_MA_CM_NONID', 'Id non-conforme');
    define('_MA_CM_NORIGHTS', 'Vous n\'avez pas accès à cette zone');
