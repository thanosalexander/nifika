
<script>
<?php
    /**
     * <h4>Manual use</h4>
     * var showActionBarButtons = function () {<br>
     *      //display action bar Buttons<br>
     *      if (ActionBar === undefined || typeof (ActionBar) !== 'object') {<br>
     *          return;<br>
     *      }<br>
     *      var $formSaveButton = $('#saveButton');<br>
     *      var $saveButton = ActionBar.newButton(<br>
     *              $formSaveButton.text(),<br>
     *              'bgDefault',<br>
     *              function () {<br>
     *                  $formSaveButton.trigger('click');<br>
     *              }<br>
     *      );<br>
     *      $formSaveButton.addClass('minimize'); //minimize form save button<br>
     *      ActionBar.addButtonToBar($saveButton); //add button to action bar<br>
     *  };<br>
     *  showActionBarButtons();<br>
     *  <br>
     * <h4>Automatic use<h4>
     * -------------<br>
     * &lt;button class="showAsActionBarButton" data-actionbar-order="2" data-actionbar-bgclass="bgDanger"&gt;Text&lt;/button&gt;
     */ ?>
    var ActionBar = {};
    $(document).ready(function () {
        ActionBar = {
            containerSelector: '#actionButtonBarContainer',
            barSelector: '#actionButtonBar',
            buttonSelector: '.actionButton',
            bdClasses: {
                default: 'bgDefault',
                success: 'bgSuccess',
                danger: 'bgDanger'
            },
            sampleButton: $('<div class="actionButton">Save</div>'),
            bar: function(){
                return $(this.barSelector);
            },
            /** bdClass values: [bgDefault, bgDanger, bgSuccess] */
            newButton: function(text, bgClass, clickEventCallback){
                text = text !== undefined ? text : 'Action';
                bgClass = bgClass !== undefined ? bgClass : this.bdClasses.default;
                if(clickEventCallback === undefined){
                    clickEventCallback = function(){console.log('click action bar button!');};
                }
                return this.sampleButton.clone()
                    .addClass(bgClass)
                    .text(text)
                    .on('click', clickEventCallback);
            },
            addButtonToBar: function($button){
                $button.appendTo(this.bar()).removeClass('hidden');
            },
            initActionButtons: function(){
                var actionBarButtons = [];
                $(document).find('.showAsActionBarButton').each(function(){
                    var $formButton = $(this);
                    var $actionBarButton = ActionBar.newButton(
                            $formButton.text(),
                            $formButton.attr('data-actionBar-bgclass'),
                            function () { $formButton.trigger('click'); }
                    );
                    $formButton.addClass('minimize'); //minimize form save button
                    var orderAttr = 'data-actionBar-order';
                    var formButtonOrder = parseInt($formButton.attr(orderAttr));
                    var formButtonOrder = isNaN(formButtonOrder) ? 0 : formButtonOrder;
                    actionBarButtons.push({order: formButtonOrder, button: $actionBarButton});
                });

                actionBarButtons.sort(function(a, b){return a.order - b.order;});
                for(var i = 0; i < actionBarButtons.length; i++){
                    ActionBar.addButtonToBar(actionBarButtons[i].button); //add button to action bar
                }
            }
        };
        ActionBar.initActionButtons();
    });
</script>