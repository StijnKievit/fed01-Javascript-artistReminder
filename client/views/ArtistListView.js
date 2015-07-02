/**
 * Created by Stijn on 2-7-2015.
 */
var ArtistListView = Backbone.View.extend({
    el: '.page',
    events: {
        'click .addFav': 'addFavArtist'

    },
    addFavArtist: function(object){


        console.log( object.currentTarget.attributes[0].value);

        $.ajax({
            url: '/artists/fav/'+object.currentTarget.attributes[0].value,
            type: 'POST',
            data: {fav: true},
            success: function(result) {
                // Do something with the result
                console.log(result);
                favArtistListView.render();
                artistListView.render();

            }
        });


    },
    render: function () {
        var that = this;
        var artists = new Artists();
        artists.fetch({
            success: function (artists) {
                var template = _.template($('#artist-list-template').html(), {artists: artists.models});
                that.$el.html(template);
            }
        })
    }
});