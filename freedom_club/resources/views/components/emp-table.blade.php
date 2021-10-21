@php
    use ourcodeworld\NameThatColor\ColorInterpreter;
    use App\Models\SuppTransaction;
    use App\Models\Supplier;
    $color = new ColorInterpreter();        
@endphp
<style>
    tr th{
        font-family: Montserrat;
    }
    td{
        font-family: Bahnschrift;
    }
</style>
@if ($title == 'Edit Supplier')
    <table id="example" class="hover" style="width: 100%;">            
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($dataCollection as $supplier)
                <tr>                                                            
                    @if (!SuppTransaction::where('supplier_id', $supplier->id)->first())
                        <td style="display: flex; align-items:center;">
                        <form action="{{ url('/dashboard/suppliers/' .$supplier->id) }}" method="POST" id="supplier_delete_form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="margin-right: 20px" id="supplier_delete"><i class="fas fa-trash"></i></button>
                            <input type="text" class="display:none;" name="supplier_id" value="{{ $supplier->id }}" hidden>  
                        </form>
                    @else
                    <td style="display: flex; justify-content:center;">
                    @endif                                              
                        <a href="" @if(SuppTransaction::where('supplier_id', $supplier->id)->first()) style="display: flex; justify-content:center;" @endif id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$supplier->id }}">{{ $supplier->supp_name }}</a>
                    </td>                                                                    
                    <td>{{ $supplier->supp_contactNum }}</td>                        
                    <td>{{ $supplier->supp_email }}</td>                        
                    <td>{{ $supplier->created_at }}</td>                        
                    <td>{{ $supplier->updated_at }}</td>                                       
                </tr>        
                <div class="modal fade" id="{{ 'id'.$supplier->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                        <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ url('/dashboard/suppliers/'.$supplier->id) }}" method="POST" id="edit_credential_form">
                            @csrf                   
                            @method("PUT")                                                                                                                                              
                            <div class="modal-body" style="height:300px">
                                <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around; align-items:center;">
                                    <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                        <label style="width: 170px" for="supplier_name" class="form-label text-white">Supplier Name: </label>
                                        <input type="text" class="form-control" name="supplier_name" placeholder="Name" value="{{ $supplier->supp_name }}">
                                    </div>                    
                                    <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                        <label style="width: 180px" for="supplier_contactNumber" class="form-label text-white">Contact Number: </label>
                                        <input type="tel" class="form-control" name="supplier_contactNumber" placeholder="09*********" value="{{ $supplier->supp_contactNum }}">
                                    </div>                    
                                    <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                        <label style="width: 150px" for="supplier_email" class="form-label text-white">Email Address: </label>
                                        <input type="email" class="form-control" name="supplier_email" placeholder="Email" value="{{ $supplier->supp_email }}">
                                    </div>  
                                </div>                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary" value="Edit Credentials" id="edit_credentials">
                            </form>
                    </div>
                    </div>
                </div> 
            @endforeach
        </tbody>           
    </table>
@endif

@if ($title == 'Item List')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection as $material)
            <tr>
                <td style="display: flex; align-items:center;">
                    <form action="{{ url('/dashboard/materials/' .$material->id) }}" method="POST" id="item_delete_form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="margin-right: 20px"><i class="fas fa-trash" id="item_delete"></i></button>
                        <input type="text" name="material_id" value="{{ $material->id }}" hidden>  
                    </form>
                    <a href="" id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$material->id }}">{{ $material->material_number }}</a>
                </td>                        
                <td>{{ $material->supplier_id }}</td>                        
                <td>{{ $material->material_type }}</td>                        
                <td>{{ $material->material_size }}</td>                        
                <td>{{ $color->name($material->material_color)['name'] }}</td>                                                                
                <td>{{ $material->material_price }}</td>                                       
                <td>{{ $material->created_at }}</td>                                       
                <td>{{ $material->updated_at }}</td>                                       
            </tr>        
            <div class="modal fade modal_item_list" id="{{ 'id'.$material->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                    <div class="modal-header">
                    <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('/dashboard/materials/' .$material->id) }}" method="POST" id="edit_item_form">
                        @csrf                   
                        @method("PUT")                                                                                                                                              
                        <div class="modal-body" style="height:300px">
                            <input type="text" hidden value="{{ $material->id }}">
                            <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around; align-items:center;">
                                <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                    <label style="width: 170px" for="material_type" class="form-label text-white">Material Type:</label>
                                    <input type="text" disabled class="form-control material_type1" id='material_type1' name="material_type" value="{{ $material->material_type }}" placeholder="(Ex: Shirts, hats, bags, and etc.)" >
                                </div>                    
                                <div style="height:100%; width:100%; display: flex; align-items:center;">
                                    <label style="width: 170px" for="material_size" class="form-label text-white">Material Size: </label>
                                    <select name="material_size" class="form-select mb-3" id="material_size1" style="width: 100px;" >                                    
                                        <option value="None" disabled hidden selected>None</option>                                
                                        <option value="16">16</option>                                                                                                         
                                        <option value="18">18</option>                                                                                                         
                                        <option value="20">20</option>                                                                                                         
                                        <option value="22">22</option>                                                                                                         
                                        <option value="XXS">XXS</option>
                                        <option value="XS">XS</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                        <option value="3XL">3XL</option>                                                                   
                                    </select>
                                </div>                     
                                <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                    <label style="width: 170px" for="material_color" class="form-label text-white">Material Color: </label>                                    
                                    <input type="color" class="form-control" name="material_color" value="{{ $material->material_color }}" placeholder="(Ex: Black, Red, Fuschia, and etc.)">
                                </div>                                                                                                                    
                                <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                    <label style="width: 150px" for="material_price" class="form-label text-white">Material Price: </label>
                                    <input type="number" class="form-control" name="material_price" value="{{ $material->material_price }}" placeholder="(Ex: 50.00, 75.15, and etc.)" min="0" step=".01">
                                </div>  
                            </div>                
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Edit Record" id="edit_item">
                        </form>
                </div>
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif

