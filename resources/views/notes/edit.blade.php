@extends('index')
   
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Редактировать заметку</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('notes.index') }}"> Вернуться</a>
            </div>
        </div>
    </div>
   
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Внимание!</strong> Введены неверные данные.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  
    <form action="{{ route('notes.update',$note->id) }}" method="POST">
        @csrf
        @method('PUT')
   
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Заголовок:</strong>
                    <input type="text" name="title" value="{{ $note->title }}" class="form-control" placeholder="Заголовок">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Описание:</strong>
                    <textarea class="form-control" style="height:150px" name="description" placeholder="Описание">{{ $note->description }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
   
    </form>
@endsection