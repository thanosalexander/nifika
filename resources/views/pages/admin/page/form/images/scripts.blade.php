<script>

    $(document).ready(function () {
        var rootSelector = '#<?= $fieldWrapperId ?>'; //find root element for this field
        var maxRecords = <?= $maxRecords ?>;
        var recordWrapperSelector = '.recordsContainer';
        var recordRowSelector = '.recordRow';
        var switchButtonSelector = '.switchButton.imagesSwitchButtonYesNo input[type="checkbox"]';
        var recordRowSwitchButtonSelector = recordRowSelector + ':not(.dump-record) ' + switchButtonSelector;
        var $root = $(rootSelector); //find root element for this field
        var $myform = $($(document).find("#<?= $fieldWrapperId ?>").closest('form')[0]);

        var validateElement = function ($element) {
            if ($element.length > 0 && $myform.data('validator') !== undefined) {
                $element.valid();
            }
        };

        var addRecord = function () {
            var $recordWrapper = $root.find(recordWrapperSelector);//find row Container element
            if (maxRecords <= 0 || $recordWrapper.children(':not(.dump-record)').length < maxRecords) {
                var $dump = $root.find(recordRowSelector + '.dump-record').clone();//find dump-record element and clone it
                $dump.removeClass('dump-record').find('*:disabled').removeAttr('disabled');// remove dump-record class and enable inputs
                var rowId = $.now();
                $dump.attr('data-row-id', rowId);
                $dump.find('*[data-row-id]').attr('data-row-id', rowId); //generate unique row id to use it for deletion
                $dump.find('*[data-record-field]').each(function () {
                    var fieldName = $(this).attr('data-base-name') + '[' + rowId + ']' + $(this).attr('data-record-field') + '';
                    $(this).closest(".recordFieldWrapper").find('label').attr('for', fieldName);
                    $(this).attr('name', fieldName);// create field name
                    $(this).attr('id', fieldName);// create field id
                }); //generate unique row id to use it for deletion

                $dump.appendTo($recordWrapper);// append empty record to tbody
                initSwitchButtons(recordRowSelector + '[data-row-id="' + rowId + '"] ' + switchButtonSelector); //init switch button
            } else {
                alert('<?= trans($transBaseName.'.form.multiRecords.maxRecordsCountAre') ?>' + maxRecords + '!');
            }
        }

        var removeRecord = function ($this) {
            var rowId = $this.attr('data-row-id');
            var $record = $root.find(recordRowSelector + '[data-row-id=' + rowId + ']');
            if (confirm('<?= trans('labels.confirm.delete') ?>')) {
                $record.remove();
            }
        }

        /* Destroy bootstrapToggle buttons and create them again */
        var refreshSwitchButtons = function (selector) {
            selector = selector === undefined ? recordRowSwitchButtonSelector : selector;
            destroySwitchButtons(selector);
            initSwitchButtons(selector);
        };

        /* Create bootstrapToggle buttons */
        var initSwitchButtons = function (selector) {
            selector = selector === undefined ? recordRowSwitchButtonSelector : selector;
            $(document).find(rootSelector + ' ' + selector).bootstrapToggle({
                on: '<?= trans($transBaseName.'.form.switchButton.on.yes') ?>',
                off: '<?= trans($transBaseName.'.form.switchButton.off.no') ?>',
                onstyle: 'success',
                offstyle: 'danger'
            });
        };

        /* Destroy bootstrapToggle buttons */
        var destroySwitchButtons = function (selector) {
            selector = selector === undefined ? recordRowSwitchButtonSelector : selector;
            $(document).find(rootSelector + ' ' + selector).bootstrapToggle('destroy');
        };

        //catch onclick add-record button
        $(document).on('click', rootSelector + ' .add-record', function (event) {
            event.preventDefault();
            try {
                addRecord();
            } catch (e) {
                console.error(e);
            }
        });
        //catch onclick remove-record button
        $(document).on('click', rootSelector + ' ' + recordRowSelector + ':not(.dump-record) .remove-record', function (event) {
            event.preventDefault();
            removeRecord($(this));
        });
        //catch onclick remove-record button
        $(document).on('click', rootSelector + ' button', function (event) {
            event.preventDefault();
        });

        $(recordWrapperSelector).sortable({
            handle: 'i.glyphicon-move',
        });

        /* Set bootstrapToggle buttons */
        destroySwitchButtons(switchButtonSelector);
        initSwitchButtons();
        
        var articleImage = new FileFieldManager();
        articleImage.init({
                rootSelector: rootSelector,
                recordRowSelector: recordRowSelector,
                validateElement: validateElement
        });
    });
</script>