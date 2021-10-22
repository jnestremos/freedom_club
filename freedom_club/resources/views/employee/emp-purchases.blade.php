@extends('layouts.emp-app')
@section('content')
<style>
    th, td{
        text-align: center;
    }
</style>

    @php                    
        use App\Models\SuppTransaction;
        use App\Models\Supplier;                
        use App\Models\Material; 
        use Illuminate\Support\Facades\DB;
        use Carbon\Carbon;
        use App\Mail\RemindPayment;    
        use Illuminate\Support\Facades\Mail;                  
        $dataCollections = [SuppTransaction::all(), Supplier::all(), Material::all()];            
        $headers = ['Receipt #', 'Supplier Name', 'Total Cost (with Shipping Fee)', 'Date Paid', 'Created At', 'Updated At'];
        $dayToday = Carbon::now()->toDayDateTimeString();
        $dateToday = Carbon::now()->toDateString();
        $dayTodayArray = explode(',', $dayToday);
        $dayToday = $dayTodayArray[0];                  
        if($dayToday == "Tue"){
            foreach($dataCollections[0] as $transaction){                
                $created_at = $transaction->created_at->toDateString();                              
                $remind_id = DB::table('remind_payments')->where('supp_transactions_id', $transaction->id)->first();                
                if($transaction->datePaid == null && $remind_id == null && $created_at == $dateToday){
                    DB::table('remind_payments')->insert([
                        'supp_transactions_id' => $transaction->id,
                        'created_at' => Carbon::now()
                    ]);
                    Mail::to(auth()->user()->employee->emp_email)->send(new RemindPayment($transaction));
                }
            }            
        }
        else{
            DB::table('remind_payments')->truncate();
        }                   
    @endphp
    @if (count($dataCollections[1]) != 0 && count($dataCollections[2]) != 0)           
        @php                      
            $disabled = 'false';
        @endphp
    @else    
        @php                     
            $disabled = 'true';
        @endphp
    @endif

    <div style="display: flex; align-items:center; justify-content:space-between; width:100%; height: 6%;">
        <x-emp-header title="SUPPLIER PURCHASE RECORDS"></x-emp-header>        
        <x-emp-button-link title="View All Item List" link="{{ route('purchases.indexItems') }}"></x-emp-button-link>
        <x-emp-button-link title="Add Transaction" toggle='true' target='addTransaction' :disabled='$disabled' :dataCollection="$dataCollections" ></x-emp-button-link>        
        <x-emp-button-link title="View Transaction History" link="{{ route('purchases.history') }}"></x-emp-button-link>
    </div>
    <ul>
        @if (session()->has('error'))
            <li>{{ session('error') }}</li>
        @endif
        @error('receipt_number')
            <li class="">{{ $message }}</li>
        @enderror
        @error('supplier_id')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_id')
            <li class="">{{ $message }}</li>
        @enderror
        @error('material_quantity')
            <li class="">{{ $message }}</li>
        @enderror
        @error('shipping_fee')
            <li class="">{{ $message }}</li>
        @enderror
        @error('datePaid')
            <li class="">{{ $message }}</li>
        @enderror
    </ul>  
    <div style="margin-top:20px; width:100%;">
        <x-emp-table :dataCollection="$dataCollections" :headers="$headers" title="Edit Transaction"/>  
    </div>
    
    <script>    
        var counter = 1;
        var checker = 1;        
        var supplier_id = document.getElementById('supplier_id');
        var material_id = document.getElementById('material_id');
        var modal = document.getElementById('addTransaction');
        var material_qty = document.getElementById('material_quantity');
        var add_button = document.getElementById('add_button')
        var totalCost = document.getElementById('totalCost')   
        var costs = []
        var shipping_fee = document.getElementById('shipping_fee')
        var clear_item = document.getElementById('clear_item')                 
        var supplierIDArray = [
                @foreach($dataCollections[1] as $supplier)
                    "{{ $supplier->id }}",
                 @endforeach
                ];

                var materialIDArray = [
                @foreach($dataCollections[2] as $material)
                    "{{ $material->id }}",
                 @endforeach
                ];
                var materialNumberArray = [
                @foreach($dataCollections[2] as $material)
                    "{{ $material->material_number }}",
                 @endforeach
                ];
                var materialPriceArray = [
                @foreach($dataCollections[2] as $material)
                    "{{ $material->material_price }}",
                 @endforeach
                ];
               
                var supplierMaterialIDArray = [
                @foreach($dataCollections[2] as $material)
                    "{{ $material->supplier_id }}",
                 @endforeach
                ]; 
                
                var selectedMaterialID = []

        clear_item.addEventListener('click', function(){
            totalCost.value = 0
            clear_item.disabled = true
            for(var i = 0; i < costs.length; i++){
                if(i != 0){
                    costs[i] = 0
                }
            }
            costs.forEach(function(cost){
                totalCost.value = parseFloat(totalCost.value) + parseFloat(cost) 
            })
            clear_item.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.innerHTML = ''
        })
        modal.addEventListener('show.bs.modal', function(){                       
            shipping_fee.value = 0;            
            if(supplier_id.value == ''){
                material_id.previousElementSibling.style.display = 'none'
                material_id.style.display = 'none'
                material_qty.style.display = 'none'        
                material_qty.previousElementSibling.style.display = 'none'
                add_button.style.display = 'none'
            }
            shipping_fee.addEventListener('change', function(){
                if(shipping_fee.value == 0 || shipping_fee.value == null || shipping_fee.value == '' || shipping_fee.value < 0){
                    shipping_fee.value = 0
                }
                else{
                    shipping_fee.value = parseInt(shipping_fee.value)
                }
            })
            supplier_id.addEventListener('change', function(){
                //selectedMaterialID = []
                supplier_id.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.innerHTML = ''
                supplier_id.parentElement.nextElementSibling.nextElementSibling.lastElementChild.value = 0            
                                                      
                material_qty.value = 0  
                totalCost.value = 0
                costs = []
                //totalCostComputation[0] = 0                
                var materialIDLength = 0
                for(var i = 0; i < materialIDArray.length; i++){
                        for(var ii = 0; ii < supplierIDArray.length; ii++){
                            if(supplierMaterialIDArray[i] == supplier_id.value){  
                                materialIDLength++
                                break                                                            
                            }
                        }
                    } 
                material_qty.addEventListener('change', function(){                                                                         
                    totalCost.value = 0                
                    if(material_qty.value < 0 || material_qty.value == '' || material_qty.value == 0 || material_id.value == ''){                                                                            
                        material_qty.value = 0
                        for(var i = 0; i < selectedMaterialID.length; i++){
                            if(i != 0){
                                selectedMaterialID[i] = 0
                            }
                        }                                                 
                        costs = []                                            
                        add_button.style.display = 'none'
                        add_button.previousElementSibling.innerHTML = ''
                        
                                                
                        //costs = []
                        for(var i = 0; i < materialIDArray.length; i++){                                   
                            if(materialIDArray[i] == parseInt(material_qty.parentElement.previousElementSibling.lastElementChild.value)){                                        
                                var qtyValue = Math.round(material_qty.value)
                                if(costs[0] != null){
                                    costs[0] = qtyValue * materialPriceArray[i]
                                }  
                                else{
                                    costs.push(qtyValue * materialPriceArray[i])
                                }                                                          
                                costs.forEach(function(cost){
                                    totalCost.value = parseFloat(totalCost.value) + parseFloat(cost)
                                })                                                                    
                                break
                            }
                        } 
                    }
                    else{
                        materialIDLength--
                        // if(selectedMaterialID.length == 0){
                        //     selectedMaterialID.push(material_qty.value)
                        // }       
                        // else{
                        //     selectedMaterialID[0] = material_qty.value
                        // }
                        material_qty.value = parseInt(material_qty.value)
                        if(materialIDLength == 0){
                            add_button.style.display = 'none'
                        }
                        else{
                            add_button.style.display = 'block'                            
                        }                        
                        
                        for(var i = 0; i < materialIDArray.length; i++){                                    
                            if(materialIDArray[i] == parseInt(material_qty.parentElement.previousElementSibling.lastElementChild.value)){                                        
                                var qtyValue = Math.round(material_qty.value)                                
                                if(costs[0] == null){
                                    costs.unshift(qtyValue * materialPriceArray[i])                                    
                                    costs.forEach(function(cost){
                                        totalCost.value = parseFloat(totalCost.value) + parseFloat(cost)
                                    })                                    
                                }
                                else{
                                    costs[0] = qtyValue * materialPriceArray[i]
                                    costs.forEach(function(cost){
                                        totalCost.value = parseFloat(totalCost.value) + parseFloat(cost)
                                    })
                                    //&& checkerConcat[parseInt(material_qty.className[material_qty.className.length - 1]) - 1] != parseInt(material_qty.className[material_qty.className.length - 1])                                    
                                }
                                break
                            }
                        }
                    }
                    //console.log(totalCost1)  
                    console.log(costs)   
                })  
                                
                                   


                material_id.addEventListener('change', function(){
                    for(var i = 0; i < selectedMaterialID.length; i++){
                        if(i != 0){
                            selectedMaterialID[i] = 0
                        }
                    }                                                                                                                                     
                    selectedMaterialID[0] = material_id.value 
                    //console.log(selectedMaterialID)                        
                    material_qty.value = 0
                    costs = []
                    costs[0] = 0
                    totalCost.value = 0
                    material_id.parentElement.nextElementSibling.nextElementSibling.innerHTML = ''
                    for(var i = 0; i < materialIDArray.length; i++){                                    
                        if(materialIDArray[i] == parseInt(material_qty.parentElement.previousElementSibling.lastElementChild.value)){                                        
                            var qtyValue = Math.round(material_qty.value)                            
                            if(costs[0] == null){
                                costs.push(qtyValue * materialPriceArray[i])
                                //totalCostComputation.concat(totalCost2)
                                costs.forEach(function(cost){
                                    totalCost.value = parseFloat(totalCost.value) + parseFloat(cost)
                                })
                                
                            }
                            else{
                                costs[0] = qtyValue * materialPriceArray[i]                                                                   
                                costs.forEach(function(cost){
                                    totalCost.value = parseFloat(totalCost.value) + parseFloat(cost)
                                })
                            }
                            break
                        }
                    }                    
                })

                


                if(!supplier_id.value == ""){                       
                    var checker = 0
                    add_button.style.display = 'none'                                     
                    material_id.innerHTML = ''              
                    material_id.previousElementSibling.style.display = 'block'
                    material_id.style.display = 'block'
                    material_qty.style.display = 'block'        
                    material_qty.previousElementSibling.style.display = 'block'                    
                    for(var i = 0; i < materialIDArray.length; i++){
                        for(var ii = 0; ii < supplierIDArray.length; ii++){
                            if(supplierMaterialIDArray[i] == supplier_id.value){
                                if(checker == 0){
                                    var option = document.createElement('option')
                                    option.value = ''
                                    option.disabled = true
                                    option.selected = true
                                    option.hidden = true
                                    var optionTextNode = document.createTextNode('None')
                                    option.appendChild(optionTextNode)
                                    material_id.appendChild(option)
                                    i--
                                    checker++;
                                    break 
                                }
                                else{
                                    selectedMaterialID.push(0)
                                    var option = document.createElement('option')
                                    option.value = materialIDArray[i]
                                    var optionTextNode = document.createTextNode(materialNumberArray[i])
                                    option.appendChild(optionTextNode)
                                    material_id.appendChild(option)
                                    break
                                }                                
                            }
                        }
                    }                      
                    //console.log(selectedMaterialID)                
                }
            })        
            add_button.firstElementChild.addEventListener('click', function(){ 
                    clear_item.disabled = false 
                    totalCost.value = 0
                    add_button.style.display = 'none'                                                                       
                    var div1 = document.createElement('div')
                    div1.style.display = 'flex'
                    div1.style.marginTop = '20px'
                    div1.style.alignItems = 'center'
                    var label1 = document.createElement('label')
                    label1.setAttribute('for', 'material_id' + counter)
                    label1.className = 'form-label text-white'
                    label1.style.marginRight = '60px'
                    var label1TextNode = document.createTextNode('Material Number: ')
                    label1.appendChild(label1TextNode)
                    div1.appendChild(label1)

                    var select = document.createElement('select')
                    select.id = 'material_id' + counter
                    select.name = 'material_id' + counter
                    select.className = 'form-select material_id' 
                    select.style.width = '200px'
                    var checker = 0
                    // console.log('materialIDArray :' + materialIDArray)
                    // console.log('supplierIDArray :' + supplierIDArray)
                    // console.log('supplierMaterialIDArray :' + supplierMaterialIDArray)
                    // console.log('selectedMaterialID :' + selectedMaterialID)
                    for(var i = 0; i < materialIDArray.length; i++){
                        for(var ii = 0; ii < supplierIDArray.length; ii++){
                            if(checker == 0){
                                var option = document.createElement('option')
                                option.value = ''
                                option.disabled = true
                                option.selected = true
                                option.hidden = true
                                var optionTextNode = document.createTextNode("None")
                                option.appendChild(optionTextNode)
                                select.appendChild(option)
                                i--
                                checker++
                                break
                            }  
                            //console.log(materialIDArray[i])                            
                            if(supplierMaterialIDArray[i] == supplier_id.value){
                                //console.log(99)
                                checker = 1 // 1 is true, 2 is false
                                for(var iii = 0; iii < selectedMaterialID.length; iii++){
                                    //console.log(materialIDArray[i] != selectedMaterialID[iii])
                                    if(materialIDArray[i] != selectedMaterialID[iii]){                                                                
                                        checker = 1                                                                                                                                                                                                                                                                                                                                                                                                                            
                                    }
                                    else{
                                        checker = 2
                                        break
                                    }                                                                    
                                }
                                if(checker == 1){
                                    var option = document.createElement('option')
                                    option.value = materialIDArray[i]
                                    var optionTextNode = document.createTextNode(materialNumberArray[i])
                                    option.appendChild(optionTextNode)
                                    select.appendChild(option) 
                                    break    
                                }                                
                            }                                                                                                                                                                                         
                        }
                    }
                    div1.appendChild(select)
                 
                    // var button = document.createElement('button')
                    // button.type = 'button'
                    // button.id = 'close' + counter
                    // button.style.position = 'absolute'
                    // button.style.right = '10%'
                    // button.className = 'close'
                    // var icon = document.createElement('i')
                    // icon.className = 'fas fa-times'

                    // button.appendChild(icon)
                    // div1.appendChild(button)                    

                    // add_button.previousElementSibling.appendChild(div1)

                    
                    

                    var div2 = document.createElement('div')
                    div2.style.height = '100%'
                    div2.style.width = '100%'
                    div2.style.display = 'flex'
                    div2.style.alignItems = 'center'
                    div2.style.marginTop = '20px'

                    var label2 = document.createElement('label')
                    label2.setAttribute('for', 'material_qty' + counter)
                    label2.className = 'form-label text-white'
                    label2.style.width = '150px'
                    var label2TextNode = document.createTextNode('Quantity Purchased: ')
                    label2.appendChild(label2TextNode)
                    div2.appendChild(label2)

                    var input = document.createElement('input')
                    input.type = 'number'
                    input.style.width = '150px'
                    input.id = 'material_qty' + counter
                    input.className = 'form-control quantity_purchased'
                    input.name = 'material_qty' + counter
                    input.step = '1'
                    input.min = '0'
                    input.value = 0
                    div2.appendChild(input)
                    var div3 = document.createElement('div')                    
                    div3.appendChild(div1)
                    div3.appendChild(div2)                    
                    add_button.previousElementSibling.appendChild(div3)               

                    costs.push(0) 
                    var quantity_purchased = document.querySelectorAll('.quantity_purchased') 
                    var material_id = document.querySelectorAll('.material_id')
                    // var button_close = document.querySelectorAll('.close')                     
                    //     button_close.forEach(function(btn_close){
                    //         btn_close.addEventListener('click', function(){  
                    //             totalCost.value = 0
                    //             selectedMaterialID[parseInt(btn_close.id[btn_close.id.length - 1])]  = 0                                                       
                    //             costs[parseInt(btn_close.id[btn_close.id.length - 1])] = 0
                    //             costs.forEach(function(cost){
                    //                 totalCost.value = parseInt(totalCost.value) + parseInt(cost) 
                    //             })
                    //             if(btn_close.parentElement.parentElement != null){
                    //                 btn_close.parentElement.parentElement.remove()
                    //             }                                                                    
                    //             console.log(costs)                                                                                                                                                                                                                                                           
                    //         })                    
                    //     }) 
                    material_id.forEach(function(materialID){                        
                        console.log(costs)
                        materialID.addEventListener('change', function(){   
                                                      
                            // console.log(selectedMaterialID[parseInt(materialID.id[materialID.id.length - 1])] == null)
                            // console.log(materialID.id[materialID.id.length - 1])     
                            if(selectedMaterialID[parseInt(materialID.id[materialID.id.length - 1])] != null){
                                selectedMaterialID[parseInt(materialID.id[materialID.id.length - 1])] = materialID.value
                                //console.log(selectedMaterialID)
                                for(var i = parseInt(materialID.id[materialID.id.length - 1]); i < selectedMaterialID.length; i++){
                                    if(i != parseInt(materialID.id[materialID.id.length - 1])){
                                        selectedMaterialID[i] = 0; 
                                    }                                                                                            
                                }
                            }
                            else{
                                selectedMaterialID.push(materialID.value)
                            }
                            
                              
                            //console.log(selectedMaterialID)
                            totalCost.value = 0                   
                            materialID.parentElement.nextElementSibling.lastElementChild.value = 0                            
                            //costs[parseInt(materialID.id[materialID.id.length - 1])] = 0  
                            //console.log(parseInt(materialID.id[materialID.id.length - 1]))   
                            //console.log(costs.length)                       
                            for(var i = parseInt(materialID.id[materialID.id.length - 1]); i < costs.length; i++){
                                costs[i] = 0
                                if(i != costs.length - 1 && materialID.parentElement.parentElement.nextElementSibling != null){                                    
                                    selectedMaterialID[parseInt(materialID.id[materialID.id.length - 1])] = 0
                                    materialID.parentElement.parentElement.nextElementSibling.remove()
                                }                                
                            }                        
                            costs[parseInt(materialID.id[materialID.id.length - 1])] = 0
                            // costs.forEach(function(cost){
                            //     totalCost.value = parseInt(totalCost.value) + parseInt(cost)
                            // })                                                                                            
                                                   
                            console.log(costs)
                            //console.log(materialID.parentElement.parentElement)
                        })
                    })
                                  
                    quantity_purchased.forEach(function(qty_purchased){                                                              
                        qty_purchased.addEventListener('change', function(){   
                            totalCost.value = 0
                            // last change
                            if(qty_purchased.value < 0 || qty_purchased.value == '' || qty_purchased.value == 0 || qty_purchased.parentElement.previousElementSibling.lastElementChild.value == null){                                    
                                qty_purchased.value = 0   
                                totalCost.value = 0                             
                                add_button.style.display = 'none'
                                for(var i = parseInt(qty_purchased.id[qty_purchased.id.length - 1]); i < costs.length; i++){
                                    costs[i] = 0
                                    if(i != costs.length - 1 && qty_purchased.parentElement.parentElement.nextElementSibling != null){                                        
                                        selectedMaterialID[parseInt(qty_purchased.id[qty_purchased.id.length - 1])] = 0
                                        qty_purchased.parentElement.parentElement.nextElementSibling.remove()
                                    }                                
                                }                                                                                                                                                                                       
                            }
                            
                            else{                                 
                                qty_purchased.value = parseInt(qty_purchased.value)
                                if(qty_purchased.parentElement.previousElementSibling.firstElementChild.nextElementSibling.childElementCount == 2){
                                    add_button.style.display = 'none'                                                                    
                                }
                                else{
                                    add_button.style.display = 'block'                                                                    
                                }                                                                  
                                
                                // qty_purchased.parentElement.previousElementSibling.lastElementChild.previousElementSibling.value
                            }
                            for(var i = 0; i < materialIDArray.length; i++){                                                                                                                      
                                if(materialIDArray[i] == parseInt(qty_purchased.parentElement.previousElementSibling.lastElementChild.value)){                                                                                
                                    costs[parseInt(qty_purchased.id[qty_purchased.id.length - 1])] = Math.round(qty_purchased.value) * parseFloat(materialPriceArray[i])
                                    costs.forEach(function(cost){
                                        totalCost.value = parseFloat(totalCost.value) + parseFloat(cost)
                                    })                                                                                       
                                    break                                                                                                                                                                
                                }                                                                                                                        
                            } 
                            // console.log(materialIDArray)
                            // console.log(materialPriceArray)
                             console.log(costs)
                        //console.log(totalCost2) 
                        })                    
                    })   
                    counter++                                       
                })
                 
                // <div style="display: flex; align-items:center; margin-top:20px; position: relative;">
                //                 <label for="material_id" class="form-label text-white" style="margin-right:60px">Material ID: </label> 
                //                 <select id="material_id" name="material_id" class="form-select" style="width: 100px;" >
                                                                                                         
                //                 </select>
                //                  <button type="button" style="position: absolute; right:10%"><i class="fas fa-times"></i></button>
                //             </div> 

                // <div style="height:100%; width:100%; display: flex; align-items:center;">
                //                 <label style="width: 150px" for="material_qty" class="form-label text-white">Quantity Purchased: </label>
                //                 <input type="number" style="width: 150px" id="material_qty" class="form-control" name="material_qty" step="1" min="0">
                //             </div>  

            
            
            
            
            
           
        });
    </script>
@endsection