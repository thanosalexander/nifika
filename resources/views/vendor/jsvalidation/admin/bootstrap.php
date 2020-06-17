<script>
    $(document).ready(function(){
        
        $("<?php echo $validator['selector']; ?>").validate({
            errorElement: 'span',
            errorClass: 'help-block error-help-block',

            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length ||
                    element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                    error.insertAfter(element.parent());
                    // else just place the validation message immediatly after the input
                } else if (element.hasClass('selectionTypeShadow')) {
                    error.insertBefore(element.parent().children('.selectionTypeShadow').first());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error'); // add the Bootstrap error class to the control group
            },

            <?php if (isset($validator['ignore']) && is_string($validator['ignore'])): ?>

            ignore: "<?php echo $validator['ignore']; ?>",
            <?php endif; ?>

            /*
             // Uncomment this to mark as validated non required fields
             unhighlight: function(element) {
             $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
             },
             */
            success: function(element) {
                //element is the error element
                var $inputElement = $(document).find('[aria-describedby="'+element.prop('id')+'"]');
                if($inputElement.prop('aria-invalid') === false && $inputElement.hasClass('isDisabled')){ //there is not markups yet
                    //ignore success markup
                    return false;
                }
                
                if($(element).siblings('*[aria-invalid="true"]').length === 0){
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // remove the Boostrap error class from the control group
                }
            },

            focusInvalid: false, // do not focus the last invalid input
            <?php if (Config::get('jsvalidation.focus_on_error')): ?>
            invalidHandler: function(form, validator) {
                if (!validator.numberOfInvalids())
                    return;

                //calculate first invalid element's offset
                var fixedHeaderHeight = $(document).find('#headerMenu').height();
                if($(document).find('#actionButtonBarContainer').offset() != 'undefined'){
                    fixedHeaderHeight += $(document).find('#actionButtonBarContainer').height();
                }
                var $invalidElement = $(validator.errorList[0].element);
                if ($invalidElement.offset() != 'undefined') {
                    var firstInvalidElementOffset = $invalidElement.offset().top;
                    var elementId = $invalidElement.attr('id');
                    var $invalidElementLabel = $("label[for='" + elementId + "']");
                    if($invalidElement.hasClass('elementShadow')){
                        elementId = $invalidElement.attr('name').substring(1);
                        $invalidElementLabel = $("label[for='" + elementId + "']");
                    }
                    if($invalidElement.closest('.cmb-row').hasClass('cmb-type-multi-records')){
                        elementId = $invalidElement.attr('data-base-name');
                        $invalidElementLabel = $invalidElement.closest('.recordRow');
                    }
                    if ($invalidElementLabel.offset() != undefined) {
                        if ($invalidElementLabel.offset().top < firstInvalidElementOffset) {
                            firstInvalidElementOffset = $invalidElementLabel.offset().top;
                        }
                    }
                }

                $('html, body').animate({
                    scrollTop: firstInvalidElementOffset - fixedHeaderHeight
                }, <?php echo Config::get('jsvalidation.duration_animate') ?>);
                $(validator.errorList[0].element).focus();

            },
            <?php endif; ?>
        
            rules: <?php echo json_encode($validator['rules']); ?>
        })
    })
</script>
