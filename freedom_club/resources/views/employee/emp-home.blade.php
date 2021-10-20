@extends('layouts.emp-app')
@section('content')
@php
    use Illuminate\Support\Facades\DB; 
    use App\Models\Expense;   
@endphp
<div style="display: flex; align-items:center; justify-content:space-between; width:35%; height: 6%;">
    <x-emp-header title="HOME"></x-emp-header>                                
</div>
<ul>
    @if (session()->has('error'))
        <li>{{ session('error') }}</li>
    @endif
    @error('category_name')
        <li>{{$message}}</li>
    @enderror
    @error('description')
        <li>{{$message}}</li>
    @enderror
    @error('computed_expense')
        <li>{{$message}}</li>
    @enderror
</ul>

<div style="margin-top:20px; width:100%; height:80%; display:flex; flex-wrap:wrap; position: relative; ">
    <div style="display: flex; height:100%; flex-wrap:wrap; width:90%;">
        <div style="width: 50%; height:300px;">
            <canvas id="myChart1" style="width: 100%; height:80%;"></canvas>
        </div>
        <div style="width: 40%; height:300px;">
            <canvas id="myChart2" style="width: 100%; height:80%;"></canvas>
        </div>
        <div style="width: 50%; height:300px;">
            <canvas id="myChart3" style="width: 100%; height:80%;"></canvas>
        </div>
    </div> 
    <div id="slot" style="display:block;">        

    </div>
    <div style="position: absolute; left:0%; bottom:10%;">
        Total Balance: {{ DB::table('balance_sheet')->get()[count(DB::table('balance_sheet')->get()) - 1]->total_balance }}
    </div>
    
    <div style="position: absolute; right:5%; bottom:5%;">
        <a href="{{ route('carousel.index') }}" style="color:black; text-decoration:none"><button class="btn btn-primary">Edit Carousel Images</button></a>
    </div>    
</div>


<script>
        var labels = [
            @foreach(DB::table('sales')->where('deleted_at', null)->get() as $sale)
                '{{ $sale->sales_category }}',
            @endforeach
        ]
        var data = [
            @foreach(DB::table('sales')->where('deleted_at', null)->get() as $sale)
                {{ $sale->total }},
            @endforeach
        ]
        var labels1 = [
            @foreach(DB::table('sales')->get() as $sale)
                '{{ $sale->sales_category }}',
            @endforeach
        ]
        var data1= [
            @foreach(DB::table('sales')->get() as $sale)
                {{ $sale->total }},
            @endforeach
        ]
        var expense_desc = [
            @foreach(Expense::selectRaw('sum(computed_expenses) as total, description')->groupBy('description')->get() as $expense)
            '{{ $expense->description }}',
            @endforeach
        ]
        var expense_data = [
            @foreach(Expense::selectRaw('sum(computed_expenses) as total, description')->groupBy('description')->get() as $expense)
                {{ $expense->total }},
            @endforeach
        ]
        var total = 0;
        var slot = document.getElementById('slot')
        data.forEach(function(d){
            total = total + d
        })
        expense_data.forEach(function(expense){
            total = total - expense
        })
        var i = document.createElement('i')
        slot.innerText = 'Computed Net Sales: Php ' + total + ' '
        if(total > 0){
            i.className = 'fas fa-caret-up'
            i.style.color = 'green'
            slot.appendChild(i)
        }
        else if(total < 0){
            i.className = 'fas fa-caret-down'
            i.style.color = 'red'
            slot.appendChild(i)
        }        
        var myChart1 = document.getElementById('myChart1').getContext('2d');
        var myChart2 = document.getElementById('myChart2').getContext('2d');        
        var monthlyChart1 = new Chart(myChart1, {
            type:'bar',
            data:{
                labels:labels1,
                datasets:[{
                    label:'Sales',
                    data:data1,
                    backgroundColor: ['red', 'yellow', 'green']
                }]
            },
            options:{
                plugins: {
                    title: {
                        display: true,
                        text: 'Overall Gross Sales',                        
                    },
                    legend:{
                        display:false
                    }
                }
            }
        });
        var monthlyChart2 = new Chart(myChart2, {
            type:'pie',
            data:{
                labels:labels,
                datasets:[{
                    label:'Sales',
                    data:data,
                    backgroundColor: ['red', 'yellow', 'green']
                }]
            },
            options:{
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Gross Sales',                        
                    }
                }
            }
        });
        var monthlyChart3 = new Chart(myChart3, {
            type:'bar',
            data:{
                labels:expense_desc,
                datasets:[{
                    label:'Expenses',
                    data:expense_data,
                    backgroundColor: ['red', 'yellow', 'green']
                }]
            },
            options:{
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Expenses',                        
                    }
                }
            }
        });
   
    window.setInterval(function(){
        labels = [
            @foreach(DB::table('sales')->where('deleted_at', null)->get() as $sale)
                '{{ $sale->sales_category }}',
            @endforeach
        ]
        data = [
            @foreach(DB::table('sales')->where('deleted_at', null)->get() as $sale)
                {{ $sale->total }},
            @endforeach
        ]
        labels1 = [
            @foreach(DB::table('sales')->get() as $sale)
                '{{ $sale->sales_category }}',
            @endforeach
        ]
        data1= [
            @foreach(DB::table('sales')->get() as $sale)
                {{ $sale->total }},
            @endforeach
        ]
        expense_data = [
            @foreach(Expense::all() as $expense)
                {{ $expense->computed_expenses }},
            @endforeach
        ]

        total = 0;
        slot.innerHTML = ''
        data.forEach(function(d){
            total = total + d
        })
        expense_data.forEach(function(expense){
            total = total - expense
        })
        var i = document.createElement('i')
        slot.innerText = 'Computed Net Sales: Php ' + total + ' '
        if(total > 0){
            i.className = 'fas fa-caret-up'
            i.style.color = 'green'
            slot.appendChild(i)
        }
        else if(total < 0){
            i.className = 'fas fa-caret-down'
            i.style.color = 'red'
            slot.appendChild(i)
        }    

        monthlyChart1.data.datasets[0].data = data1
        monthlyChart1.data.labels = labels1
        monthlyChart1.update();
        monthlyChart2.data.datasets[0].data = data
        monthlyChart2.data.labels = labels
        monthlyChart2.update();        
    }, 1000)
</script>
@endsection