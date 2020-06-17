'use strict';

$(document).ready(function(){
    
    Cart.loadFromCookie();

    $('body').on('click', '.addToCart', function(){
        Cart.log('--------EVENT: CLICK -> .addToCart-------');
        var $addToCart = $(this);
        CartUI.$addToCartButton = $addToCart;
        var id = $addToCart.attr('data-productId');
        var isGroup = $addToCart.attr('data-isGroup');
        var ingredientsCount = $addToCart.attr('data-ingredients');
        if( isGroup==1 || ingredientsCount > 0 ){ //if isGroup or ingredientsCount
            if( $('#categoryPage').length > 0 ){ //go to choose configuration
                window.location = BASE_ULR+'/product/'+id;
            }else if( $('#productPage').length > 0){ //on product page
                if( isGroup==1 ){ //add group product
                    Cart.addGroupProduct();
                }else if( ingredientsCount > 0){// add product with ingredients
                    Cart.addProductWithIngredients(id);
                }
            }
        }else{//not group , no ingredients
            Cart.addProduct(id);
        }
        Cart.log('---------------------EVENT END---------------------------');
    });
    $('body').on('click', '.cartShow .removeProduct', function(){
        Cart.log('--------EVENT: CLICK -> .cartShow .removeProduct-------');
        var $tr = $(this).closest('tr.productRow');
        var key = $tr.attr('data-productKey');
        $tr.remove();
        Cart.removeProduct(key);
        Cart.log('---------------------EVENT END---------------------------');
    });
    $('body').on('click', '.cartShow .moreQuantity', function(){
        Cart.log('--------EVENT: CLICK -> .cartShow .moreQuantity-------');
        var $tr = $(this).closest('tr.productRow');
        var key = $tr.attr('data-productKey');
        var quantity = Cart.changeQuantity(key,'+');
        CartUI.updateProductPriceAndQuantity($tr, quantity);
        Cart.log('---------------------EVENT END---------------------------');
    });
    $('body').on('click', '.cartShow .lessQuantity', function(){
        Cart.log('--------EVENT: CLICK -> .cartShow .lessQuantity-------');
        var $tr = $(this).closest('tr.productRow');
        var key = $tr.attr('data-productKey');
        var quantity = Cart.changeQuantity(key,'-'); 
        CartUI.updateProductPriceAndQuantity($tr, quantity);
        Cart.log('---------------------EVENT END---------------------------');
    });
    
    CartUI.init();
    
    $(window).scroll( function(){
        CartUI.placeCartCounter(); 
    });
    $(window).resize( function(){
        CartUI.cartLeft = $(window).width() - ( $('#defaultTopRow .cartCounter').width() + 120 );
        CartUI.placeCartCounter();
    });
    
    //live price calculation for ingredients
    Products.setProductPrice();
    $('#product').on('change', '.ingredient input',function(){
        Products.log('--------EVENT: CHANGE -> .ingredient input-------');
        Products.setProductPrice();
        Products.log('---------------------EVENT END---------------------------');
    });
    $('#product').on('change', '.ingredient select',function(){
        Products.log('--------EVENT: CHANGE -> .ingredient select-------');
        IngredientsUI.showIngredientChildLinkedIngredients($(this));
        Products.setProductPrice();
        Ingredients.checkSelections();
        Products.log('---------------------EVENT END---------------------------');
    });
    $('#product[data-isGroup="0"]').on('change', '.ingredient input', function(){
        Products.log('--------EVENT: CHANGE -> #product[data-isGroup="0"] .ingredient input-------');
        Ingredients.checkSelections();
        Products.log('---------------------EVENT END---------------------------');
    });
});

