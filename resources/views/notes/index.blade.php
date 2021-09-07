@extends('index')
 
@section('content')
    <div class="row" style="margin-top: 5rem;">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Заметки</h2>
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('notes.create') }}"> Добавить заметку</a>
            </div>
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
   
    <table class="table table-bordered">
        <tr>
            <th>№</th>
            <th>ID</th>
            <th>Заголовок</th>
            <th>Описание</th>
            <th width="280px">Действие</th>
        </tr>
        @foreach ($data as $key => $value)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $value->id }}</td>
            <td>{{ $value->title }}</td>
            <td>{{ \Str::limit($value->description, 100) }}</td>
            <td>
                <form action="{{ route('notes.destroy',$value->id) }}" method="POST">   
                    <a class="btn btn-info btn-sm" href="{{ route('notes.show',$value->id) }}">Посмотреть</a>    
                    <a class="btn btn-primary btn-sm" href="{{ route('notes.edit',$value->id) }}">Редактировать</a>   
                    @csrf
                    @method('DELETE')      
                    <button type="submit" class="btn btn-danger btn-sm">Удалить</button>


                    <a class="btn btn-warning btn-sm" href="{{ route('dlpdf',$value->id) }}">Скачать в PDF</a>


                </form>
            </td>
        </tr>
        @endforeach
    </table>  
    {!! $data->links() !!}      
@endsection