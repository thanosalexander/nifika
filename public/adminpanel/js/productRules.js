/** Handles selection on product rules styling and ui */
$(document).ready(function(){
    
    var $productGroup = $('#product[data-isGroup="1"]');
    
    $productGroup.on('click', '.productRow:not(.disabled,.noChoice) .productLabel', function(){
        ProductRules.log('-----EVENT: CLICK -> .productRow:not(.disabled,.noChoice) .productLabel----');
        var $productRow = $(this).closest('.productRow');
        if( ProductRules.productIsSelected($productRow) ){ // deselect
            ProductRules.deselectProduct($productRow);
        }else{ //select
            ProductRules.selectProduct($productRow);
        }
        ProductRules.log('---------------------EVENT END---------------------------');
    });
    
    $productGroup.on('click', '.productRow:not(.noChoice) .moreQuantity', function(){
        ProductRules.log('--------EVENT: CLICK -> .productRow .moreQuantity-------');
        var $productRuleGroup = $(this).closest('.productRuleGroup'); //the rule group
        var $productRow = $(this).closest('.productRow');
        if( !ProductRules.isProductSelectionDone($productRuleGroup) ){
            var $count = ProductRules.quantityCountElement($productRow);
            var countInt = ProductRules.quantityCount($productRow);
            $count.text( countInt + 1 );
            Ingredients.ingredientsUIForProduct($productRow);
            ProductRules.productsChanged($productRuleGroup);
        }
        ProductRules.log('---------------------EVENT END---------------------------');
    });
    $productGroup.on('click', '.productRow:not(.noChoice) .lessQuantity', function(){
        ProductRules.log('--------EVENT: CLICK -> .productRow .lessQuantity-------');
        var $productRow = $(this).closest('.productRow');
        var $count = ProductRules.quantityCountElement($productRow);
        var countInt = ProductRules.quantityCount($productRow);
        if( countInt > 1 ){
            $count.text( countInt - 1 );
            var $productRuleGroup = $(this).closest('.productRuleGroup');
            Ingredients.ingredientsUIForProduct($productRow);
            ProductRules.productsChanged($productRuleGroup);
        } else if (countInt == 1){
            ProductRules.deselectProduct($productRow);
        }
        ProductRules.log('---------------------EVENT END---------------------------');
    });
    $productGroup.on('change', '.ingredient input', function(){
        ProductRules.log('-----------EVENT: CHANGE -> .ingredient input-----------');
        var $productRuleGroup = $(this).closest('.productRuleGroup');
        ProductRules.productsChanged($productRuleGroup);
        ProductRules.log('---------------------EVENT END---------------------------');
    });
    $productGroup.on('change', '.ingredient select', function(){
        ProductRules.log('-----------EVENT: CHANGE -> .ingredient select-----------');
        var $productRuleGroup = $(this).closest('.productRuleGroup');
        ProductRules.productsChanged($productRuleGroup);
        ProductRules.log('---------------------EVENT END---------------------------');
    });
    
    ProductRules.init();
});