/** Cart actions */
var Cart = Cart || {};
(function(window, $, scope, undefined){
    
    var _products = [];
    
    scope.log = function($message){
        //console.log($message);
    }
    scope.addProduct = function(id){
        scope.log('Cart.addProduct');
        var newProduct = Products.getForAddition(id);
        scope.addProductFromProduct(newProduct);
    };
    scope.addProductWithIngredients = function(id){
        scope.log('Cart.addProductWithIngredients');
        if( Ingredients.checkSelections() ){ 
            scope.addProduct(id);
        }
    };
    scope.addGroupProduct = function(){
        scope.log('Cart.addGroupProduct');
        if( ProductRules.isAllSelectionsDone() ){//all selected
            if( Ingredients.checkSelections() ){ 
                var newProduct = Products.createGroupProductFromView();
                scope.addProductFromProduct(newProduct);
            }else{

            }
        }else{ //
            ProductRules.buildRulesReport();
        }
    };
    scope.addProductFromProduct = function(newProduct){
        scope.log('Cart.addProductFromProduct');
        scope.log(newProduct);
        var pIndexes = findIndexesById(newProduct.id);
        var sameIndex = -1;
        $.each( pIndexes, function( k, pIndex ){
            if( sameIndex===-1 && Products.compare(_products[pIndex], newProduct) ){ //already in cart
                sameIndex = pIndex;
            }
        });
        if(sameIndex!==-1){ //same found
            scope.log('EXISTS IN CART!');
            scope.changeQuantity( _products[sameIndex].key, '+' );
        }else{//same not found
            scope.log('NOT EXISTS IN CART!');
            _products.push(newProduct);
        }
        productAdded();
    }
    scope.removeProduct = function(key){
        scope.log('Cart.removeProduct');
        var pIndex = findIndexByKey(key);
        if( pIndex!==-1 ){
            _products.splice( pIndex, 1);
            cartChanged();
        }
    };
    /** Action is '+' or '-' .*/
    scope.changeQuantity = function(key, action){
        scope.log('Cart.changeQuantity');
        var res = 0;
        var pIndex = findIndexByKey(key);
        if( pIndex!==-1 ){ //product exists
            if(action=='+'){
                res = _products[pIndex].moreQuantity();
            }else{
                res = _products[pIndex].lessQuantity();
            }
        }
        cartChanged();
        return res;
    }
    scope.pdebug = function(){
        scope.log('Cart.pdebug');
        scope.log(_products);
    };
    scope.loadFromCookie = function(){
        scope.log('Cart.loadFromCookie');
        _products = [];
        var cookieProducts = Cookies.get('cartProducts');
        try{
            var cookieProductsArray = JSON.parse(cookieProducts);
            if(Array.isArray(cookieProductsArray)){
                for(var i=0; i < cookieProductsArray.length; i++){
                    _products.push( Products.createFromCookie(cookieProductsArray[i]) );
                }
            }
        }catch(e){}
        cartChanged();
    };
    scope.countProducts = function(){
        scope.log('Cart.countProducts');
        var count = 0;
        for(var i=0; i < _products.length; i++){
            count = count + _products[i].quantity;
        }
        return count;
    };
    scope.calculatePrice = function(){
        scope.log('Cart.calculatePrice');
        if( $('.cartFinalAmountLive').length ){
            $.get(BASE_ULR+'/calculatePrice', function(data){
                try{
                    CartUI.updateCartPriceCalculations(data.results);
                    CartUI.handleCartTotalLimitation(data.hasSufficientTotalToOrder);
                }catch(e){
                    scope.log(e);
                }
            });
        }
    }
    /* Things to do when cart changes. */
    function cartChanged(){
        scope.log('Cart.cartChanged');
        setCookie();
        CartUI.updateCounters();
        scope.calculatePrice();
        CartUI.checkIfEmpty();
        Cart.pdebug();
    };
    /* Runs when cart changes by product addition. */
    function productAdded(){
        scope.log('Cart.productAdded');
        cartChanged();
        CartUI.animateCartCounter();
        CartUI.animateAddToCart();
    }
    function setCookie(){
        scope.log('Cart.setCookie');
        Cookies.set('cartProducts', _products, {expires: 7, path:'/'});
    };
    function findIndexByKey(key){
        scope.log('Cart.findIndexByKey');
        var res = -1;
        for (var i = 0; i < _products.length; i++) {
            if (_products[i].key == key) {
                res = i;
            }
        }
        return res;
    }
    function findIndexById(id) {
        scope.log('Cart.findIndexById');
        var res = -1;
        for (var i = 0; i < _products.length; i++) {
            if (_products[i].id == id) {
                res = i;
            }
        }
        return res;
    }
    function findIndexesById(id) {
        scope.log('Cart.findIndexesById');
        var res = [];
        for (var i = 0; i < _products.length; i++) {
            if (_products[i].id == id) {
                res.push(i);
            }
        }
        return res;
    }
    
})(window, jQuery, Cart);

