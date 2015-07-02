/**
 * Created by Stijn on 2-7-2015.
 */
var artistListView = new ArtistListView();
var artistEditView = new ArtistEditView();
var favArtistListView = new FavArtistListView();
var Router = Backbone.Router.extend({
    routes: {
        "": "home",
        "edit/:id": "edit",
        "new": "edit"
    }
});
var router = new Router;
router.on('route:home', function() {
    // render artist list
    artistListView.render();
    favArtistListView.render();
})
router.on('route:edit', function(id) {
    artistEditView.render({id: id});
})
Backbone.history.start();