$(document).ready(function () {

    $('[data-mask]').inputmask()

    //SUPPRIMER UEN IMAGE DE PRODUIT
    $(document).on('click', '.btn-image-delete', function (e) {
        e.preventDefault()
        let href = $(this).attr('href')
        let token = $(this).data('token')
        let div = $(this).closest('div')
        Swal.fire({
            title: 'Etes vous sûr?',
            text: "Voulez vous supprimr cettre image ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Fermer',
            confirmButtonText: 'Oui, je confirme!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: href,
                    method: 'DELETE',
                    type: 'json',
                    data: {
                        _token: token
                    },
                    beforeSend: function () {
                        $('.js-loader-text').text("Suppression de l'image en cour ...")
                        $('.js-loader').css('display', 'flex')
                    },
                    success: function (data) {
                        $('.js-loader').css('display', 'none')
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Supression réussie !',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            div.remove()
                        } else {
                            alert('Une erreur est servenue')
                        }
                    },
                    error: function () {
                        $('.js-loader').css('display', 'none')
                        alert('Une erreur est servenue')
                    }
                })
            }
        })
    })

    //copier un produit
    $(document).on('click', '.btn-copy', function (e) {
        e.preventDefault()
        let href = $(this).attr('href')
        Swal.fire({
            title: 'Etes vous sur ?',
            text: "Vous allez copier ce produit !",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, Copier',
            cancelButtonText: 'Fermer'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href
            }
        })
    })

    // ENVOIE UN LIEN DE MODIFICATION D'EMAIL
    $(document).on('click', '#btn-edit-email', function () {
        Swal.fire({
            title: 'Es-tu sûr ?',
            text: "Vous enverrez un e-mail de changement d'e-mail !",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Fermer',
            confirmButtonText: 'Oui, je confirme!'
        }).then((result) => {
            if (result.isConfirmed) { // ajax
                $.ajax({
                    url: "/send/edit-email",
                    method: "POST",
                    dataType: 'json',
                    data: {},
                    beforeSend: function () {
                        $('.loader-edit-email').css('display', 'initial')
                        $('#btn-edit-email').css('display', 'none')
                    },
                    success: function (data) {
                        if (data == 'success') { } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Le mail de modification a été envoyé',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        $('.loader-edit-email').css('display', 'none')
                        $('#btn-edit-email').css('display', 'initial')
                        $('.alert-edit-email').css('display', 'initial')
                        
                        var bouton = $('#btn-edit-email');
                        bouton.prop('disabled', true); // désactiver le bouton
                        bouton.text('Veuillez patienter 2 minutes...'); // changer le texte du bouton
                        setTimeout(function(){
                            updateCounter()
                            bouton.prop('disabled', false); // réactiver le bouton
                            bouton.text('Cliquez ici'); // restaurer le texte du bouton
                        }, 120000); // 2 minutes en millisecondes (2 minutes = 120 000 millisecondes)
                        
                    }
                })
                // ./ajax end
            }
        })
    })

    function updateCounter() {
        $('#counter').text(120);
        count--; // Décrémenter le compteur
        if (count >= 0) {
            setTimeout(updateCounter, 1000); // Appel récursif de la fonction toutes les 1 seconde
        }
    }

    //BOUTON DE DECONNECTION
    $(document).on('click', '#btn-logout', function (e) {
        e.preventDefault()
        Swal.fire({
            title: 'Es-tu sûr ?',
            text: "Vous êtes sur le point de vous déconnecter!  ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Fermer',
            confirmButtonText: "Oui, je confirme!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Déconnexion réussie !',
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    window.location.href = "/logout"
                })
            }
        })
    })
})