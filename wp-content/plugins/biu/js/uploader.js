jQuery(document).ready( function($){

    var mediaUploader;

    $('#upload-button').on('click',function(e) {
        e.preventDefault();
        if( mediaUploader ){
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose an Image',
            button: { text: 'Choose Image'},
            multiple: false
        });

        mediaUploader.on('select', function(){
            attachment = mediaUploader.state().get('selection').first().toJSON();
            if(attachment.id){
                console.log(attachment);
                var x = document.createElement("IMG");
                x.src=attachment.url;
                x.style.width='30%';
                $('#cat_meta_img_id').val(attachment.id);
                $('#cat_meta_img_url').val(attachment.url);
                $('#cat_meta_img_preview').html(x);
                $('#cat_meta_img_preview').show();
            }
        });

        mediaUploader.open();
    });    



});