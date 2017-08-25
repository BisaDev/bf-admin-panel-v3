export default {
    init () {
        $(document).ready(function(){
            $('.knob').knob({
                'format' : function (value) {
                    return value + '%';
                }
            });
        });
    },
}
