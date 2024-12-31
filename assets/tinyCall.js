tinymce.init({
    // selector: 'textarea.tinymce',
    // selector: 'textarea#lessons_text',
    selector: 'textarea#articles_text, textarea#lessons_text',
    plugins: 'lists link image media table code formatselect',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image media | code',
    menubar: true,
    // Formats disponibles dans le menu déroulant
    block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3; Header 4=h4; Header 5=h5; Header 6=h6',
    // Insertion d'images/vidéos via URL
    // Active l'intégration d'image par URL uniquement
    images_upload_handler: function (blobInfo, success, failure) { // Interception de l'upload, comme on ne veut pas uploader on passe
    failure('L\'upload est désactivé');
    },
    // Autorise la sélection d'images via URL
    file_picker_callback: function (callback, value, meta) {
    if (meta.filetype === 'image' || meta.filetype === 'media') {
    const url = prompt('Entrez l\'URL de l\'image ou de la vidéo:');
    if (url) {
    callback(url);
    }
    }
    }
    });