var ProductRules = ProductRules || {};
(function(window, $, scope, undefined){
    
    scope.log = function($message){
        //console.log($message);
    }
    
    scope.init = function(){
        scope.log('ProductRules.init');
        $('.productRuleGroup').each(function(){
            scope.productsChanged($(this)); 
        });
    };
    
    scope.selectProduct = function($productRow){
        scope.log('ProductRules.selectProduct');
        var $productRuleGroup = $productRow.closest('.productRuleGroup'); //the rule group
        if( !scope.isProductSelectionDone($productRuleGroup) ){
            $productRow.addClass('selected');
            Ingredients.ingredientsUIForProduct($productRow);
            scope.productsChanged($productRuleGroup);
        }
    };
    scope.deselectProduct = function($productRow){
        scope.log('ProductRules.deselectProduct');
        var $productRuleGroup = $productRow.closest('.productRuleGroup'); //the rule group
        $productRow.removeClass('selected');
        scope.quantityCountElement($productRow).text(1);
        Ingredients.ingredientsUIForProduct($productRow);
        scope.productsChanged($productRuleGroup);
    };
    scope.quantityCountElement = function($productRow){
        scope.log('ProductRules.quantityCountElement');
        return $productRow.find('.count');
    };
    scope.quantityCount = function($productRow){
        scope.log('ProductRules.quantityCount');
        var $element = ProductRules.quantityCountElement($productRow);
        var res = 1;
        if($element.length > 0){
            res = parseInt($element.text());
        }
        return res;
    };
    scope.productIsSelected = function($productRow){
        scope.log('ProductRules.productIsSelected');
        return $productRow.hasClass('selected');
    };
    scope.buildRulesReport = function(){
        scope.log('ProductRules.buildRulesReport');
        scope.updateRulesReport('');
        var errorText = '';
        $('.productRuleGroup').each(function(){
            if( !ProductRules.isProductSelectionDone( $(this) ) ){
                errorText += '<br>' + $(this).find('.productRuleLabel .theLabel').text();
            }
        });
        if(errorText!=''){
            scope.updateRulesReport(Trans.get('labels.product.menuStillHaveTochoose', {text: errorText})+'<br><br>');
        }
    }
    scope.updateRulesReport = function($message){
        scope.log('ProductRules.setRulesReport');
        $('.rulesReport').html($message);
    }
    scope.productsChanged = function($productRuleGroup){
        scope.log('ProductRules.productsChanged');
        //enable - disable selection
        scope.checkIngredients($productRuleGroup);
        if( !scope.isProductSelectionDone($productRuleGroup) ){
            scope.productSelectionNotDone($productRuleGroup);
            scope.buildRulesReport();
        }else{
            scope.productSelectionDone($productRuleGroup);
            scope.updateRulesReport('');
        }
        scope.enableDisableQuantityChange($productRuleGroup);
        Products.setProductPrice();
    }
    scope.isAllSelectionsDone = function(){
        scope.log('ProductRules.selectionDoneCheckAll');
        var res = true;
        $('.productRuleGroup').each(function(){
            var $productRuleGroup = $(this);
            if( !ProductRules.isProductSelectionDone($productRuleGroup) || !ProductRules.isIngredientSelectionDone($productRuleGroup)){
                res = false;
                return false;
            }
        });
        return res;
    }
    scope.isProductSelectionDone = function ($productRuleGroup){
        scope.log('ProductRules.isProductSelectionDone');
        var res = false;
        if( scope.countSelected($productRuleGroup) == scope.ruleGroupQuantity($productRuleGroup)){
            res = true;
        }
        return res;
    }
    scope.isIngredientSelectionDone = function ($productRuleGroup){
        scope.log('ProductRules.isIngredientSelectionDone'); 
        return Ingredients.checkSelections($productRuleGroup, false);
    }
    scope.checkIngredients = function($productRuleGroup){
        scope.log('ProductRules.checkIngredients');
        return Ingredients.checkSelections($productRuleGroup);
    };
    scope.productSelectionDone = function($productRuleGroup){
        scope.log('ProductRules.productSelectionDone');
        $productRuleGroup.find('.productRow:not(.selected)').addClass('disabled'); //disable products
        $productRuleGroup.find('.productRow .moreQuantity').addClass('disabled'); //disable products quantity increament
        
        if(scope.isIngredientSelectionDone()){
            $productRuleGroup.addClass('selectionDone');
        } else {
           $productRuleGroup.removeClass('selectionDone');
        }
    }
    scope.productSelectionNotDone = function($productRuleGroup){
        scope.log('ProductRules.productSelectionNotDone');
        $productRuleGroup.find('.productRow').removeClass('disabled'); //enable products
        $productRuleGroup.find('.productRow .moreQuantity').removeClass('disabled'); //enable products quantity increament
        $productRuleGroup.removeClass('selectionDone');
    }
    scope.ruleGroupQuantity = function($productRuleGroup){
        scope.log('ProductRules.ruleGroupQuantity');
        return $productRuleGroup.attr('data-quantity')
    }
    scope.enableDisableQuantityChange = function($productRuleGroup){
        scope.log('ProductRules.enableDisableQuantityChange');
        $productRuleGroup.find('.productRow:not(.selected) .quantity').addClass('hidden');
        $productRuleGroup.find('.productRow.selected .quantity').removeClass('hidden');
    }
    /** Count currently selected products on rule.
    * @param {object} $productRuleGroup
    * @returns {int} */
    scope.countSelected = function($productRuleGroup){
       scope.log('ProductRules.countSelected');
       var count = 0;
       $productRuleGroup.find('.selected').each(function(){
           if( $(this).find('.count').length ==0 ){
               count++;
           }else{
               count += parseInt($(this).find('.count').text());
           }
       });
       return count;
    }
    
})(window, jQuery, ProductRules);