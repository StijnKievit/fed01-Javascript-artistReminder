/**
 * Created by Stijn on 2-7-2015.
 */
function htmlEncode(value){
    return $('<div/>').text(value).html();
}
$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {
    options.url = 'http://stud.cmi.hr.nl/0875013/jaar2/simpleRestServer' + options.url;
});