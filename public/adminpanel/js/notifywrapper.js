/* Encapsulate notify plugin actions. */
var NW = NW || {};
(function(window, $, scope, undefined){
    
    var offset = { x: 10, y: 110 };
    var animate = {};
    
    scope.success = function(message){
        $.notify(
            {   message: message
            },{
                type: 'success',
                delay: 200,
                animate: animate,
                offset: offset,
                allow_dismiss: false
            }
        );      
    }
    scope.fail = function(message){
        $.notify(
            {   message: message
            },{
                type: 'danger',
                delay: 2000,
                animate: animate,
                offset: offset
            }
        );      
    }
    
})(window, jQuery, NW);