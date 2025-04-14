@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>食事記録一覧</h1>
        <div>
            <a href="{{ route('meal-records.summary') }}" class="btn btn-info me-2">サマリー</a>
            <a href="{{ route('meal-records.create') }}" class="btn btn-primary">新規記録</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>食事の種類</th>
                    <th>食事名</th>
                    <th>カロリー</th>
                    <th>メモ</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mealRecords as $record)
                    <tr>
                        <td>{{ $record->date->format('Y-m-d') }}</td>
                        <td>
                            @switch($record->meal_type)
                                @case('breakfast')
                                    朝食
                                    @break
                                @case('lunch')
                                    昼食
                                    @break
                                @case('dinner')
                                    夕食
                                    @break
                                @case('snack')
                                    間食
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $record->food_name }}</td>
                        <td>{{ $record->calories ?? '-' }}</td>
                        <td>{{ $record->notes ?? '-' }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('meal-records.edit', $record->id) }}" class="btn btn-sm btn-warning">編集</a>
                                <form action="{{ route('meal-records.destroy', $record->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $mealRecords->links() }}
    </div>
@endsection 