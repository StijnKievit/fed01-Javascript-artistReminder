/**
 * Created by Stijn on 2-7-2015.
 */
var FavArtistListView = Backbone.View.extend({
    el: '.fav',
    events: {
      'click .remove': 'removeFavArtist'

    },
    removeFavArtist: function(object){


        console.log( object.currentTarget.attributes[0].value);

        $.ajax({
            url: '/artists/fav/'+object.currentTarget.attributes[0].value,
            type: 'DELETE',
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
        var favartists = new FavArtists();
        favartists.fetch({
            success: function (favartists) {
                var template = _.template($('#fav-artist-list-template').html(), {favartists: favartists.models});
                that.$el.html(template);
            }
        })
    }
});


