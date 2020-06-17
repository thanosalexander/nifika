/** Handles selection of ingredients */

var Ingredients = Ingredients || {};
(function(window, $, scope, undefined){
    
    scope.log = function($message){
        //console.log($message);
    }
    /** Gather ingredient data for simple products. */
    scope.gather = function(){
        scope.log('Ingredients.gather');
        var ingredients = [];
        //gather simple product ingredients
        $('#productPage #product[data-isGroup="0"] .ingredientsRow:not(.hidden) .ingredient:not(.hidden)').each( function(){
            var $ingredient = $(this);
            if(Ingredients.isIngredientCountable($ingredient)){
                var ingredient = readIngredient($ingredient);
                if(ingredient.selectedChilds.length > 0){
                    ingredients.push( ingredient );
                }
            }
        });
        return ingredients;
    };
    /** Gather ingredient data for group products.
     * @param $ $productRow
     * @param int index Used to choose ingredients row.
     * @returns array */
    scope.gatherForSubProduct = function($productRow, index){
        scope.log('Ingredients.gatherForSubProduct');
        var ingredients = [];
        $productRow.find(".ingredientsRow[data-linked-ingredient-base-path='']:not(.template)").eq(index).find('.ingredient').each( function(){
            var $ingredient = $(this);
            if(Ingredients.isIngredientCountable($ingredient)){
                var ingredient = readIngredient($ingredient);
                if(ingredient.selectedChilds.length > 0){
                    ingredients.push( ingredient );
                }
            }
        });
        return ingredients;
    };
    readIngredient = function($ingredient){
        scope.log('Ingredients.readIngredient');
        var ingredient = {};
        ingredient.id = $ingredient.attr('data-id');
        ingredient.selectedChilds = [];
        if( $ingredient.find('select.form-control').length>0 ){
            $ingredient.find('select').each(function(){
                //check that found selected option is first-degree descendant of ingredient
                if($(this).closest('.ingredient').attr('data-linked-ingredient-path')  === $ingredient.attr('data-linked-ingredient-path')){
                    ingredient.selectedChilds.push($(this).val());
                }
            });
        }else if( $ingredient.find('.checkbox').length>0 ){
            $ingredient.find('.checkbox [type="checkbox"]:checked').each(function(){
                ingredient.selectedChilds.push( $(this).val() );
            });
        }else{
            scope.log('none');
        }
        return ingredient;
    }
    scope.isIngredientCountable = function($ingredient){
        //scope.log('Ingredients.isIngredientCountable');
        if($ingredient.hasClass('hidden') 
                || $ingredient.parents('.ingredientsRow.hidden').length > 0 
                || $ingredient.parents('.ingredientsRow.template').length > 0){
            return false;
        } else {
            return true;
        }
    }
    // return total price of selected ingredients
    scope.totalPrice = function($product){
        scope.log('Ingredients.totalPrice');
        var price = 0;
        $product.find('.ingredientsRow:not(.hidden, .template) .ingredient:not(.hidden)').each(function(){
            price += Ingredients.calculatePrice($(this));
        });
        return price;
    }
    scope.calculatePrice = function($ingredient){
        scope.log('Ingredients.calculatePrice');
        var price = 0;
        if(scope.isIngredientCountable($ingredient)) {
            if( $ingredient.find('select.form-control').length>0 ){
                $ingredient.find(':selected').each(function(){
                    //check that found selected option is first-degree descendant of ingredient
                    if($(this).closest('.ingredient').attr('data-linked-ingredient-path')  === $ingredient.attr('data-linked-ingredient-path')){
                        price += parseFloat( $(this).attr('data-price') );
                    }
                });
            }else if( $ingredient.find('.checkbox').length>0 ){
                $ingredient.find('.checkbox [type="checkbox"]:checked').each(function(){
                    price +=  parseFloat( $(this).attr('data-price') );
                });
            }
        }
        return price;
    }
    //handle selection messages , pass $parent to limit elements
    scope.checkSelections = function($parent, buildReport){
        buildReport = buildReport !== undefined ? buildReport: true;
        scope.log('Ingredients.checkSelections');
        var valid = true;
        if($parent == null){
            $parent = $('#product');
        }
        $parent.find('.ingredientsRow:not(.template) .ingredient[data-checkIngredients="1"]').each(function(){
            var $ingredient = $(this);
            if(Ingredients.isIngredientCountable($ingredient) && ! Ingredients.checkSelection( $ingredient ) ){
                valid = false;
            }
        });
        if(buildReport){
            scope.buildIngredientsReport();
        }
        return valid;
    };
    //check selection for ingredient
    scope.checkSelection = function($ingredient){
        scope.log('Ingredients.checkSelection');
        var valid = true;
        var countMin = $ingredient.attr('data-countMin');
        var countMax = $ingredient.attr('data-countMax');
        var selectedCount = $ingredient.find('input:checked').length;
        if( selectedCount < countMin || countMax < selectedCount ){
            $ingredient.find('.subtitle').addClass('warning');
            valid = false;
        }else{
            $ingredient.find('.subtitle').removeClass('warning');
        }
        return valid;
    };
    scope.buildIngredientsReport = function(){
        scope.log('Ingredients.buildIngredientsReport');
        if( $('#product[data-isGroup="0"]').length === 1 ){
            $('.ingredientsReport').html('');
            var errorText = '';
            $('.ingredientsRow:not(.template) .ingredient[data-checkIngredients="1"]').each(function(){
                var $ingredient = $(this);
                if(Ingredients.isIngredientCountable($ingredient) && $ingredient.find('.subtitle.warning').length === 1 ){
                    errorText += '<br>' + $ingredient.find('label .theLabel').text() 
                                        + ': '
                                        + $ingredient.find('label .subtitle').text();
                }
            });
            if(errorText!=''){
                $('.ingredientsReport').html(Trans.get('labels.product.ingredient.pleasefixYourChoices', {text: errorText})+'<br><br>');
            }
        }
    }
    //display ingredients selection for product in rule
    scope.ingredientsUIForProduct = function($productRow){
        scope.log('Ingredients.ingredientsUIForProduct');
        var numberOfIngredientsRows = Ingredients.ingredientsRowsCount($productRow);
        var quantityCount = 0;
        if( ProductRules.productIsSelected($productRow) ){
            quantityCount = ProductRules.quantityCount($productRow);
        }
        while( numberOfIngredientsRows < quantityCount){//for addition
            Ingredients.addIngredientsForProduct($productRow);
            numberOfIngredientsRows++;
        }
        while( numberOfIngredientsRows > quantityCount){//for removal
            Ingredients.removeIngredientsForProduct($productRow);
            numberOfIngredientsRows--;
        }
    }
    scope.addIngredientsForProduct = function($productRow){
        scope.log('Ingredients.addIngredientsForProduct');
        var $newIngredients = $productRow.find('.ingredientsRow.template').clone();
        $newIngredients.removeClass('template');
        $productRow.find('.ingredientsRow:not(.linkedIngredientsRow)').last().after($newIngredients);
    }
    /** Remove ingredients selection for product in rule. */
    scope.removeIngredientsForProduct = function($productRow){
        scope.log('Ingredients.removeIngredientsForProduct');
        $productRow.find('.ingredientsRow:not(.linkedIngredientsRow, .template)').last().remove();
    }
    scope.ingredientsRowsCount = function($productRow){
        scope.log('Ingredients.ingredientsRowsCount');
        return $productRow.find(".ingredientsRow:not(.linkedIngredientsRow, .template)").length;
    }
    /** Compare 2 ingredients arrays. */
    scope.compare = function(ingredients1, ingredients2){
        scope.log('Ingredients.compare');
        return JSON.stringify( ingredients1 ) == JSON.stringify( ingredients2 );
    }
    
})(window, jQuery, Ingredients);