@if ($title == 'Edit Employee')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection as $employee)        
        @if (auth()->user()->id != $employee->user_id)
            <tr>
                <td style="display: flex; align-items:center;">
                    <form action="{{ url('/dashboard/employees/' .$employee->user_id) }}" method="POST" id="employee_delete_form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="margin-right: 20px" id="employee_delete"><i class="fas fa-trash"></i></button>
                        <input type="text" name="user_id" value="{{ $employee->user_id }}" hidden>  
                    </form>
                    <a href="" id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$employee->user_id }}">{{ $employee->user_id }}</a>
                </td>                                                               
                <td>{{ $employee->emp_firstName }}</td>                        
                <td>{{ $employee->emp_lastName }}</td>                        
                <td>{{ $employee->emp_email }}</td>                        
                <td>{{ $employee->emp_gender }}</td>                        
                <td>{{ $employee->emp_birthDate }}</td>                                       
                <td>{{ $employee->created_at }}</td>                                       
                <td>{{ $employee->updated_at }}</td>                                       
            </tr> 
            @endif  
     
            <div class="modal fade edit_employee" id="{{ 'id'.$employee->user_id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                    <div class="modal-header">
                    <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ url('/dashboard/employees/' .$employee->user_id) }}" method="POST" id="employee_edit_form">
                        @csrf                   
                        @method("PUT")                                                                                                                                              
                        <div class="modal-body" style="height:300px">
                            <input type="text" class="emp_id" hidden value="{{ $employee->user_id }}">
                            <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around; align-items:center;">
                                <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                    <label style="width: 170px" for="emp_firstName" class="form-label text-white">First Name:</label>
                                    <input type="text" disabled class="form-control" name="emp_firstName" value="{{ $employee->emp_firstName }}" placeholder="(Ex: Shirts, hats, bags, and etc.)" >
                                </div>                    
                                <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                    <label style="width: 170px" for="emp_lastName" class="form-label text-white">Last Name: </label>
                                    <input type="text" disabled class="form-control" name="emp_lastName" value="{{ $employee->emp_lastName }}" placeholder="(Ex: S, M, L, 18, 20, and etc.)">
                                </div>                    
                                <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                    <label style="width: 170px" for="emp_email" class="form-label text-white">Email : </label>
                                    <input type="email" disabled class="form-control" name="emp_email" value="{{ $employee->emp_email }}" placeholder="(Ex: Black, Red, Fuschia, and etc.)">
                                </div>                    
                                <div style="height:100%; width:100%; display: flex; align-items:center;">
                                    <label style="width: 220px" for="emp_gender" class="form-label text-white">Gender: </label>
                                    <select disabled name="emp_gender" class="emp_gender">
                                        @if ($employee->emp_gender == "Male")
                                            <option value="Male" selected>M</option>
                                            <option value="Female">F</option>
                                        @else
                                            <option value="Male">M</option>
                                            <option value="Female" selected>F</option>
                                        @endif
                                    </select>
                                </div>                                                                 
                                <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                    <label style="width: 150px" for="emp_birthDate" class="form-label text-white">Birthdate: </label>
                                    <input disabled type="date" class="form-control" name="emp_birthDate" value="{{ $employee->emp_birthDate }}" placeholder="(Ex: 50.00, 75.15, and etc.)">
                                </div>
                                <div style="height:100%; width:100%; display: flex; align-items:center;">
                                    <label style="width: 220px" for="role" class="form-label text-white">Position: </label>
                                    <select name="role" class="role" style="width:200px">

                                    </select>
                                </div>  
                            </div>                
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Edit Position" id="employee_edit">
                        </form>
                </div>
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif


