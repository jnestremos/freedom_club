@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php
        use App\Models\Expense;        
        use App\Models\ExpenseCategory;        
        $dataCollections = [Expense::all(), ExpenseCategory::all()];                      
        $headers = ['Expense ID', 'Receipt #', 'Category ID', 'Description', 'Computed Expenses', 'Created At', 'Updated At'];              
    @endphp

    <div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
        <x-emp-header title="EXPENSES"></x-emp-header>                       
        <x-emp-button-link title="Add Expense" toggle='true' target='addExpense' :dataCollection="$dataCollections"></x-emp-button-link>    
        <x-emp-button-link title="View Expenses History" link="{{ route('expenses.history') }}"></x-emp-button-link>    
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

    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="Edit Expense"/>  
    </div>    



    <script>

        var category = document.getElementById('category_name')
        //var description = document.getElementById('description')
        var computed_expense = document.getElementById('computed_expense')
        category.addEventListener('change', function(){
            computed_expense.disabled = true 
            computed_expense.value = null                                   
            if(category.value == '5'){                
                category.parentElement.nextElementSibling.nextElementSibling.hidden = false
                category.parentElement.nextElementSibling.nextElementSibling.lastElementChild.disabled = false
                category.parentElement.nextElementSibling.hidden = true
                category.parentElement.nextElementSibling.lastElementChild.disabled = true

                var description = category.parentElement.nextElementSibling.nextElementSibling.lastElementChild
                description.addEventListener('change', function(){
                    computed_expense.value = 0
                    if(description.value.trim() != ''){
                        computed_expense.disabled = false                    
                    }
                    else{
                        computed_expense.value = ''
                        computed_expense.disabled = true
                    }
                }) 
            }
            else{
                category.parentElement.nextElementSibling.nextElementSibling.hidden = true
                category.parentElement.nextElementSibling.nextElementSibling.lastElementChild.disabled = true
                category.parentElement.nextElementSibling.hidden = false
                category.parentElement.nextElementSibling.lastElementChild.disabled = false
                
                var description = category.parentElement.nextElementSibling.lastElementChild                                               
                var shirt_production = ['Printing', 'Embroidery', 'Design Fee']
                var packaging = ['Ziplocks', 'Paper Bags', 'Wrappers', 'Sando Bag', 'Cellophane Bag']
                var promotional_advertise = ['Photographer', 'Videographer', 'Model', 'Promoter']  
                description.addEventListener('change', function(){
                    computed_expense.value = 0
                    if(description.value != "None"){
                        computed_expense.disabled = false
                    }
                    else{
                        computed_expense.disabled = true
                    }
                })           
                if(description.childElementCount > 1){
                    description.innerHTML = ''
                    var option = document.createElement('option')
                    option.value = "None"
                    option.disabled = true
                    option.hidden = true
                    option.selected = true
                    var optionTextNode = document.createTextNode("None")
                    option.appendChild(optionTextNode)
                    description.appendChild(option)
                }
                if(category.value == '2'){
                    shirt_production.forEach(element => {
                        var option = document.createElement('option')
                        option.value = element
                        var optionTextNode = document.createTextNode(element)
                        option.appendChild(optionTextNode)
                        description.appendChild(option)
                    });
                }
                else{
                    if(category.value == '3'){
                        packaging.forEach(element => {
                            var option = document.createElement('option')
                            option.value = element
                            var optionTextNode = document.createTextNode(element)
                            option.appendChild(optionTextNode)
                            description.appendChild(option)
                        }); 
                    }
                    else{
                        if(category.value == '4'){
                            promotional_advertise.forEach(element => {
                                var option = document.createElement('option')
                                option.value = element
                                var optionTextNode = document.createTextNode(element)
                                option.appendChild(optionTextNode)
                                description.appendChild(option)
                            }); 
                        }
                    }
                }
            }
        })
        
        computed_expense.addEventListener('change', function(){
            computed_expense.value = parseFloat(computed_expense.value)            
        })
    </script>


@endsection