var IngredientsUI = IngredientsUI || {};
( function( window, $, scope, undefined ){
    scope.log = function($message){
        //console.log($message);
    }
    //show ingredients tree in console
    scope.consoleProductIngredientsTree = function($product){
        scope.log('IngredientsUI.consoleProductIngredientsTree');
        // display
        $product.find('*[data-linked-ingredient-path]').each(function(){
            var level = $(this).parents('.ingredientsRow').length === $(this).parents('.ingredient').length ? '|-----': '';
            for(var i = 1; i < $(this).parents('.ingredientsRow').length; i++){
                level += '|-----';
            }
            IngredientsUI.log(level + $(this).attr('data-linked-ingredient-path') 
                    + ' ------>' + $(this).prop('tagName')
                    + ($(this).prop('selected') === true ? ' selected' : ''));
        });
        IngredientsUI.log('--------------------');
        IngredientsUI.log('--------------------');
    };
    /** Shows rest tree of selected ingredientChild */
    scope.showIngredientChildLinkedIngredients = function($ingredientChild){
        scope.log('IngredientsUI.showIngredientChildLinkedIngredients');
        //scope.consoleProductIngredientsTree($('#product'));

        //keep the elements that they should be shown
        var showingIngredients = [];
        
        //search for linked ingredients of given selected option 
        var showSelectedTree = function($select){
            //find seletcted option element
            var $selectedOption = $select.find(":selected");
            //find ingredient treepath of seletcted option
            var elementTreePath = $selectedOption.attr('data-linked-ingredient-path');

            //find descendant treepaths of siblings options
            var notLinkedIngredients = $select.closest('.ingredient')
                    .find(".ingredient:not([data-linked-ingredient-path^='"+elementTreePath+"'])");
            //hide descendant elements of siblings options
            notLinkedIngredients.each(function(){
                $(this).closest('.ingredientsRow').addClass('hidden');
            });
            
            //find linked ingredientsRow of selected option
            var linkedIngredientsRow = $select.closest('.ingredient')
                    .find(".ingredientsRow[data-linked-ingredient-base-path='"+elementTreePath+"']");
            //add linked ingredientsRow of selected option to showing elements
            showingIngredients.push(linkedIngredientsRow);
            
            //parse all descendant ingredients recursively to extract that they should be shown
            linkedIngredientsRow.find(".ingredient").each(function(){
                var $ingredient = $(this);
                var parentIngredientsRow = $(this).closest('.ingredientsRow');
                //check if ingredient is first-degree descendant of linkedIngredientsRow
                if(parentIngredientsRow.attr('data-linked-ingredient-base-path') === linkedIngredientsRow.attr('data-linked-ingredient-base-path')){
                    //if ingredient is linkable
                    if($ingredient.attr('data-linkable-ingredient') === '1'){
                        //search if ingredients choices has selected option
                        $ingredient.find(':selected').each(function(){
                            //check that found selected option is first-degree descendant of ingredient
                            if($(this).closest('.ingredient').attr('data-linked-ingredient-path')  === $ingredient.attr('data-linked-ingredient-path')){
                                //search for linked ingredients of this selected option 
                                showSelectedTree($(this).closest('select'));
                            }
                        });
                    } else {
                        //ingredient is not linkable then adds to showing elements
                        showingIngredients.push(parentIngredientsRow);
                    }
                }
            });
        };
        showSelectedTree($ingredientChild);
        
        //display appopriate elements
        for(var i = 0; i < showingIngredients.length; i++){
            showingIngredients[i].removeClass('hidden');
        }
    }
    
})(window, jQuery, IngredientsUI);