@if ($title == 'Edit Transaction')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[0] as $transaction)        
            <tr>                
                @if ($transaction->datePaid == null)
                    <td style="display: flex; align-items:center;">
                        <form action="{{ url('/dashboard/purchases/'.$transaction->id) }}" method="POST">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-danger" style="margin-right: 62px"><i class="fas fa-trash"></i></button>
                            <input type="text" name="user_id" value="{{ $transaction->id }}" hidden>   
                        </form>                                                           
                        <a href="" style="text-align: center" id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$transaction->id }}">{{ $transaction->receipt_num }}</a>
                @else
                    <td style="display: flex; justify-content:center;">
                        {{ $transaction->receipt_num }}
                @endif                    
                </td>                                                                                                      
                <td>{{ Supplier::find($transaction->supplier_id)->supp_name }}</td>                        
                <td>{{ $transaction->totalCost }}</td>
                <td>
                    @if ($transaction->datePaid)
                        {{ $transaction->datePaid }}
                    @else
                        NOT PAID
                    @endif                    
                </td>                                                                            
                <td>{{ $transaction->created_at }}</td>                                       
                <td>{{ $transaction->updated_at }}</td>                                       
            </tr> 
                  
            <div class="modal fade" id="{{'id'.$transaction->id}}" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                        </div>
                            <form action="{{ url('/dashboard/purchases/'.$transaction->id) }}" method="POST">
                            @csrf 
                            @method("PUT")                                                                                                                                                                
                                <div class="modal-body">                                                
                                    <div style="display: flex; margin-top:20px; width:80%; justify-content:space-between;">
                                        <label for="datePaid" class="form-label text-white">Is the transaction paid? </label>                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="datePaid" id="exampleRadios1" value="true">
                                            <label class="form-check-label" for="exampleRadios1">
                                              Yes
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="datePaid" id="exampleRadios2" value="false" checked>
                                            <label class="form-check-label" for="exampleRadios2">
                                              No
                                            </label>
                                          </div>    
                                    </div> 
                                </div>                
                            
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Add Record">                    
                                </div>
                        </form>
                    </div>
                    </div>        
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Edit Item List')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[0] as $item)        
            <tr>
                
                    @if (!$dataCollection[3]::find($item->supp_transactions_id)->datePaid)
                    <td style="display: flex; align-items:center;">
                    <form action="{{ url('/dashboard/purchases/items/'.$item->id) }}" method="POST"> 
                        @csrf
                        @method("DELETE")                   
                        <button type="submit" class="btn btn-danger" style="margin-right: 20px"><i class="fas fa-trash"></i></button>
                        <input type="text" name="user_id" value="{{ $item->supp_transactions_id }}" hidden> 
                    </form>                     
                    <a href="" id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$item->supp_transactions_id }}">{{ SuppTransaction::find($item->supp_transactions_id)->receipt_num }}</a>
                    @else
                    <td style="display: flex; justify-content:center;">
                    <a id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$item->supp_transactions_id }}">{{ SuppTransaction::find($item->supp_transactions_id)->receipt_num }}</a>  
                    @endif
                    
                </td>                                                                                                     
                <td>{{ $item->material_number }}</td>                        
                <td>{{ $item->material_type }}</td>                        
                <td>{{ $item->material_size }}</td>                                                                            
                <td>{{ $color->name($item->material_color)['name'] }}</td>                                                                            
                <td>{{ $item->material_qty }}</td>                                                                            
                <td>{{ $item->material_price }}</td>                                                                            
                <td>{{ $item->created_at }}</td>                                       
                <td>{{ $item->updated_at }}</td>                                       
            </tr> 
                  
            <div class="modal fade" id="{{'id'.$item->supp_transactions_id}}" class="edit_item_list" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                        </div>
                            <form action="{{ url('/dashboard/purchases/items/'.$item->id) }}" method="POST">
                            @csrf                                                       
                            @method("PUT")                                                                                                                                     
                            <div class="modal-body">   
                                <input type="text" value="{{ $item->supp_transactions_id }}" hidden name="supp_transactions_id">                             
                                <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around;">
                                    <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                        <label style="width: 170px" for="receipt_num" class="form-label text-white">Receipt #: </label>                                        
                                        <input type="text" disabled class="form-control" value="{{ $dataCollection[3]::find($item->supp_transactions_id)->receipt_num }}" name="receipt_num" placeholder="Receipt #" >
                                    </div>                    
                                    <div style="display: flex; align-items:center; margin-top:20px">
                                        <label for="supplier_id" class="form-label text-white" style="margin-right:60px">Supplier ID: </label> 
                                        <select disabled id="supplier_id99" name="supplier_id" class="form-select" style="width: 100px;" >                                           
                                            <option value="{{ $dataCollection[3]::find($item->supp_transactions_id)->supplier_id }}" disabled hidden selected>{{ $dataCollection[3]::find($item->supp_transactions_id)->supplier_id }}</option>                                    
                                            @foreach ($dataCollection[1] as $supplier)
                                                @if ($supplier->material->first())
                                                    <option value="{{ $supplier->id }}">{{ $supplier->id }}</option>
                                                @endif                                                                                                                                                                  
                                            @endforeach                                                                      
                                        </select>
                                    </div>                   
                                    <div style="display: flex; align-items:center; margin-top:20px;">                                
                                        <label for="material_id" class="form-label text-white" style="margin-right:60px">Material ID: </label>                                 
                                        <select id="material_id99" name="material_id" class="form-select" style="width: 100px;" >
                                            
                                                                                                                 
                                        </select>
                                        
                                    </div>       
        
                                    <div style="height:100%; width:100%; display: flex; align-items:center; margin-top: 20px">
                                        <label style="width: 150px" for="material_qty" class="form-label text-white">Quantity Purchased: </label>
                                        <input type="number" style="width: 150px;" value="{{ $item->material_qty }}" id="material_qty99" class="form-control quantity_purchased1" name="material_qty" step="1" min="0">
                                    </div> 
                                                                                 
                                    <div style="height:100%; width:100%; display: none; align-items:center;">
                                        <label style="width: 150px" for="totalCost" class="form-label text-white">Total Cost: </label>
                                        <input id="totalCost99" type="number" disabled class="form-control" name="totalCost" step=".01" min="0">
                                    </div>                              
                                                                        
                                </div>                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary" value="Edit Record">                    
                            </div>
                        </form>
                    </div>        
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Edit Shipment')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[0] as $shipment)        
        @php
        $check = true;
            foreach($dataCollection[3] as $item){                
                if($item->supp_transactions_id == $shipment->supp_transactions_id && $item->material_qty == 0){
                    $check = false;
                }
                else if($item->supp_transactions_id == $shipment->supp_transactions_id && $item->material_qty != 0){
                    $check = true;
                    break;
                }
            }            
        @endphp
            <tr>  
                @if ($shipment->dateReceived == null && $check)
                <td style="display: flex; justify-content:center;">                    
                        <input type="text" name="user_id" value="{{ $shipment->id }}" hidden>                                         
                    <a href="" id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$shipment->id }}">{{ $shipment->receipt_number }}</a>
                </td>
                @else
                <td style="display: flex; justify-content:center;">
                    {{ $shipment->receipt_number }}                                                                                                                              
                </td>
                @endif                                                 
                <td>
                    @if ($shipment->dateReceived)
                        {{ $shipment->dateReceived }}
                    @else
                        Pending
                    @endif
                    
                </td>                        
                <td>{{ $shipment->shipping_fee }}</td>                        
                <td>{{ $shipment->created_at }}</td>                                                                            
                <td>{{ $shipment->updated_at }}</td>                                                                                                                               
            </tr> 
                  
            <div class="modal fade" id="{{'id'.$shipment->id}}" class="edit_item_list" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                        </div>
                            <form action="{{ url('/dashboard/shipments/'.$shipment->id) }}" method="POST">
                            @csrf                                                       
                            @method("PUT")                                                                                                                                     
                            <div class="modal-body">                               
                                {{-- <input type="text" value="{{ $shipment->id }}" hidden name="supp_transactions_id">                              --}}
                                <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around;">
                                    <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                        <label style="width: 170px" for="shipment_id" class="form-label text-white">Shipment ID: </label>                                        
                                        <input type="text" disabled class="form-control" value="{{ $shipment->id }}" name="shipment_id" placeholder="Shipment ID" >
                                    </div>                    
                                    <div style="display: flex; align-items:center; margin-top:20px">
                                        <label for="supp_transactions_id" class="form-label text-white" style="margin-right:60px">Transaction ID: </label> 
                                        <input type="text" disabled class="form-control" value="{{ $shipment->supp_transactions_id }}" name="supp_transactions_id" placeholder="Transaction ID" >
                                    </div>                   
                                    <div style="display: flex; align-items:center; margin-top:20px;">                                
                                        <label for="shipping_fee" class="form-label text-white" style="margin-right:60px">Shipping Fee: </label>                                 
                                        <input type="text" disabled class="form-control" value="{{ $shipment->shipping_fee }}" name="shipping_fee" placeholder="Shipping Fee" >                                        
                                    </div> 
                                    <br>                                                                                                                                     
                                    <div style="display: flex; width:80%; justify-content:space-between;">
                                        <label for="">Transfer Items to Inventory? </label>                                        
                                        <div class="form-check">
                                            <input class="form-check-input radio" type="radio" name="isReceived" id="exampleRadios1" value="true" checked>
                                            <label class="form-check-label" for="exampleRadios1">
                                              Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio" type="radio" name="isReceived" id="exampleRadios2" value="false">
                                            <label class="form-check-label" for="exampleRadios2">
                                                No
                                            </label>
                                        </div>    
                                    </div> 
                                    <br>                                     
                                    <div hidden>
                                        <hr> 
                                        <div style="display:flex; width:80%; justify-content:space-between;" class="slot {{ $shipment->id }}">
                                            <label for="">Item to Replace/Refund:</label>
                                            <select name="material_id" id="" class="form-select" style="width: 50%" disabled>
                                                @foreach ($dataCollection[3] as $material_transaction)
                                                    @if ($shipment->supp_transactions_id == $material_transaction->supp_transactions_id && $material_transaction->material_qty > 0)
                                                        <option value="{{ $material_transaction->material_id }}">{{ $material_transaction->material_number }}</option>
                                                    @endif
                                                @endforeach
                                            </select>                                                                                
                                        </div>
                                        <div style="display:flex; width:80%; justify-content:space-between;">
                                            <label for="">Quantity:</label>
                                            <input type="number" id="return_qty" name="quantity" disabled>
                                        </div> 
                                        <div style="display:flex; width:80%; justify-content:space-between;">      
                                            <label for="">Select one:</label>                                                                            
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="return" id="exampleRadios1" value="Refund" disabled>
                                                <label class="form-check-label" for="exampleRadios1">
                                                  Refund
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="return" id="exampleRadios2" value="Replace" checked disabled>
                                                <label class="form-check-label" for="exampleRadios2">
                                                    Replace
                                                </label>
                                            </div>  
                                        </div> 
                                    </div>                                                                                                                                                                                                      
                                </div>                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary" value="Edit Record">                    
                            </div>
                        </form>
                    </div>        
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'View Material Details')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[0] as $stock)        
            <tr>  
               
                <td style="display: flex; justify-content:center;">                     
                                           
                        {{-- <button type="submit" class="btn btn-danger" style="margin-right: 20px"><i class="fas fa-trash"></i></button> --}}
                        <input type="text" name="user_id" value="{{ $stock->id }}" hidden> 
                                       
                    <a>{{ $stock->stock_number }}</a>
                </td>
                
                            
                                                                                                                     
                                       
                <td><a href="" id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$stock->material_id }}">{{ $stock->material_number }}</a></td> 
                <td>{{ $stock->stock_type }}</td>                        
                <td>{{ $stock->stock_size }}</td>                        
                <td>{{ $color->name($stock->stock_color)['name'] }}</td>                        
                <td>{{ $stock->stock_qty }}</td>                        
                <td>{{ $stock->stock_price }}</td>                                                                            
                <td>{{ $stock->created_at }}</td>                                                                                                                               
                <td>{{ $stock->updated_at }}</td>                                                                                                                               
            </tr> 
                  
            <div class="modal fade" id="{{'id'.$stock->material_id}}" class="edit_item_list" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                        </div>
                            <form action="{{ url('/dashboard/shipments/'.$stock->id) }}" method="POST">
                            @csrf                                                       
                            @method("PUT")                                                                                                                                     
                            <div class="modal-body" style="height: 300px">                               
                                <input type="text" value="" hidden name="supp_transactions_id">                             
                                <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around; align-items:center;">
                                    <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                        <label style="width: 170px" for="supplier_id" class="form-label text-white">Supplier ID:</label>
                                        <input disabled type="text" class="form-control" name="supplier_id" value="{{ $stock->material->supplier_id }}" placeholder="(Ex: Shirts, hats, bags, and etc.)" >
                                    </div>                    
                                    <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                        <label style="width: 170px" for="stock_size" class="form-label text-white">Stock Size: </label>
                                        <input disabled type="text" class="form-control" name="stock_size" value="{{ $stock->stock_size }}" placeholder="(Ex: S, M, L, 18, 20, and etc.)">
                                    </div>                    
                                    <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                        <label style="width: 170px" for="stock_color" class="form-label text-white">Stock Color: </label>                                    
                                        <input disabled type="color" class="form-control" name="stock_color" value="{{ $stock->stock_color }}" placeholder="(Ex: Black, Red, Fuschia, and etc.)">
                                    </div>                    
                                    <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                        <label style="width: 220px" for="stock_qty" class="form-label text-white">Stock Quantity: </label>
                                        <input disabled type="number" class="form-control" name="stock_qty" value="{{ $stock->stock_qty }}" placeholder="" min="0">
                                    </div>                                                                 
                                    <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                        <label style="width: 150px" for="stock_price" class="form-label text-white">Stock Price: </label>
                                        <input disabled type="number" class="form-control" name="stock_price" value="{{ $stock->stock_price }}" placeholder="(Ex: 50.00, 75.15, and etc.)" min="0" step=".01">
                                    </div>  
                                </div>              
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                {{-- <input type="submit" class="btn btn-primary" value="Edit Record">                     --}}
                            </div>
                        </form>
                    </div>        
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'View Transfer Request')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[2] as $transfer)        
            <tr>  
               
                <td style="display: flex; justify-content:center;">      
                    
                        {{-- @php
                            dd($transfer->confirmed == false);
                        @endphp --}}
                        {{-- <button type="submit" class="btn btn-danger" style="margin-right: 20px"><i class="fas fa-trash"></i></button> --}}
                        @if ($transfer->confirmed == null)
                            <input type="text" name="user_id" value="{{ $transfer->id }}" hidden>                 
                            <a href="" id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$transfer->id }}">{{ $transfer->transfer_number }}</a>
                        @else
                            {{ $transfer->transfer_number }}
                        @endif
                </td>                                                                                                                                            
                <td>{{ $transfer->stock_number }}</td>
                <td>{{ $transfer->product_number }}</td> 
                <td>{{ $transfer->quantity }}</td>                       
                <td>@if ($transfer->confirmed == 'False' )
                    NOT CONFIRMED
                @elseif($transfer->confirmed === null)                    
                    PENDING
                @else
                    CONFIRMED
                @endif</td>                                        
                <td>{{ $transfer->created_at }}</td>                                                                                                                               
                <td>{{ $transfer->updated_at }}</td>                                                                                                                               
            </tr> 
                  
            <div class="modal fade" id="{{'id'.$transfer->id}}" class="edit_item_list" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                        </div>
                            <form action="{{ url('/dashboard/transfer/'.$transfer->id) }}" method="POST">
                            @csrf                                                       
                            @method("PUT")                                                                                                                                  
                            <div class="modal-body">
                                {{-- <input type="text" value="" hidden name="supp_transactions_id">--}}
                                <div style="height:100%; width:100%; display: flex;">
                                    <label style="width: 120px" for="transfer_id" class="form-label text-white">Transfer #: </label>                                        
                                    <input type="text" hidden class="form-control" value="{{ $transfer->id }}" name="transfer_id" placeholder="Transfer ID" >
                                    {{ $transfer->transfer_number }}
                                </div>                    
                                <div style="display: flex; margin-top:20px">
                                    <label for="stock_id" class="form-label text-white" style="margin-right:60px">Stock ID: </label> 
                                    <input type="text" hidden class="form-control" value="{{ $transfer->stock_id }}" name="stock_id" placeholder="Stock ID" >
                                    {{ $transfer->stock_number }}
                                </div>                   
                                <div style="display: flex; margin-top:20px;">                                
                                    <label for="product_id" class="form-label text-white" style="margin-right:60px">Product ID: </label>                                 
                                    <input type="text" hidden class="form-control" value="{{ $transfer->product_id }}" name="product_id" placeholder="Product ID" >   
                                    {{ $transfer->product_number }}                                     
                                </div>  
                                <div style="display: flex; margin-top:20px;">                                
                                    <label for="quantity" class="form-label text-white" style="margin-right:40px">Quantity: </label>                                 
                                    <input type="number" hidden class="form-control" value="{{ $transfer->quantity }}" name="quantity" placeholder="Quantity" >  
                                    {{ $transfer->quantity }}                                      
                                </div>  
                                <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around; align-items:center; margin-top:20px">        
                                @if ($transfer->confirmed == 0)                                
                                    <div style="display: flex; width:80%; justify-content:space-between; margin-top:20px">
                                        <label for="">Confirm chosen request?</label>                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status1" value="true" checked>
                                            <label class="form-check-label" for="exampleRadios1">
                                              Yes
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status2" value="false">
                                            <label class="form-check-label" for="exampleRadios2">
                                              No
                                            </label>
                                          </div>    
                                    </div>                                  
                                @else                                
                                    <div style="display: flex; width:80%; justify-content:space-between;">
                                        <label for="">Set status to inactive?</label>                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status1" value="true" checked>
                                            <label class="form-check-label" for="exampleRadios1">
                                              Yes
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status2" value="false">
                                            <label class="form-check-label" for="exampleRadios2">
                                              No
                                            </label>
                                          </div>    
                                    </div> 
                                   
                                @endif 
                            </div>                                                                                           
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary" value="Edit Record">                    
                            </div>
                        </form>
                    </div>        
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'View Product Details')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[0] as $product)        
            <tr>       
                @if ($product->prod_status != 1 && ($product->prod_qty < 0 || $product->prod_qty == null))
                <td style="display: flex; align-items:center;">
                    <form action="{{ url('/dashboard/products/'.$product->id) }}" method="POST" id="product_delete_form">
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn btn-danger" style="margin-right: 20px" id="product_delete"><i class="fas fa-trash"></i></button>
                        <input type="text" name="user_id" value="{{ $product->id }}" hidden>   
                    </form>
                    {{ $product->product_number }}                   
                </td>
                @else
                    @if ($product->prod_status != 1 && $product->prod_qty > 0)
                    <td style="display: flex; align-items:center;">
                        <form action="{{ url('/dashboard/products/'.$product->id) }}" method="POST">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-danger" style="margin-right: 20px"><i class="fas fa-trash"></i></button>
                            <input type="text" name="user_id" value="{{ $product->id }}" hidden>   
                        </form> 
                        <a href="" style="text-align: center" id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$product->id }}">{{ $product->product_number }}</a>                       
                    </td> 
                    @else
                    <td style="display: flex; justify-content:center;">
                        <a href="" style="text-align: center" id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$product->id }}">{{ $product->product_number }}</a>
                    </td>                                            
                    @endif                                 
                @endif                     
                
                                                                                                 
                                                                                                   
                <td>{{ $product->prod_name }}</td>                        
                <td>{{ $product->prod_type }}</td>                        
                <td>{{ $product->prod_size }}</td>                        
                <td>
                    @if ($product->prod_color == null)
                        None
                    @else
                        {{ $color->name($product->prod_color)['name'] }}
                    @endif 
                </td>                   
                <td id = 'prod_status'>
                    @if ($product->prod_status == 1)
                        ACTIVE
                    @else
                        INACTIVE
                    @endif                    
                </td>
                <td>{{ $product->prod_qty }}</td>
                <td>{{ $product->prod_price }}</td>
                <td>{{ $product->created_at }}</td>                                       
                <td>{{ $product->updated_at }}</td>                                       
            </tr> 
                  
            <div class="modal fade" id="{{'id'.$product->id}}" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                        </div>
                            <form action="{{ url('/dashboard/products/'.$product->id) }}" method="POST">
                            @csrf 
                            @method("PUT")                                                                                                                                                                
                                <div class="modal-body">                                                
                                    <div style="display: flex; width:80%; justify-content:space-between;">
                                        <label for="">Set status: </label>                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status1" value="true" checked>
                                            <label class="form-check-label" for="exampleRadios1">
                                              Active
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status2" value="false">
                                            <label class="form-check-label" for="exampleRadios2">
                                              Inactive
                                            </label>
                                          </div>    
                                    </div>
                                </div>                                            
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Add Record">                    
                                </div>
                        </form>
                    </div>
                    </div>        
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Edit Expense')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[0] as $expense)        
            <tr>                            
                
                @if ($expense->expense_categories_id == 1)
                <td style="display: flex; justify-content:center;">
                @else
                <td style="display: flex;  align-items:center;">
                    <form action="" method="POST">
                        @method("DELETE")
                        @csrf
                        <button type="submit" class="btn btn-danger" style="margin-right: 18px"><i class="fas fa-trash"></i></button>
                        <input type="text" name="user_id" value="{{ $expense->id }}" hidden>   
                    </form> 
                @endif  
                
                         {{ $expense->id }}
                                                                                                                                             
                </td> 
                @if ($expense->receipt_number)
                <td>{{ $expense->receipt_number }}</td> 
                @else
                  <td>NONE</td>
                @endif                
                @if ($expense->expense_categories_id == 1)
                <td>{{ DB::table('expense_categories')->where('id', $expense->expense_categories_id)->first()->category_name }}</td>
                @else                
                <td>{{ $expense->expense_categories_id }}</td> 
                @endif 
                <td>{{ $expense->description }}</td>                
                <td>{{ $expense->computed_expenses }}</td>                                                               
                <td>{{ $expense->created_at }}</td>                                                                       
                <td>{{ $expense->updated_at }}</td>                                                                                                                                       
            </tr> 
                  
            <div class="modal fade" id="{{'id'.$expense->id}}" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                        </div>
                            <form action="{{ url('/dashboard/expenses/'.$expense->id) }}" method="POST">
                            @csrf 
                            @method("PUT")                                                                                                                                                                
                                <div class="modal-body">                                                
                                    <div style="display: flex; width:80%; justify-content:space-between;">
                                        <label for="">Set status: </label>                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status1" value="true" checked>
                                            <label class="form-check-label" for="exampleRadios1">
                                              Active
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status2" value="false">
                                            <label class="form-check-label" for="exampleRadios2">
                                              Inactive
                                            </label>
                                          </div>    
                                    </div>
                                </div>                                            
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Add Record">                    
                                </div>
                        </form>
                    </div>
                    </div>        
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Update Order')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[0] as $checkout)        
            <tr>                                            
                <td>                        
                    @if ($checkout->status === null && $checkout->receipt_number === null)
                        <a href="" data-bs-toggle="modal" data-bs-target="#id{{ $checkout->invoice_number }}">{{ $checkout->invoice_number }}</a>
                    @else
                        {{ $checkout->invoice_number }}
                    @endif
                </td>
                @if ($checkout->receipt_number === null)
                    <td>NONE</td>
                @else
                    <td>{{ $checkout->receipt_number }}</td>
                @endif 
                <td>{{ $checkout->acc_name }}</td>                                
                <td>{{ $checkout->acc_num }}</td>
               
                <td>{{ $checkout->payment_method }}</td> 
                <td>
                    @if ($checkout->shipping_service == null)
                        NONE
                    @else
                        {{ $checkout->shipping_service }}
                    @endif
                </td>               
                <td>
                    @if ($checkout->tracking_number == null)
                        NONE
                    @else
                        {{ $checkout->tracking_number }}
                    @endif
                </td>
                <td>
                    @if ($checkout->status === null)
                        PENDING
                    @elseif($checkout->status == 0)                
                        NOT CONFIRMED
                    @else
                        CONFIRMED
                    @endif                    
                </td>                    
                <td>{{ $checkout->total }}</td>                                                          
                <td>{{ $checkout->created_at }}</td>
                @if ($checkout->dateReceived === null)
                    <td>PENDING</td>                    
                @else
                    {{ $checkout->dateReceived }}
                @endif
                @if ($checkout->receipt_number === null)
                    <td>PENDING</td>  
                @else
                    <td>{{ $checkout->updated_at }}</td>  
                @endif                                                                                                                                                                    
            </tr> 
                  
            <div class="modal fade" id="{{ 'id'.$checkout->invoice_number }}" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                        </div>
                            <form action="{{ url('/dashboard/orders/'.$checkout->invoice_number) }}" method="POST">
                            @csrf
                            @method("PUT")                                                                                                                                                       
                                <div class="modal-body">      
                                    <h4>Order Summary:</h4>
                                    <div style="display: flex; width:100%; justify-content:space-between">
                                        <div style="width:33%">Product Name</div>                                        
                                        <div style="width:33%">Quantity</div>
                                        <div style="width:33%">Subtotal</div>
                                    </div>  
                                    <br>                                                                                                   
                                    @foreach ($dataCollection[2] as $item)
                                    @if ($item->checkout_id == $checkout->id)
                                    <div style="display: flex; width:100%; justify-content:space-between">
                                        <div style="width:33%">{{ $dataCollection[1]->find($item->product_id)->prod_name. '-'.$color->name($dataCollection[1]->find($item->product_id)->prod_color)['name']}}</div>                                        
                                        <div style="width:33%; padding-left:20px">{{ $item->quantity }}</div>
                                        <div style="width:33%; padding-left:20px">{{ $item->subtotal }}</div>
                                    </div>   
                                    <br>
                                    @endif                                        
                                    @endforeach                                                                        
                                    <div style="display: flex; width:77%; justify-content:space-between;">
                                        <div>Shipping Fee</div>
                                        <div>500</div>
                                    </div> 
                                    <br> 
                                    <hr>
                                    <div style="display: flex; width:77%; justify-content:space-between;">
                                        <div>Total</div> 
                                        <div>{{ $checkout->total }}</div>
                                    </div>      
                                    <br>  
                                    <div style="display: flex; justify-content:space-between; width:90%; align-items:center;" class="toggle_ship {{$checkout->id}}" hidden>
                                        <div style="margin-right: 20px; width:50%">
                                            <label for="shipping_service" id="">Shipping Service: </label>
                                            <select name="shipping_service" id="shipping_service" class="form-select" disabled>
                                                <option value="JRS Express">JRS Express</option>
                                                <option value="2GO">2GO</option>
                                                <option value="LBC">LBC</option>
                                            </select>
                                        </div>                                        
                                        <div>
                                            <label for="">Tracking Number:</label>
                                            <input type="text" name="tracking_number" id="tracking_number" disabled>
                                        </div>
                                    </div>    
                                    <br>                                                                                   
                                    <div style="display: flex; width:80%; justify-content:space-between;">                                                                               
                                        <label for="">Set payment status: </label>                                        
                                        <div class="form-check">
                                            <input class="form-check-input radio" type="radio" name="setStatus" id="status1" value="true">
                                            <label class="form-check-label" for="exampleRadios1">
                                              Paid
                                            </label>
                                          </div>                                         
                                          <div class="form-check">
                                            <input class="form-check-input radio" type="radio" name="setStatus" id="status2" value="false" checked>
                                            <label class="form-check-label" for="exampleRadios2">
                                              Not Paid
                                            </label>
                                          </div>                                              
                                    </div>
                                </div>
                                <div>                                    
                                </div>                                            
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Add Record">                    
                                </div>
                        </form>
                    </div>
                    </div>        
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'View Sales')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[0] as $sale)        
            <tr>                                                            
                <td>{{ $sale->sales_category }}</td>                                
                <td>{{ $sale->total }}</td>                                                                                      
                <td>{{ $sale->created_at }}</td>                                                                                                                                                  
                <td>{{ $sale->updated_at }}</td>                                                                                                                                                  
            </tr> 
                  
            {{-- <div class="modal fade" id="{{ 'id'.$checkout->invoice_number }}" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="idLabel">{{ $title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                        </div>
                            <form action="{{ url('/dashboard/orders/'.$checkout->invoice_number) }}" method="POST">
                            @csrf
                            @method("PUT")                                                                                                                                                       
                                <div class="modal-body">      
                                    <h4>Order Summary:</h4>
                                    <div style="display: flex; width:100%; justify-content:space-between">
                                        <div style="width:33%">Product Name</div>                                        
                                        <div style="width:33%">Quantity</div>
                                        <div style="width:33%">Subtotal</div>
                                    </div>  
                                    <br>                                                                                                   
                                    @foreach ($dataCollection[2] as $item)
                                    @if ($item->checkout_id == $checkout->id)
                                    <div style="display: flex; width:100%; justify-content:space-between">
                                        <div style="width:33%">{{ $dataCollection[1]->find($item->product_id)->prod_name. '-'.$color->name($dataCollection[1]->find($item->product_id)->prod_color)['name']}}</div>                                        
                                        <div style="width:33%; padding-left:20px">{{ $item->quantity }}</div>
                                        <div style="width:33%; padding-left:20px">{{ $item->subtotal }}</div>
                                    </div>   
                                    <br>
                                    @endif                                        
                                    @endforeach                                                                        
                                    <div style="display: flex; width:77%; justify-content:space-between;">
                                        <div>Shipping Fee</div>
                                        <div>500</div>
                                    </div> 
                                    <br> 
                                    <hr>
                                    <div style="display: flex; width:77%; justify-content:space-between;">
                                        <div>Total</div>
                                        <div>{{ $checkout->total }}</div>
                                    </div>      
                                    <br>                                                                                          
                                    <div style="display: flex; width:80%; justify-content:space-between;">                                                                               
                                        <label for="">Set payment status: </label>                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status1" value="true" checked>
                                            <label class="form-check-label" for="exampleRadios1">
                                              Paid
                                            </label>
                                          </div>
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status2" value="false">
                                            <label class="form-check-label" for="exampleRadios2">
                                              Not Paid
                                            </label>
                                          </div>    
                                    </div>
                                </div>                                            
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Add Record">                    
                                </div>
                        </form>
                    </div>
                    </div>        
                </div>
            </div>  --}}
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Sales Returns')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[0] as $return)        
            <tr>                                                            
                <td>
                    @if ($return->status === null)
                        <a href="" data-bs-target="#id{{ $return->receipt_number }}" data-bs-toggle="modal">{{ $return->receipt_number }}</a>
                    @else
                        {{ $return->receipt_number }}
                    @endif                    
                </td>                                
                <td>{{ $return->acc_name }}</td>                                                                                      
                <td>{{ $return->acc_number }}</td>                                                                                      
                <td>{{ $return->payment_method }}</td>    
                <td>{{ $return->product_number }}</td>
                <td>{{ $return->quantity }}</td>
                <td>
                    @if ($return->status === null)
                        PENDING
                    @elseif($return->status == false)
                        REJECTED
                    @else
                        ACCEPTED
                    @endif                    
                </td>
                <td>{{ $return->created_at }}</td>                                                                                                                                                  
                <td>{{ $return->updated_at }}</td>                                                                                                                                                  
            </tr> 
                  
            <div class="modal fade" id="{{ 'id'.$return->receipt_number }}" tabindex="-1" aria-labelledby="idLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="idLabel">Update</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                        </div>
                            <form action="{{ url('/dashboard/salesReturn/'.$return->receipt_number) }}" method="POST">
                            @csrf
                            @method("PUT")                                                                                                                                                       
                                <div class="modal-body"> 
                                    <input type="text" hidden value="{{ $return->id }}" name="return_id">
                                    <label for="">Proof of Defective Product:</label>
                                    <div style="width: 300px;">
                                        <img src="{{ asset('/storage/return_images/'. $return->image) }}" alt="" width="100%">
                                    </div>
                                    <div style="display: flex; width:80%; justify-content:space-between">
                                        <label for="">Set status: </label>                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status1" value="true" checked>
                                            <label class="form-check-label" for="exampleRadios1">
                                            Active
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="setStatus" id="status2" value="false">
                                            <label class="form-check-label" for="exampleRadios2">
                                            Inactive
                                            </label>
                                        </div>
                                        
                                    </div>                                           
                                </div>                                            
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary" value="Add Record">                    
                                </div>
                        </form>
                    </div>
                    </div>        
                </div>
            </div> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Sales History')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection as $sale)        
            <tr>                                                            
                <td>{{ $sale->sales_category }}</td>                                
                <td>{{ $sale->total }}</td>                                                                                      
                <td>{{ $sale->created_at }}</td>                                                                                                                                                  
                <td>{{ $sale->updated_at }}</td>                                                                                                                                                  
            </tr> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Expenses History')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection as $expense)        
        <tr>                            
                

            <td>
            
                     {{ $expense->id }}
                                                                                                                                         
            </td> 
            
            <td>{{ $expense->receipt_number }}</td> 
                           
            @if ($expense->expense_categories_id == 1)
            <td>{{ DB::table('expense_categories')->where('id', $expense->expense_categories_id)->first()->category_name }}</td>
            @else                
            <td>{{ $expense->expense_categories_id }}</td> 
            @endif 
            <td>{{ $expense->description }}</td>                
            <td>{{ $expense->computed_expenses }}</td>                                                               
            <td>{{ $expense->created_at }}</td>                                                                       
            <td>{{ $expense->updated_at }}</td>                                                                                                                                       
        </tr> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Orders History')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection as $checkout)        
            <tr>                    
                <td>                        
                   
                       {{ $checkout->invoice_number }}
                   
                       
                
                </td>
                @if ($checkout->receipt_number === null)
                    <td>NONE</td>
                @else
                    <td>{{ $checkout->receipt_number }}</td>
                @endif 
                <td>{{ $checkout->acc_name }}</td>                                
                <td>{{ $checkout->acc_num }}</td>
               
                <td>{{ $checkout->payment_method }}</td> 
                <td>
                    @if ($checkout->shipping_service == null)
                        NONE
                    @else
                        {{ $checkout->shipping_service }}
                    @endif
                </td>               
                <td>
                    @if ($checkout->tracking_number == null)
                        NONE
                    @else
                        {{ $checkout->tracking_number }}
                    @endif
                </td>
                <td>
                    @if ($checkout->status === null)
                        PENDING
                    @elseif($checkout->status == 0)                
                        NOT CONFIRMED
                    @else
                        CONFIRMED
                    @endif                    
                </td>                    
                <td>{{ $checkout->total }}</td>                                                          
                <td>{{ $checkout->created_at }}</td>
                @if ($checkout->receipt_number === null)
                    <td>PENDING</td>  
                @else
                    <td>{{ $checkout->updated_at }}</td>  
                @endif                                                                                                                            
            </tr>
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Shipment History')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection as $shipment)        
            <tr>                    
                <td>{{ $shipment->receipt_number }}</td>                                   
                <td>{{ $shipment->dateReceived }}</td>                        
                <td>{{ $shipment->shipping_fee }}</td>                        
                <td>{{ $shipment->created_at }}</td>                                                                            
                <td>{{ $shipment->updated_at }}</td>                                                                                                                               
            </tr>
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Transactions History')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection as $transaction)        
        <tr>                
            
                <td>
                
                    {{ $transaction->receipt_num }}
                            
            </td>                                                                                                      
            <td>{{ Supplier::find($transaction->supplier_id)->supp_name }}</td>                        
            <td>{{ $transaction->totalCost }}</td>
            <td>
                @if ($transaction->datePaid)
                    {{ $transaction->datePaid }}
                @else
                    NOT PAID
                @endif                    
            </td>                                                                            
            <td>{{ $transaction->created_at }}</td>                                       
            <td>{{ $transaction->updated_at }}</td>                                       
        </tr> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Transfers History')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection as $shipment)        
        <tr>
                <td>      
                            
                    
                    
                       
                       
                
                        {{ $transfer->transfer_number }}
                   
            </td>                                                                                                                                            
            <td>{{ $transfer->stock_number }}</td>
            <td>{{ $transfer->product_number }}</td> 
            <td>{{ $transfer->quantity }}</td>                       
            <td>@if ($transfer->confirmed == 'False' )
                NOT CONFIRMED
            @elseif($transfer->confirmed === null)                    
                PENDING
            @else
                CONFIRMED
            @endif</td>                                        
            <td>{{ $transfer->created_at }}</td>                                                                                                                               
            <td>{{ $transfer->updated_at }}</td>  
            </tr>
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Balance Sheet')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection[0] as $balance)        
        <tr>                                                            
            <td>                                              
                    {{ $balance->description }}                               
            </td>                                
            <td>
                @if ($balance->debit_amount === null)
                    0
                @else
                +{{ $balance->debit_amount }}
                @endif
                
            </td>                                                                                      
            <td>
                @if ($balance->credit_amount === null)
                    0
                @else
                -{{ $balance->credit_amount }}
                @endif               
            </td>                                                                                      
            <td>{{ $balance->total_balance }}</td>                                    
            <td>{{ $balance->created_at }}</td>                                                                                                                                                  
            <td>{{ $balance->updated_at }}</td>                                                                                                                                                  
        </tr> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Sales Returns History')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection as $return)        
        <tr>                                                            
            <td>                                              
                    {{ $return->receipt_number }}                               
            </td>                                
            <td>{{ $return->acc_name }}</td>                                                                                      
            <td>{{ $return->acc_number }}</td>                                                                                      
            <td>{{ $return->payment_method }}</td>    
            <td>{{ $return->product_number }}</td>
            <td>{{ $return->quantity }}</td>
            <td>
                @if ($return->status === null)
                    PENDING
                @elseif($return->status == false)
                    REJECTED
                @else
                    ACCEPTED
                @endif                    
            </td>
            <td>{{ $return->created_at }}</td>                                                                                                                                                  
            <td>{{ $return->updated_at }}</td>                                                                                                                                                  
        </tr> 
        @endforeach
    </tbody>           
</table>
@endif
@if ($title == 'Removed Employees')
<table id="example" class="hover" style="width: 100%;">            
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataCollection as $employee)        
        <tr>
            <td style="display: flex; justify-content:center;">
                <form action="{{ url('/dashboard/employees/restore/' .$employee->user_id) }}" method="POST" id="restore_form">
                    @csrf
                    @method('PUT')                    
                    <input type="text" name="user_id" value="{{ $employee->user_id }}" hidden>  
                    <a href="" id="dataID" data-bs-toggle="modal" data-bs-target="{{ '#id'.$employee->user_id }}">{{ $employee->user_id }}</a>
                </form>                
            </td>                                                               
            <td>{{ $employee->emp_firstName }}</td>                        
            <td>{{ $employee->emp_lastName }}</td>                        
            <td>{{ $employee->emp_email }}</td>                        
            <td>{{ $employee->emp_gender }}</td>                        
            <td>{{ $employee->emp_birthDate }}</td>                                       
            <td>{{ $employee->created_at }}</td>                                       
            <td>{{ $employee->updated_at }}</td>                                       
        </tr> 
        @endforeach
    </tbody>           
</table>
@endif



