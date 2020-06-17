$(document).ready(function () {

    //load ckeditor
    $(document).find('textarea.hasEditor').each(function () {
        CKEDITOR.replace(this, {
//            extraPlugins: 'filebrowser',
            filebrowserImageBrowseUrl: '/_media?type=Images',
//            filebrowserImageUploadUrl: '/_media/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/_media?type=Files',
//            filebrowserUploadUrl: '/_media/upload?type=Files&_token=',
            height: 250,
            entities_greek : false,
            language: $(document).find('html').attr('lang'),
            toolbarGroups: [
                {name: 'document', groups: ['mode', 'document', 'doctools']},
                {name: 'clipboard', groups: [ 'clipboard', 'undo' ]},
                {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing' ] },
                { name: 'forms', groups: [ 'forms' ] },
                '/',
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                {name: 'links', groups: [ 'links' ] },
                { name: 'insert', groups: [ 'insert' ] },
                '/',
                { name: 'styles', groups: [ 'styles' ] },
                { name: 'colors', groups: [ 'colors' ] },
                { name: 'tools', groups: [ 'tools' ] },
                { name: 'others', groups: [ 'others' ] },
                { name: 'about', groups: [ 'about' ] }
            ],
            removeButtons: 'HiddenField,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,Scayt,Undo,Redo,Preview,Print,NewPage,Save,Templates,Find,Flash,Smiley,Iframe,Maximize',
//            removeButtons: 'HiddenField,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,Scayt,Undo,Redo,Preview,Print,NewPage,Save,Source,Templates,Find,Flash,Smiley,Iframe,Maximize',
        });
    });
});