const demoBaseConfig = {
  selector: 'textarea#classic',
  width: "100%",
  height: 1000,
  resize: false,
  autosave_ask_before_unload: false,
  plugins: [
    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
    'insertdatetime', 'media', 'table', 'help', 'wordcount'
  ],
  toolbar: 'undo redo | blocks | ' +
  'bold italic backcolor | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | ' +
  'removeformat | help',
  media_live_embeds: true, // Permet l'insertion de vidéos via des URLs
  image_title: true, // Permet d'ajouter des titres aux images
  automatic_uploads: false, // Pas besoin d'upload automatique
  file_picker_callback: function(callback, value, meta) {
    if (meta.filetype === 'image') {
      const url = prompt('Insérer une URL d\'image:');
      callback(url);
    }
  },
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
  image_class_list: [
    { title: 'Standard Image', value: 'standard-img' }
  ]
};

tinymce.init(demoBaseConfig);
