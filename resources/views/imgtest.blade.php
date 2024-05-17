@extends('layouts.main')

@section('content')
    <p>Тестируем</p>
{{--    <br>--}}
{{--    <form action="{{route('imgtest.store')}}" method="post" enctype="multipart/form-data">--}}
{{--        @csrf--}}
{{--        <p>Добавьте файл: <input type="file" name="image"></p>--}}
{{--        <input type="submit" value="Отправить">--}}
{{--    </form>--}}

    @isset($blank)
        <img alt="img" src="{{asset('/storage/' . $blank)}}">
        <p>{{$blank}}</p>
    @endisset
@endsection
