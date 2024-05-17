@extends('layouts.main')

@section('content')
    <p>Попытка загрузить картинку на сервер</p>
    <br>
    <form action="{{route('imgtest.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <p>Добавьте файл: <input type="file" name="image"></p>
        <input type="submit" value="Отправить">
    </form>

    @isset($path)
        <img alt="img" src="{{asset('/storage/' . $path)}}">
    @endisset
@endsection