var CartUI = CartUI || {};
( function( window, $, scope, undefined ){
    
    scope.originalBackgroundColor;
    scope.originalColor;
    scope.cartTop;
    scope.cartLeft;
    
    scope.log = function($message){
        //console.log($message);
    }
    scope.$addToCartButton = null;//hold last clicked addToCart element
    
    scope.init = function(){
        scope.log('CartUI.init');
        scope.originalBackgroundColor =  $('#defaultTopRow .cartCounter').css('background-color');
        scope.originalColor =  $('#defaultTopRow .cartCounter').css('color');

        scope.cartTop = $('#defaultTopRow .cartCounter').offset().top;
        scope.cartLeft = $('#defaultTopRow .cartCounter').offset().left;
        scope.placeCartCounter();
    }
    
    scope.placeCartCounter = function(){
        scope.log('CartUI.placeCartCounter');
        if ($(window).scrollTop()> scope.cartTop) {
            $('#defaultTopRow .cartCounter').css({position:"fixed", top:-5, left: scope.cartLeft});
        } else {
            $('#defaultTopRow .cartCounter').css({position:'', top:''});
        }
    }
    
    scope.animateCartCounter = function(){
        scope.log('CartUI.animateCartCounter');
        $('#defaultTopRow .cartCounter').css({'background-color': $('#topRow').attr('data-cartCounterAnimateColor')});
        $('#navBarRow .cartCounter').css({'color': $('#topRow').attr('data-cartCounterAnimateColor')});
        setTimeout(function(){
            $('#defaultTopRow .cartCounter').css({'background-color':scope.originalBackgroundColor});
            $('#navBarRow .cartCounter').css({'color':scope.originalColor});
        }, 500);
    }
    scope.updateCounters = function(){
        scope.log('CartUI.updateCounters');
        $('.cartCounter .count').text(Cart.countProducts());
    }
    
    scope.animateAddToCart = function(){
        scope.log('CartUI.animateAddToCart');
        if( $('#categoryPage').length > 0 ){
            animateAddToCartDoAnimation(
                scope.$addToCartButton.closest('.addToCartContainer.forAnimationCart'),
                scope.$addToCartButton.closest('.productSmall').find('.addToCartContainer.forAnimationCheck') );
        }else if( $('#productPage').length > 0){
            animateAddToCartDoAnimation(
                scope.$addToCartButton.find('.addToCartIcon.forAnimationCart'),
                scope.$addToCartButton.find('.addToCartIcon.forAnimationCheck') );
        }
    }
    function animateAddToCartDoAnimation($cartIcon, $checkIcon){
        scope.log('CartUI.animateAddToCartDoAnimation');
        $cartIcon.addClass('hidden');
        $checkIcon.removeClass('hidden');
        setTimeout(function(){
            $checkIcon.addClass('hidden');
            $cartIcon.removeClass('hidden');
        }, 800);
    }
    scope.showDiscountDetails = function(){
        scope.log('CartUI.showDiscountDetails');
        $('#showDiscountDetailsContainer').removeClass('hidden');
        $('#discountDetailsContainer').removeClass('pull-right');
    }
    scope.hideDiscountDetails = function(){
        scope.log('CartUI.hideDiscountDetails');
        $('#showDiscountDetailsContainer').addClass('hidden');
        $('#discountDetailsContainer').addClass('pull-right');
    }
    scope.updateCartPriceCalculations = function(results){
        scope.log('CartUI.updateCartPriceCalculations');
        if( $('#pricingContainer').length >= 1 ){
            //get discounts results
            var previousDiscount = results.previousDiscount;
            var currentDiscount = results.currentDiscount;
            var followingDiscount = results.followingDiscount;

            /* HIDE UNUSED CALCULATION PARTS */
            //hide original cart amount if previous or current discount have not been applied
            if(!previousDiscount.applied && !currentDiscount.applied){ //hide
                $('#cartAmountBeforeDiscounts').addClass('hidden');
                scope.hideDiscountDetails();
            }
            //hide previous discount and newAmount if has not been applied
            if(!previousDiscount.applied){ //hide
                $('#cartPreviousDiscount').addClass('hidden');
                $('#cartAmountAfterPreviousDiscount').addClass('hidden');
            }
            //hide current discount if has not been applied
            if(!currentDiscount.applied){ //hide
                $('#cartCurrentDiscount').addClass('hidden');
            }
            //hide following discount if has not been applied
            if(!followingDiscount.applied){ //hide
                $('#cartFollowingDiscount').addClass('hidden');
            }
            if(previousDiscount.applied && !currentDiscount.applied){
                $('#cartAmountAfterPreviousDiscount').addClass('hidden');
            }
            //hide maxDiscount description of current discount if there is not discount limit
            if(!currentDiscount.rule.maxDiscount > 0){ //hide
                $('#cartCurrentDiscountLabelMaxDiscount').addClass('hidden');
            }
            //hide maxDiscount description of following discount if there is not discount limit
            if(followingDiscount.rule.maxDiscount > 0){ //hide
                $('#cartFollowingDiscountLabelMaxDiscount').removeClass('hidden');
            }

            /* UPDATE CALCULATION NUMBERS */
            //update calculations numbers
            $('.cartAmountBeforeDiscountsLive').text(scope.toMoney(results.originalAmount));
            $('.cartPreviousDiscountLive').text(scope.toMoney(-1*previousDiscount.discountAmount));
            $('.cartAmountAfterPreviousDiscountLive').text(scope.toMoney(previousDiscount.newAmount));

            $('.cartCurrentDiscountPercentageLive').text(currentDiscount.rule.discount);
            $('.cartCurrentDiscountMaxDiscountLive').text(scope.toMoney(currentDiscount.rule.maxDiscount));
            $('.cartCurrentDiscountLive').text(scope.toMoney(-1*currentDiscount.discountAmount));

            $('.cartFinalAmountLive').text(scope.toMoney(results.finalAmount));

            $('.cartFollowingDiscountPercentageLive').text(followingDiscount.rule.discount);
            $('.cartFollowingDiscountMaxDiscountLive').text(scope.toMoney(followingDiscount.rule.maxDiscount));
            $('.cartFollowingDiscountLive').text(scope.toMoney(followingDiscount.discountAmount));

            /* SHOW USED CALCULATION PARTS */
            //show maxDiscount description of current discount if there is discount limit
            if(currentDiscount.rule.maxDiscount > 0){ //hide
                $('#cartCurrentDiscountLabelMaxDiscount').removeClass('hidden');
            }
            //show maxDiscount description of following discount if there is discount limit
            if(followingDiscount.rule.maxDiscount > 0){ //hide
                $('#cartFollowingDiscountLabelMaxDiscount').removeClass('hidden');
            }
            //show original cart amount if previous or current discount have been applied
            if(previousDiscount.applied || currentDiscount.applied){ //show
                $('#cartAmountBeforeDiscounts').removeClass('hidden');
                scope.showDiscountDetails();
            }
            //show previous discount and newAmount if has been applied
            if(previousDiscount.applied){
                $('#cartPreviousDiscount').removeClass('hidden');
            }
            //show current discount if has been applied
            if(currentDiscount.applied){
                $('#cartCurrentDiscount').removeClass('hidden');
            }
            //show following discount if has been applied
            if(followingDiscount.applied){
                $('#cartFollowingDiscount').removeClass('hidden');
            }
            if(previousDiscount.applied && currentDiscount.applied){
                $('#cartAmountAfterPreviousDiscount').removeClass('hidden');
            }
        }
    }
    /* Handle cart`s total limitation to show/hide the message and checkout details. */
    scope.handleCartTotalLimitation = function(isSufficient){
        scope.log('CartUI.handleCartTotalLimitation');
        if(isSufficient){
            //hide checkoutCannotOrder message
            $('#cartHasNotSufficientTotalMessage').addClass('hidden');
            if(Cart.countProducts() > 0){
                //show checkoutDetails
                $('#checkoutDetails').removeClass('hidden');
            }
        } else {
            //hide checkoutDetails
            $('#checkoutDetails').addClass('hidden');
            //show checkoutCannotOrder message
            if(Cart.countProducts() > 0){
                $('#cartHasNotSufficientTotalMessage').removeClass('hidden');
            }
        }
    }
    scope.updateProductPriceAndQuantity = function($tr, quantity){
        scope.log('CartUI.updateProductPriceAndQuantity');
        var unitPrice = $tr.attr('data-unitPrice');
        var price = quantity*unitPrice;
        var totalPrice = scope.toMoney(price);
        if( quantity<=1 ){
            $tr.find('.lessQuantity').addClass('disabled');
        }else{
            $tr.find('.lessQuantity').removeClass('disabled');
        }
        $tr.find('.count').text(quantity);
        $tr.find('.price').text(totalPrice+' €');
    }
    /** Number.prototype.format(n, x, s, c)
    * @param integer n: length of decimal
    * @param integer x: length of whole part
    * @param mixed   s: sections delimiter
    * @param mixed   c: decimal delimiter */
    scope.toMoney = function(inputNumber){
        scope.log('CartUI.toMoney');
        var n = 2, x = 3, s = '.', c = ',';
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
            num = inputNumber.toFixed(Math.max(0, ~~n));
        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    }
    /* Check if cart empty and do stuff. */
    scope.checkIfEmpty = function(){
        scope.log('CartUI.checkIfEmpty');
        if( Cart.countProducts()===0 ){
            $('#checkoutPage .cartEmpty').removeClass('hidden');
            $('#checkoutPage #checkoutDetails').addClass('hidden');
            $('#checkoutPage .cartShow, #checkoutPage #pricingContainer').addClass('hidden');
            $('#cartHasNotSufficientTotalMessage').addClass('hidden');
        }
    }
    
})(window, jQuery, CartUI);

