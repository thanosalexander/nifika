/** 
 * Use to handle overlay and other administration ui needs.
 */
$(document).ready(function () {
    $('body').on('submit', 'form', function(){
        Overlay.show();
    });
});

var Overlay = Overlay || {};
(function(window, $, scope, undefined){
    
    scope.show = function(){
        $('#loadingOverlay').removeClass('hidden');
    }
    
    scope.hide = function(){
        $('#loadingOverlay').addClass('hidden');
    }
    
})(window, jQuery, Overlay);

