$(document).ready(function () {

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
            title: 'Are you sure ?',
            text: "You will send an email change email !",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Close',
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
                    }
                })
                // ./ajax end
            }
        })
    })

    //BOUTON DE DECONNECTION
    $(document).on('click', '#btn-logout', function (e) {
        e.preventDefault()
        Swal.fire({
            title: 'Are you sure',
            text: "You are about to log out!  ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Close',
            confirmButtonText: "Yes, I'm confirme!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Successful disconnection !',
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    window.location.href = "/logout"
                })
            }
        })
    })
})