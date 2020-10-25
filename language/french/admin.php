<?php

    //Titre du module d'admin
    define('_AM_CM_ADMINTITLE', "Menu d'administration");

    //Pour le petit menu en haut
    define('_AM_CM_MENU_PREFERENCES', 'Préférences');
    define('_AM_CM_MENU_REPERTOIRECHECK', "Vérifier les répertoires d'upload");
    define('_AM_CM_MENU_UPDATEMODULE', 'Mettre à jour le module');

    //Pour le menu principal admin
    define('_AM_CM_MENU_INDEX', 'Index');
    define('_AM_CM_MENU_ADMINCAT', 'Administration : Catégories');
    define('_AM_CM_MENU_ADMINCD', 'Administration : CD');
    define('_AM_CM_MENU_ADMINGROUP', 'Administration : Groupes de CD');
    define('_AM_CM_MENU_ADMINRENT', 'Administration : Emprunts');

    //Section Administration des catégories
    define('_AM_CM_CAT_ADMIN_ADMINCAT', 'Administration des catégories');
    define('_AM_CM_CAT_ADMIN_ADDCATPRINC', "Ajout d'une catégorie PRINCIPALE");
    define('_AM_CM_CAT_ADMIN_ADDCATSUB', "Ajout d'une sous-catégorie");
    define('_AM_CM_CAT_ADMIN_MODIFCAT', "Modification d'une catégorie/sous-catégorie");

    define('_AM_CM_CAT_ADD_CATTEXT', 'Catégorie : ');
    define('_AM_CM_CAT_ADD_DESC', 'Description : ');
    define('_AM_CM_CAT_ADD_CATPARENT', 'Catégorie parent : ');
    define('_AM_CM_CAT_ADD_PREFIX', 'Préfixe : ');
    define('_AM_CM_CAT_ADD_ADD', 'Ajouter');

    define('_AM_CM_CAT_MODIFY_CATTEXT', 'Catégorie : ');
    define('_AM_CM_CAT_MODIFY_DESC', 'Description : ');
    define('_AM_CM_CAT_MODIFY_PREFIX', 'Préfixe : ');
    define('_AM_CM_CAT_MODIFY_CATPARENT', 'Catégorie parent : ');

    define('_AM_CM_CAT_MODIFY_MODIFY', 'Modifier');
    define('_AM_CM_CAT_MODIFY_ERRORCATPARENT', 'La catégorie parent et la catégorie à modifier sont pareilles');

    define('_AM_CM_CAT_DEL_DEL', 'Supprimer');
    define('_AM_CM_CAT_DEL_YES', 'Oui');
    define('_AM_CM_CAT_DEL_NO', 'Non');
    define('_AM_CM_CAT_DEL_AREYOUSURE', 'Êtes-vous sûr de vouloir supprimer cette catégorie');
    define('_AM_CM_CAT_DEL_CONFIRM', 'Confirmation');
    define('_AM_CM_CAT_DEL_CANNOTDEL', 'Vous ne pouvez supprimer une catégorie qui a des sous-catégories ou qui contient des cd. Veuillez supprimer ou changer de parent les sous-catégories.');

    //Section Administration des cd
    define('_AM_CM_CD_ADMIN_ADMINCD', 'Administration des cd');
    define('_AM_CM_CD_ADMIN_ADDCD', "Ajout d'un cd");
    define('_AM_CM_CD_ADMIN_CDLIST', 'Liste des cd');
    define('_AM_CM_CD_ADMIN_PRINTCDLIST', 'Imprimer la liste des cd');
    define('_AM_CM_CD_ADMIN_PRINTCDKEYLIST', 'Imprimer la liste de tous les cd-key');

    define('_AM_CM_CD_ADD_NAME', 'Nom du cd :');
    define('_AM_CM_CD_ADD_DESC', 'Description :');
    define('_AM_CM_CD_ADD_NO', 'Numéro :');
    define('_AM_CM_CD_ADD_NOGROUP', 'Numéro de séquence dans le groupe :');
    define('_AM_CM_CD_ADD_COPY', 'Copie :');
    define('_AM_CM_CD_ADD_STATUS', 'En bon état :');
    define('_AM_CM_CD_ADD_CATTEXT', 'Catégorie : ');
    define('_AM_CM_CD_ADD_GROUP', 'Groupe : ');
    define('_AM_CM_CD_ADD_LANGUAGE', 'Langue : ');
    define('_AM_CM_CD_ADD_DATEP', 'Date de parution : ');
    define('_AM_CM_CD_ADD_CDKEY', 'CD-Key : ');
    define('_AM_CM_CD_ADD_ERRORNOCAT', 'Vous devez choisir une catégorie pour le cd');
    define('_AM_CM_CD_ADD_NOEXISTS', 'Le numéro de cd que vous avez choisi a déjà été attribué');

    define('_AM_CM_CD_LIST_ERRORIDNONCONFORM', 'Id de cd non-conforme !!');

    define('_AM_CM_CD_DESC_TITLE', 'Fiche descriptive');
    define('_AM_CM_CD_DESC_NAME', 'Nom du cd :');
    define('_AM_CM_CD_DESC_DESC', 'Description :');
    define('_AM_CM_CD_DESC_NO', 'Numéro :');
    define('_AM_CM_CD_DESC_NOGROUP', 'Numéro de séquence dans le groupe :');
    define('_AM_CM_CD_DESC_COPY', 'Copie :');
    define('_AM_CM_CD_DESC_COPYTEXT', 'Copie');
    define('_AM_CM_CD_DESC_STATUS', 'État');
    define('_AM_CM_CD_DESC_STATUSGOOD', 'En bon état');
    define('_AM_CM_CD_DESC_STATUSNGOOD', 'Défecteux');
    define('_AM_CM_CD_DESC_MEMBERGROUP', 'Membre du groupe :');
    define('_AM_CM_CD_DESC_MEMBERCAT', 'Membre de la catégorie :');
    define('_AM_CM_CD_DESC_YESTEXT', 'Oui');
    define('_AM_CM_CD_DESC_NOTEXT', 'Non');

    define('_AM_CM_CD_DEL_DEL', 'Supprimer');
    define('_AM_CM_CD_DEL_CONFIRM', 'Confirmation');
    define('_AM_CM_CD_DEL_CNAME', 'CD : ');
    define('_AM_CM_CD_DEL_AREYOUSURE', 'Êtes-vous sûr de vouloir supprimer ce cd');
    define('_AM_CM_CD_DEL_YES', 'Oui');
    define('_AM_CM_CD_DEL_NO', 'Non');

    define('_AM_CM_CD_MODIFY_MODIFY', 'Modifier');

    // Section Administration des groupes
    define('_AM_CM_GR_ADMIN_ADMINGROUP', 'Administration des groupes');
    define('_AM_CM_GR_ADMIN_ADDGROUP', "Ajout d'un groupe");
    define('_AM_CM_GR_ADMIN_GRLIST', 'Liste des groupes');

    define('_AM_CM_GR_ADD_GROUPTEXT', "Ajout d'un groupe :");
    define('_AM_CM_GR_ADD_DESC', 'Description :');
    define('_AM_CM_GR_ADD_ADD', 'Ajouter');
    define('_AM_CM_GR_ADD_ERRORNONAME', 'Vous devez obligatoirement entrer un nom pour le groupe');

    define('_AM_CM_GR_DESC_TITLE', 'Fiche descriptive');
    define('_AM_CM_GR_DESC_NAME', 'Nom du groupe :');
    define('_AM_CM_GR_DESC_DESC', 'Description :');
    define('_AM_CM_GR_DESC_NUMBEROFCD', 'Les CD ');
    define('_AM_CM_GR_DESC_COPYTEXT', ' Copie ');

    define('_AM_CM_GR_LIST_NAME', 'Nom :');
    define('_AM_CM_GR_LIST_DESC', 'Description :');
    define('_AM_CM_GR_LIST_ERRORIDNONCONFORM', 'Id de groupe non-conforme !!');

    define('_AM_CM_GR_MODIFY_TITLE', 'Fiche descriptive');
    define('_AM_CM_GR_MODIFY_NAME', 'Nom :');
    define('_AM_CM_GR_MODIFY_DESC', 'Description :');
    define('_AM_CM_GR_MODIFY_MODIFY', 'Modifier');

    define('_AM_CM_GR_CLONE_CLONE', 'Clôner');

    define('_AM_CM_GR_DEL_DEL', 'Supprimer');
    define('_AM_CM_GR_DEL_AREYOUSURE', 'Êtes-vous sûr de vouloir supprimer ce cd');
    define('_AM_CM_GR_DEL_CONFIRM', 'Confirmation');
    define('_AM_CM_GR_DEL_NAME', 'Nom du groupe :');
    define('_AM_CM_GR_DEL_YES', 'Oui');
    define('_AM_CM_GR_DEL_NO', 'Non');
    define('_AM_CM_DR_DEL_CANNOTDEL', 'Vous ne pouvez supprimez un groupe qui contient encore des cd.');

    // section administration des emprunts
    define('_AM_CM_RENT_ADMIN_ADMINRENT', 'Administration des emprunts');
    define('_AM_CM_RENT_ADMIN_CDRETURNED', 'Liste des cd remis');
    define('_AM_CM_RENT_ADMIN_CDLATENAPPROVED', 'Liste des cd en retard et non-approuvé');
    define('_AM_CM_RENT_ADMIN_CDLATEAPPROVED', 'Liste des cd en retard et approuvé');
    define('_AM_CM_RENT_ADMIN_CDTOCOMENAPPROVED', 'Liste des cd en cours/à venir et non-approuvé');
    define('_AM_CM_RENT_ADMIN_CDTOCOMEAPPROVED', 'Liste des cd en cours/à venir et approuvé');
    define('_AM_CM_RENT_ADMIN_TO', ' à ');
    define('_AM_CM_RENT_ADMIN_CLEAN', 'Nettoyer les empprunts');
    define('_AM_CM_RENT_ADMIN_PAGE', 'Page');
    define('_AM_CM_RENT_ADMIN_CDTEXT', ' CD ');
    define('_AM_CM_RENT_ADMIN_COPYTEXT', ' Copie ');
    define('_AM_CM_RENT_ADMIN_EMAILSENT', 'Email envoyé ?');
    define('_MA_CM_RENT_ADMIN_MAILSUBJECT', 'CD en retard');
    define('_MA_CM_RENT_ADMIN_MAILTEXT1', 'Bonjour ');
    define('_MA_CM_RENT_ADMIN_MAILTEXT2', 'Vous avez actuellement en votre possession le cd suivant : ');
    define('_MA_CM_RENT_ADMIN_MAILTEXT3', 'Malheureusement, votre réservation est en retard. Vous devez donc remettre votre cd. Si vous en avez encore de besoin veuillez contacter avec le responsable.');

    define('_MA_CM_RENT_CLEAN_TITLE', 'À partir de quelle date voulez-vous commencer à supprimer ?');
    define('_MA_CM_RENT_CLEAN_DAY', 'Jour :');
    define('_MA_CM_RENT_CLEAN_MONTH', 'Mois :');
    define('_MA_CM_RENT_CLEAN_YEAR', 'Année :');
    define('_MA_CM_RENT_CLEAN_GO', 'Go');
    define('_AM_CM_RENT_CLEAN_AREYOUSURE', 'Êtes-vous sûr de vouloir supprimer tous les emprunts archivés à partir de (incluant cette date) : ');
    define('_AM_CM_RENT_CLEAN_YES', 'Oui');
    define('_AM_CM_RENT_CLEAN_NO', 'Non');
    define('_AM_CM_RENT_CLEAN_CONFIRM', 'Confirmation');

    // section administration des permissions
    define('_AM_CM_RENT_ADMIN_ADMINPERM', 'Administration : Permissions');

    // Section DB
    define('_AM_CM_DB_UPDATED', 'La base de données a bien été mise à jour');
    define('_AM_CM_DB_NONCONFORMID', 'ID non-conforme');
    define('_AM_CM_DB_NOCATNAME', 'Vous devez spécifier un nom à la catégorie');
