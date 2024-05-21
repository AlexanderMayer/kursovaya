@extends('layouts.main')

@section('content')
    <p>Тестируем</p>
    <br>
{{--    <br>--}}
{{--    <form action="{{route('imgtest.store')}}" method="post" enctype="multipart/form-data">--}}
{{--        @csrf--}}
{{--        <p>Добавьте файл: <input type="file" name="image"></p>--}}
{{--        <input type="submit" value="Отправить">--}}
{{--    </form>--}}


{{--    <p>{{$blank}}</p>--}}
    @foreach($blank as $el)
        <p>{{$el}}</p>
    @endforeach





    @isset($blank)
{{--        <img alt="img" src="{{asset('/storage/' . $blank)}}">--}}

    @endisset
@endsection
