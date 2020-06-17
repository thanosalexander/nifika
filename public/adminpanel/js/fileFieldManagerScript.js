/**
 * it is used to handle input file 
 * @type FileFieldManager
 */
//var FileFieldManager = FileFieldManager || {};
function FileFieldManager(){
    var scope = {};
    /**
     * @property string rootSelector Selector that specifies the element in whitch all input files belong 
     * @property string recordRowSelector Selector that specifies the root element for each input file
     * @property string shadowSelector Selector that specifies the shadow element of input file that is used for validation purposes
     * @property string fileSelector Selector that specifies the input file 
     * @property string deletefileSelector Selector that specifies input element that it is sent if delete file is pressed
     * @property string deletefileNoticeSelector Selector of deleteFile notice elemen. it is shown when user press the deleteFile button
     * @property string previewChosenFile Selector that specifies the wrapper of chosen file preview
     * @property string previewChosenFileName Selector that specifies the wrapper of chosen file preview
     * @property string previewExistedFile Selector that specifies the wrapper of existed file preview
     * @property string buttonBrowseFile Selector that specifies the browse button
     * @property string buttonResetFile Selector that specifies the reset button
     * @property string buttonRemoveExistedFile Selector that specifies the remove existed file button
     * @property function validateElement function that is used to validate elements
     * @property function onChangeFile function that is used to validate elements
     * @type type
     */
    scope.defaluts = {
        rootSelector: '',
        recordRowSelector: '',
        shadowSelector: 'input.recordFieldFileShadow',
        fileSelector: 'input.recordFieldFile[type=file]',
        deletefileSelector: 'input.recordFieldDeleteFile',
        deletefileNoticeSelector: '.deleteFileNotice',
        previewChosenFileSelector: '.previewChosenFile',
        previewChosenFileNameSelector: '.previewChosenFileName',
        previewExistedFile: '.previewExistedFile',
        buttonBrowseFile: '.browseFileButton',
        buttonResetFile: '.resetFileButton',
        buttonRemoveExistedFile: '.removeExistedFileButton',
        validateElement: function(){},
        onChangeFile: function(){}
    };
    
    scope.opt = {};
    
    /* initializes options, validate function and appropriate listeners */
    scope.init = function(options){
      if (!$) {
        console.error('FileFieldManager.init() should be called on document ready!' );  
        return;}
      $.extend(scope.opt, scope.defaluts, options);
      if(typeof(scope.validateElement) !== 'function'){
          scope.validateElement = scope.defaluts.validateElement;
      }
      if(typeof(scope.onChangeFile) !== 'function'){
          scope.onChangeFile = scope.defaluts.onChangeFile;
      }
      scope.setListeners();
    };
    
    /* validate the given jquery element */
    scope.validateElement = function ($element) {
    };
    
    /* sync input file's shadow element and validate shadow */
    scope.syncFileElementShadow = function ($fileElement) {
        var $recordRow = $($fileElement.closest(scope.opt.recordRowSelector));
        var $fileShadowElement = $recordRow.find(scope.opt.shadowSelector);
        if ($fileElement.val() == '') {
            $fileShadowElement.val("");
        } else {
            $fileShadowElement.val("valid");
        }
        scope.validateElement($fileShadowElement);
    };
    
    /* resets file element to initial state and sync its shadow */
    scope.resetFileElement = function ($fileElement) {
        var $recordRow = $($fileElement.closest(scope.opt.recordRowSelector));
        var $fileShadowElement = $recordRow.find(scope.opt.shadowSelector);

        $fileElement.val('');
        scope.validateElement($fileElement);
        $fileShadowElement.val($fileShadowElement.attr('data-default-value'));
        scope.validateElement($fileShadowElement);
    };
    
    /* triggers input file's click event that is relevant with this button */
    scope.browseFile = function (buttonElement) {
        var $buttonElement = $(buttonElement);
        var $recordRow = $($buttonElement.closest(scope.opt.recordRowSelector));
        var $fileElement = $recordRow.find(scope.opt.fileSelector);
        $fileElement.trigger('click');
    };
    
    /* shows preview of selected file */
    scope.previewChosenFile = function (fileElement) {
        var $fileElement = $(fileElement);
        var $recordRow = $($fileElement.closest(scope.opt.recordRowSelector));
        var $previewChosenFileElement = $recordRow.find(scope.opt.previewChosenFileSelector);

        //if file input has file
        if (fileElement.files && fileElement.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                //show preview of chosen file
                setPreviewChosenFile($previewChosenFileElement, e.target.result, fileElement.files[0].name);
                $previewChosenFileElement.removeClass('hidden');
                //hide existed file
                $recordRow.find(scope.opt.previewExistedFile).addClass('hidden');
                //hide removeExistedFile button
                $recordRow.find(scope.opt.buttonRemoveExistedFile).addClass('hidden');
                //empty deleteFile Field
                $recordRow.find(scope.opt.deletefileSelector).val('').trigger('change');
                //hide deleteFile notice
                $recordRow.find(scope.opt.deletefileNoticeSelector).addClass('hidden');
                scope.validateElement($fileElement);
                scope.syncFileElementShadow($fileElement);
                scope.onChangeFile($fileElement);
            }

            reader.readAsDataURL(fileElement.files[0]);
        }
    };
    
    /* resets all elements to initial state */
    scope.resetFile = function (buttonElement) {
        var $buttonElement = $(buttonElement);
        var $recordRow = $($buttonElement.closest(scope.opt.recordRowSelector));
        var $previewChosenFileElement = $recordRow.find(scope.opt.previewChosenFileSelector);
        var $fileElement = $recordRow.find(scope.opt.fileSelector);

        //empty file input value
        scope.resetFileElement($fileElement);
        //empty chosen file preview
        $previewChosenFileElement.addClass('hidden');
        setPreviewChosenFile($previewChosenFileElement, '', '');

        //empty deleteFile Field
        $recordRow.find(scope.opt.deletefileSelector).val('').trigger('change');
        //hide deleteFile notice
        $recordRow.find(scope.opt.deletefileNoticeSelector).addClass('hidden');
        //show existed file preview
        $recordRow.find(scope.opt.previewExistedFile).removeClass('hidden');
        $recordRow.find(scope.opt.buttonRemoveExistedFile).removeClass('hidden');
        scope.onChangeFile($fileElement);
    };
    
    /* empty input file and enable deleteFile element */
    scope.removeExistedFile = function (buttonElement) {
        var $buttonElement = $(buttonElement);
        var $recordRow = $($buttonElement.closest(scope.opt.recordRowSelector));
        var $fileElement = $recordRow.find(scope.opt.fileSelector);
        $recordRow.find(scope.opt.deletefileSelector).val('1').trigger('change');
        //show deleteFile notice
        $recordRow.find(scope.opt.deletefileNoticeSelector).removeClass('hidden');
        //hide removeExistedFile button
        $recordRow.find(scope.opt.previewExistedFile).addClass('hidden');
        $recordRow.find(scope.opt.buttonRemoveExistedFile).addClass('hidden');
        scope.validateElement($fileElement);
        scope.syncFileElementShadow($buttonElement);
        scope.onChangeFile($fileElement);
    };
    
    /* setPreviewChosenFile */
    setPreviewChosenFile = function ($previewChosenFileElement, src, fileName) {
        if($previewChosenFileElement.hasClass('isFile')){
            $previewChosenFileElement.find('a').attr('href', src);
        } else {
            $previewChosenFileElement.find('img').attr('src', src);
        }
        var $recordRow = $($previewChosenFileElement.closest(scope.opt.recordRowSelector));
        $recordRow.find(scope.opt.previewChosenFileNameSelector).text(fileName);
    };
    
    /* sets appropriate listeners  */
    scope.setListeners = function(){
        //catch onclick browseFile button
        $(document).on('click', scope.opt.rootSelector+' '+scope.opt.buttonBrowseFile, function (event) {
            event.preventDefault();
            scope.browseFile(this);
        });

        //catch onclick ResetFile button
        $(document).on('click', scope.opt.rootSelector+' '+scope.opt.buttonResetFile, function (event) {
            event.preventDefault();
            scope.resetFile(this);

        });

        //catch onclick RemoveExistedFile button
        $(document).on('click', scope.opt.rootSelector+' '+scope.opt.buttonRemoveExistedFile, function (event) {
            event.preventDefault();
            scope.removeExistedFile(this);
        });

        //catch onchange file element to refresh its preview
        $(document).on('change', scope.opt.rootSelector+' '+scope.opt.fileSelector, function (event) {
            scope.previewChosenFile(this);
        });
    };
    
    return scope;
}