/**
 * Created by Stijn on 2-7-2015.
 */
var ArtistEditView = Backbone.View.extend({
    el: '.page',
    events: {
        'submit .edit-artist-form': 'saveArtist',
        'click .delete': 'deleteArtist'
    },
    saveArtist: function (ev) {
        var artistDetails = $(ev.currentTarget).serializeObject();
        var artist = new Artist();
        artist.save(artistDetails, {
            success: function (artist) {
                router.navigate('', {trigger:true});
            }
        });
        return false;
    },
    deleteArtist: function (ev) {
        this.artist.destroy({
            success: function () {
                console.log('deleted');
                router.navigate('', {trigger:true});
            }
        });
        return false;
    },
    render: function (options) {
        var that = this;
        if(options.id) {
            that.artist = new Artist({id: options.id});
            that.artist.fetch({
                success: function (artist) {
                    var template = _.template($('#edit-artist-template').html(), {artist: artist});
                    that.$el.html(template);
                }
            })
        } else {
            var template = _.template($('#edit-artist-template').html(), {artist: null});
            that.$el.html(template);
        }
    }
});