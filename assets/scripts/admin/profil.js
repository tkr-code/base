$(function(){
    console.log('page profil');
    // open modal edit avatar
    $(document).on('click', '#btn-modal-edit-avatar', function () {
        $('#modal-default').modal('show')
        let body = ''
        $('#modal-default .modal-title').html('Modifier votre photo de profil')
        $('#modal-default .modal-body').html(body)
    })

    let cropper;
    var preview = document.getElementById('avatar')
    var file_input = document.getElementById('profile_avatar')
    $(document).on('click','#avatar',function(){
        let file = file_input.files[0]
        let reader = new FileReader()
        console.log(fil)
    })
    window.previewImageFile = function () {
        let file = file_input.files[0]
        let reader = new FileReader()

        reader.addEventListener('load', function (event) {
            preview.src = reader.result
        }, false)

        if (file) {
            reader.readAsDataURL(file)
        }
    }

    preview.addEventListener('load', function () {
        cropper = new Cropper(preview, {
            aspectRatio: 1 / 1,
            crop: function (event) {
                // console.log(event)
            }
        })
    })

    let form = document.getElementById('profil_form')

    form.addEventListener('submit', function (event) {
        event.preventDefault()
        cropper.getCroppedCanvas({
            maxHeight: 1000,
            maxwidth: 1000
        }).toBlob(function (blob) {
            ajaxWithAxios(blob)
        })
    })

    function ajaxWithAxios(blob) {
        let url = Routing.generate('profile_image');
        let data = new FormData(form)
        data.append('file', blob)
        axios({
            method: 'post',
            url: url,
            data: data,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then((response) => {
                loadProfilImg()
            })
            .catch((error) => {
                console.log(error);
            })
    }

    function loadProfilImg() {
        $.ajax({
            url: Routing.generate('get_profile_image'),
            method: 'POST',
            data: {
                profile_img: true,
            },
            beforeSend: function () {
                $('.js-loader-text').text('Modification en cour ...')
                $('.js-loader').css('display', 'flex')
            },
            success: function (response) {
                if (response.reponse) {
                    $('#js-load-profile-user-img').html(response.content)
                    // $('.cropper-container').remove()
                    // $('#avatar').attr('src','').removeClass('cropper-hidden')
                }
                $('.js-loader').css('display', 'none')
            }
        })
    }
})