/** Creates products */
var Products = Products || {};
(function(window, $, scope, undefined){
    
    scope.log = function($message){
        //console.log($message);
    }
    /** Create product and gather data for Cart addition. */
    scope.getForAddition = function(id, quantity){
        scope.log('Products.getForAddition');
        var product = new Product(id, quantity);
        if(typeof Ingredients!=="undefined"){
            product.setIngredients( Ingredients.gather() );
        }
        return product;
    };
    scope.getForAdditionSubProduct = function(id, quantity, ingredients){
        scope.log('Products.getForAdditionSubProduct');
        var product = new Product(id, quantity);
        product.setIngredients( ingredients );
        return product;
    };
    /** Build object back from cookie. */
    scope.createFromCookie = function(obj){
        scope.log('Products.createFromCookie');
        var product = new Product(obj.id, obj.quantity);
        product.isGroup = obj.isGroup;
        product.key = obj.key;
        product.productGroups = obj.productGroups;
        product.setIngredients(obj.ingredients);
        return product;
    };
    scope.createGroupProductFromView = function(){
        scope.log('Products.createGroupProductFromView');
        var id = $('#productPage #product').attr('data-productId');
        var quantity = 1;
        var productGroups = [];
        $('#productPage .productRuleGroup').each(function(){
            var $productRuleGroup = $(this);
            var productGroup = {
                id: $productRuleGroup.attr('data-id'),
                products: []
            };
            $productRuleGroup.find('.productRow.selected').each(function(){
                var $productRow = $(this);
                var productId = $productRow.attr('data-productId');
                var quantity = 1;
                if( ProductRules.quantityCountElement($productRow).length>0 ){
                    quantity = ProductRules.quantityCount($productRow);
                }
                // create combinations of the product and the selected ingredients
                var productCombinations = [];
                for(var i=0; i<quantity; i++){
                    var ingredients = Ingredients.gatherForSubProduct($productRow, i);
                    var combinationExists = false;
                    for(var j=0; j<productCombinations.length && !combinationExists; j++){//compare with existing combinations
                        var existingProduct = productCombinations[j];
                        if( Ingredients.compare( existingProduct.ingredients, ingredients ) ){
                            existingProduct.moreQuantity();
                            combinationExists = true;
                            break;
                        }
                    }
                    if(!combinationExists){
                        var product = scope.getForAdditionSubProduct(productId, 1, ingredients);
                        productCombinations.push( product );
                    }
                }
                for(var i=0; i<productCombinations.length; i++){
                    productGroup.products.push( productCombinations[i] );
                }
            });
            productGroups.push(productGroup);
        });
        var product = scope.getForAddition(id, quantity);
        product.isGroup = 1;
        product.productGroups = productGroups;
        return product;
    };
    scope.compare = function(p1, p2, isGroupProducts){
        scope.log('Products.compare');
        isGroupProducts = isGroupProducts !== undefined ? isGroupProducts: false;
        scope.log(p1);
        scope.log(p2);
        if(p1.productGroups.length == p2.productGroups.length
            &&  p1.ingredients.length == p2.ingredients.length 
            && (!isGroupProducts || (p1.id == p2.id && p1.quantity == p2.quantity))
            &&  Ingredients.compare( p1.ingredients, p2.ingredients )
        ){
            for(var i=0; i< p1.productGroups.length; i++){
                var p1Group = p1.productGroups[i];
                var p2Group = p2.productGroups[i];
                if(p1Group.id == p2Group.id && p1Group.products.length == p2Group.products.length){
                    for(var j=0; j< p1Group.products.length; j++){
                        if(!scope.compare(p1Group.products[j], p2Group.products[j], true)){
                            return false;
                        }
                    }
                }else{
                    return false;
                }
            }
            return true;
        }
        return false;
    };
    /** Calculates and sets the product price including ingredinents costs.*/
    scope.setProductPrice = function(){
        scope.log('Products.setProductPrice');
        var $product = $('#product');
        if($product.length > 0){
            var price = parseFloat($product.attr('data-price'));
            price += Ingredients.totalPrice( $product );
            var priceForDisplay = scope.formatPriceForDisplay(price);
            $product.find('.price .number').text(priceForDisplay);
        }
        return price;
    }
    /** Translate . to , for price display. */
    scope.formatPriceForDisplay = function(price) {
        scope.log('Products.formatPriceForDisplay');
        var d = 2; //length of decimal
        var w = 3; //length of whole part
        var s = '.';//sections delimiter
        var c = ',';//decimal delimiter
        var re = '\\d(?=(\\d{' + (w || 3) + '})+' + (d > 0 ? '\\b' : '$') + ')',
        num = price.toFixed(Math.max(0, ~~d));
        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    }
    function Product(id, quantity){
        scope.log('Product.Product');
        this.id = id;
        this.quantity = 1;
        if( !isNaN(quantity) ){
            this.quantity = quantity;
        }
        this.isGroup = 0;
        this.productGroups = [];
        this.ingredients = [];
        /** Create random key to identify products with same id. */
        this.key = Math.floor( (Math.random()*1000000) +1 );
        
        this.moreQuantity = function(){
            scope.log('Product.moreQuantity');
            this.quantity++;
            return this.quantity;
        }
        /** Down to 1 */
        this.lessQuantity = function(){
            scope.log('Product.lessQuantity');
            if( this.quantity > 1 ){
                this.quantity--;
            }
            return this.quantity;
        }
        this.setIngredients = function(ingredients){
            scope.log('Product.moreQuantity');
            this.ingredients = ingredients;
        }
    }
    
})(window, jQuery, Products);