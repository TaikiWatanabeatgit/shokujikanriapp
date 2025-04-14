@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>食事記録サマリー</h1>
        <a href="{{ route('meal-records.index') }}" class="btn btn-secondary">一覧に戻る</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">今日の記録</h5>
                </div>
                <div class="card-body">
                    @if($todayRecords->isEmpty())
                        <p>今日の記録はありません。</p>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>食事の種類</th>
                                        <th>食事名</th>
                                        <th>カロリー</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayRecords as $record)
                                        <tr>
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
                                            <td>{{ $record->calories }} kcal</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">統計情報</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>今月の合計カロリー</h6>
                        <p class="h4">{{ number_format($monthlyTotalCalories) }} kcal</p>
                    </div>
                    <div class="mb-3">
                        <h6>過去の平均カロリー</h6>
                        <p class="h4">{{ number_format($pastAverageCalories) }} kcal</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">最近の記録</h5>
        </div>
        <div class="card-body">
            @if($pastRecords->isEmpty())
                <p>過去の記録はありません。</p>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>日付</th>
                                <th>食事の種類</th>
                                <th>食事名</th>
                                <th>カロリー</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pastRecords->take(10) as $record)
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
                                    <td>{{ $record->calories }} kcal</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection 