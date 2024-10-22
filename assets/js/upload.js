jQuery(document).ready(function($) {
    $('#cm_gamerecipe_img_button').on('click', function(e) {
        e.preventDefault();
        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Välj eller ladda upp bild',
            button: {
                text: 'Använd denna bild',
            },
            multiple: false,
            library: {
                type: ['image'] // Begränsar till bildfiler
            }
        });
        file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            console.log('Fil uppladdad', attachment.url);  // Add this line to log the selected image URL.
            $('#cm_gamerecipe_img').val(attachment.url);
        });
        file_frame.open();
    });
});
