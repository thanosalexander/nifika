<script>
        /** Handles translations */
    var Trans = Trans || {};
    (function(window, scope, undefined){
        //translations in dot array format
        var translations = <?= json_encode(array_dot(['labels' => trans('labels')]), JSON_UNESCAPED_UNICODE);  ?>;
        // get translation of given tag in current language
        scope.get = function(tag, params){
            var trans = !translations.hasOwnProperty(tag) ? tag : replaceParameters(translations[tag], params);
            return trans;
        };
        
        /** Replace string parameters with given */
        var replaceParameters = function(trans, params){
            var defaultParameters = findParamaters(trans);
            if(defaultParameters.length > 0){
                params = params === undefined ? {}: params;
                for(var i = 0; i < defaultParameters.length; i++){
                    var paramName = defaultParameters[i];
                    trans = trans.replace(':'+paramName, (params.hasOwnProperty(paramName) ? params[paramName] : ''));
                }
            }
            
            return trans;
        };
        
        /** Extract parameters from string */
        var findParamaters = function(string){
            var regex = /:([\w\d_]+)/gi;
            var matches = [];
            var match = regex.exec(string);
            while (match != null) {
                matches.push(match[1]);
                match = regex.exec(string);
            }
            return matches;
        };
        

    })(window, Trans);
</script>