@extends('map.master')
@section('form')
    <script>var refresh2 = function(){
            var villageId = 10346;
            $.get( "/dimas/medical-facilities?villageId="+villageId, function( data ) {
                console.log(data);
            });
        }
    </script>
    <label>Village ID</label>
    <input id="village_id"/>
    <a onclick="refresh2()" class="btn btn-primary">Filter</a>
@endsection
@section('script')
<script>
    var refresh = function(){
        var villageId = 10346;
        $.get( "/dimas/medical-facilities?villageId="+villageId).done(function( data ) {
            console.log(data);
        });
    }

</script>
@endsection