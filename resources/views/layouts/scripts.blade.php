<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery-migrate-1.2.1.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/jquery.parallax.js') }}"></script>
<script src="{{ asset('js/jquery.wait.js') }}"></script>
<script src="{{ asset('js/modernizr-2.6.2.min.js') }}"></script>
<script src="{{ asset('js/modernizr.custom.js') }}"></script>
<script src="{{ asset('js/revolution-slider/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ asset('js/revolution-slider/js/jquery.themepunch.revolution.min.js') }}"></script>
<script src="{{ asset('js/jquery.nivo.slider.pack.js') }}"></script>
{{--<script src="{{ asset('js/jquery.prettyPhoto.js') }}"></script>--}}
{{--<script src="{{ asset('js/superfish.js') }}"></script>--}}
{{--<script src="{{ asset('js/tweetMachine.js') }}"></script>--}}
<script src="{{ asset('js/tytabs.js') }}"></script>
{{--<script src="{{ asset('js/jquery.gmap.min.js') }}"></script>--}}
{{--<script src="{{ asset('js/circularnav.js') }}"></script>--}}
{{--<script src="{{ asset('js/jquery.sticky.js') }}"></script>--}}
{{--<script src="{{ asset('js/jflickrfeed.js') }}"></script>--}}
{{--<script src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>--}}
{{--<script src="{{ asset('js/waypoints.min.js') }}"></script>--}}
{{--<script src="{{ asset('js/spectrum.js') }}"></script>--}}
{{--<script src="{{ asset('js/switcher.js') }}"></script>--}}
{{--<script src="{{ asset('js/custom.js') }}"></script>--}}
<script src="{{ asset('js/custom.js') }}"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>

<script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>
<script type="text/javascript">
    var session_id = "{!! (Session::getId())?Session::getId():'' !!}";
    var user_id = "{!! (Auth::user())?Auth::user()->id:'' !!}";

    var config =
        {
            apiKey        : "AIzaSyBjpweFh63mmXB-5mOHmIVUfIrW0oN_G3A",
            authDomain    : "fcc-book-trading-e8821.firebaseapp.com",
            databaseURL   : "https://fcc-book-trading-e8821.firebaseio.com",
            storageBucket : "fcc-book-trading-e8821.appspot.com",
        };

    firebase.initializeApp(config);

    var database = firebase.database();

    if({!! Auth::user() !!}) {
        firebase.database().ref('/users/' + user_id + '/session_id').set(session_id);
    }

    firebase.database().ref('/users/' + user_id).on('value', function(snapshot2) {
        var v = snapshot2.val();

        if(v.session_id != session_id) {
            toastr.warning('Your account login from another device!!', 'Warning Alert', {timeOut: 3000});
            setTimeout(function() {
                window.location = '/login';
            }, 4000);
        }
    });

</script>
