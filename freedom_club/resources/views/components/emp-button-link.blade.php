@if ($link)
    @if ($disabled == 'true')
    <div style="background-color: black; color:white; opacity:0.5; text-decoration:none; width:210px; height:50px; border-radius:10px; display:flex; justify-content:center; align-items:center;">
        {{ strtoupper($title) }}
    </div> 
    @else
    <a href="{{ $link }}" style="background-color: black;  opacity:1; color:white; text-decoration:none; width:210px; height:50px; border-radius:10px; text-align:center; display:flex; justify-content:center; align-items:center;">
        {{ strtoupper($title) }}
    </a>
    @endif    
@else
    @if ($disabled == 'true')
    <button disabled type="button" data-bs-toggle="modal" data-bs-target="{{'#' . $target }}" style="border:0; background-color: black; color:white; text-decoration:none; width:200px; height:50px; border-radius:10px; opacity:0.5; display:flex; justify-content:center; align-items:center;">
        {{ strtoupper($title) }}
    </button>
    @else
        @if ($disabled == 'false')
        <button type="button" data-bs-toggle="modal" data-bs-target="{{'#' . $target }}" style="border:0; background-color: black; color:white; text-decoration:none; width:200px; height:50px; border-radius:10px; opacity:1; display:flex; justify-content:center; align-items:center;">
            {{ strtoupper($title) }}
        </button>
        @endif
    @endif
@endif

@if ($toggle == 'true')
    @if ($target == 'addSupplier')
    <div class="modal fade" id="{{$target}}" tabindex="-1" aria-labelledby="{{$target . "Label"}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$target . "Label"}}">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                </div>
                    <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf                                                                                                                                                                 
                    <div class="modal-body" style="height:300px">
                        <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around; align-items:center;">
                            <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                <label style="width: 170px" for="supplier_name" class="form-label text-white">Supplier Name: </label>
                                <input type="text" class="form-control" name="supplier_name" placeholder="Name" >
                            </div>                    
                            <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                <label style="width: 180px" for="supplier_contactNumber" class="form-label text-white">Contact Number: </label>
                                <input type="tel" class="form-control" name="supplier_contactNumber" placeholder="09*********" >
                            </div>                    
                            <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                <label style="width: 150px" for="supplier_email" class="form-label text-white">Email Address: </label>
                                <input type="email" class="form-control" name="supplier_email" placeholder="Email" >
                            </div>  
                        </div>                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Add Record">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($target == 'addItemToSupplier')
    <div class="modal fade" id="{{$target}}" tabindex="-1" aria-labelledby="{{$target . "Label"}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$target . "Label"}}">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                </div>
                    <form action="{{ route('materials.store') }}" method="POST">
                    @csrf                                                                                                                                                                 
                    <div class="modal-body" style="height:400px">
                        <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around;">
                            <div style="display: flex; align-items:center;">
                                <label for="supplier_id" class="form-label text-white" style="margin-right:60px">Supplier Name: </label> 
                                <select name="supplier_id" id="supplier_id" class="form-select mb-3" style="width: 200px;" >
                                    <option value="" disabled hidden selected>None</option>
                                    @foreach ($dataCollection as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->supp_name }}</option>
                                    @endforeach                                                                       
                                </select>
                            </div>
                            <div style="height:100%; width:100%; display: flex; align-items:center;">
                                <label style="width: 170px" for="material_type" class="form-label text-white">Material Type:</label>
                                <select name="material_type" id="material_type" class="form-select mb-3" style="width: 100px;" >
                                    <option value="" disabled hidden selected>None</option>                                
                                    <option value="Shirt">Shirt</option>                                                                                                         
                                    <option value="Hat">Hat</option>                                                                                                         
                                    <option value="Bag">Bag</option>                                                                                                         
                                </select>
                            </div>                    
                            <div style="height:100%; width:100%; display: flex; align-items:center;">
                                <label style="width: 170px" for="material_size" class="form-label text-white">Material Size: </label>
                                <select name="material_size" class="form-select mb-3" id="material_size" style="width: 100px;" >                                    
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
                                <input type="color" class="form-control" id="material_color" name="material_color" placeholder="(Ex: Black, Red, Fuschia, and etc.)">
                            </div>                                                                                                                 
                            <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                <label style="width: 150px" for="material_price" class="form-label text-white">Material Price: </label>
                                <input type="number" class="form-control" id="material_price" name="material_price" placeholder="(Ex: 50.00, 75.15, and etc.)" step=".01" >
                            </div>                                                                                              
                        </div>                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Add Record">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if($target == 'registerEmployee')
    <div class="modal fade" id="{{$target}}" tabindex="-1" aria-labelledby="{{$target . "Label"}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$target . "Label"}}">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                </div>
                    <form action="{{ route('registerEmployee.store') }}" method="POST">
                    @csrf                                                                                                                                                                 
                    <div class="modal-body" style="height:700px">
                        <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around;">
                            <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                <label style="width: 170px" for="firstName" class="form-label text-white">First Name: </label>
                                <input type="text" class="form-control" name="firstName" placeholder="First Name" >
                            </div>                    
                            <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                <label style="width: 180px" for="lastName" class="form-label text-white">Last Name: </label>
                                <input type="text" class="form-control" name="lastName" placeholder="Last Name" >
                            </div>                    
                            <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                <label style="width: 150px" for="birthDate" class="form-label text-white">Birthdate: </label>
                                <input type="date" class="form-control" name="birthDate">
                            </div>                              
                            <div style="display: flex">
                                <label for="gender" class="form-label text-white" style="margin-right:60px">Gender: </label> 
                                <select name="gender" id="gender" class="form-select mb-3" style="width: 100px;" >
                                    <option value="" disabled hidden selected>None</option>
                                    <option value="Male">M</option>
                                    <option value="Female">F</option>
                                </select>
                            </div>
                            <div style="display: flex">
                                <label for="role" class="form-label text-white" style="margin-right:60px">Role: </label>
                                <select name="role" id="role" class="form-select mb-3" style="width: 200px;" >
                                    <option value="" disabled hidden selected>None</option>
                                    <option value="store_owner">Store Owner</option>
                                    <option value="warehouse_manager">Warehouse Manager</option>
                                    <option value="product_manager">Product Manager</option>
                                </select>
                            </div>
                            <div style="display: flex">
                                <label for="username" class="form-label text-white" style="margin-right: 20px">Username: </label>                            
                                <input type="text" class="form-control" placeholder="Username" name="username">
                            </div>
                            <div style="display: flex">
                                <label for="email" class="form-label text-white" style="margin-right: 20px">Email: </label>  
                                <input type="email" class="form-control" placeholder="Email" name="email">
                            </div>
                           <div style="display: flex">
                                <label for="password" class="form-label text-white" style="margin-right: 20px">Password: </label>                             
                                <input type="password" class="form-control" placeholder="Password" name="password">
                           </div>
                            <div style="display: flex">
                                <label for="password_confirmation" class="form-label text-white" style="margin-right: 20px">Confirm Password: </label>  
                                <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation">
                            </div>
                            
                        </div>                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Add Record">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($target == 'addTransaction')  
    <style>
        #add_button i:hover{
            cursor: pointer;
        }
    </style>          
    <div class="modal fade" id="{{$target}}" tabindex="-1" aria-labelledby="{{$target . "Label"}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$target . "Label"}}">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                </div>
                    <form action="{{ route('purchases.store') }}" method="POST">
                    @csrf                                                                                                                                                                 
                    <div class="modal-body">
                        <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around;">
                            {{-- <div style="height:100%; width:100%; display: flex; align-items:center; justify-content:center;">
                                <label style="width: 170px" for="receipt_number" class="form-label text-white">Receipt #: </label>
                                <input type="text" class="form-control" name="receipt_number" placeholder="Receipt #" >
                            </div>  --}}
                            <div style="height:100%; width:100%; display: flex; align-items:center; margin-top: 20px">
                                <label style="width: 125px" for="shipping_fee" class="form-label text-white">Shipping Fee: </label>
                                <input type="number" style="width: 150px; margin-right:10px" id="shipping_fee" class="form-control" name="shipping_fee" step="1" min="0">
                                <button class="btn btn-secondary" style="width:40%; height:100%" id="clear_item" disabled>Clear Addtional Items</button>
                            </div>                   
                            <div style="display: flex; align-items:center; margin-top:20px">
                                <label for="supplier_id" class="form-label text-white" style="margin-right:60px">Supplier ID: </label> 
                                <select id="supplier_id" name="supplier_id" class="form-select" style="width: 100px;" >
                                    <option value="" disabled hidden selected>None</option>                                    
                                    @foreach ($dataCollection[1] as $supplier)
                                        @if ($supplier->material->first())
                                            <option value="{{ $supplier->id }}">{{ $supplier->id }}</option>
                                        @endif                                                                                                                                                                  
                                    @endforeach                                                                      
                                </select>
                            </div>                   
                            <div style="display: flex; align-items:center; margin-top:20px;">                                
                                <label for="material_id" class="form-label text-white" style="margin-right:60px">Material Number: </label>                                 
                                <select id="material_id" name="material_id" class="form-select" style="width: 100px;" >
                                    <option value ="" disabled selected>None</option>                                     
                                </select>                                
                            </div>       

                            <div style="height:100%; width:100%; display: flex; align-items:center; margin-top: 20px">
                                <label style="width: 150px" for="material_qty" class="form-label text-white">Quantity Purchased: </label>
                                <input type="number" style="width: 150px" id="material_quantity" class="form-control quantity_purchased1" name="material_quantity" step="1" min="0">
                            </div> 
                             

                            <div>

                            </div>

                            <div id="add_button" style="height:100%; display: flex; justify-content:center; align-items:center;">
                                <i class="fas fa-plus-circle" style="font-size: 40px"></i>
                            </div>  
                            
                            <div style="height:100%; width:100%; display: flex; align-items:center;">
                                <label style="width: 150px" for="totalCost" class="form-label text-white">Total Cost: </label>
                                <input id="totalCost" type="number" disabled class="form-control" name="totalCost" step=".01" min="0">
                            </div>                              
                            
                            {{-- <div style="display: flex">
                                <label for="datePaid" class="form-label text-white" style="margin-right: 20px">Date Paid: </label>                            
                                <input type="date" class="form-control" name="datePaid">
                            </div> --}}
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Add Record">                    
                    </div>
                </form>
            </div>        
        </div>
    </div>
    
    @endif            
    @if($target == 'requestStockTransfer')  
    <style>
        #add_button i:hover{
            cursor: pointer;
        }
    </style>          
    <div class="modal fade modal_stock_transfer" id="{{$target}}" tabindex="-1" aria-labelledby="{{$target . "Label"}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$target . "Label"}}">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                </div>
                    <form action="{{ route('transfer.store') }}" method="POST">
                    @csrf                                                                                                                                                                 
                    <div class="modal-body">
                        <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around;">
                            <div style="height:100%; width:100%; display: flex; align-items:center;">
                                <label style="width: 140px" for="stock_id" class="form-label text-white">Stock Number: </label>
                                <select id="stock_id" name="stock_id" class="form-select" style="width: 100px;" >
                                    <option value="None" disabled hidden selected>None</option>                                    
                                </select>
                            </div> 
                            <div style="height:100%; width:100%; display: flex; align-items:center;">
                                <label style="width: 140px" for="product_id" class="form-label text-white">Product Number: </label>
                                <select id="product_id" disabled name="product_id" class="form-select" style="width: 100px;" >
                                    <option value="None" disabled hidden selected>None</option>                                    
                                </select>
                            </div> 
                            <div style="height:100%; width:100%; display: flex; align-items:center; margin-top: 20px">
                                <label style="width: 150px" for="stock_type" class="form-label text-white">Stock Type: </label>
                                <input type="text" style="width: 150px" disabled id="stock_type" class="form-control" name="stock_type" step="1" min="0">
                            </div>                   
                            <div style="display: flex; align-items:center; margin-top:20px">
                                <label for="stock_size" class="form-label text-white" style="margin-right:60px">Stock Size: </label> 
                                <input type="text" style="width: 150px" disabled id="stock_size" class="form-control" name="stock_size" step="1" min="0">
                            </div>                   
                            <div style="display: flex; align-items:center; margin-top:20px;">                                
                                <label for="stock_color" class="form-label text-white" style="margin-right:60px">Stock Color: </label>                                 
                                <input type="color" style="width: 150px" disabled id="stock_color" class="form-control" name="stock_color" step="1" min="0">                                
                            </div>       

                            <div style="height:100%; width:100%; display: flex; align-items:center; margin-top: 20px">
                                <label style="width: 150px" for="quantity_used" class="form-label text-white">Quantity Used: </label>
                                <input type="number" disabled style="width: 150px" id="quantity_used" class="form-control" name="quantity_used" step="1" min="1">
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
    @endif
    @if($target == 'addProduct')  
    <style>
        #add_button i:hover{
            cursor: pointer;
        }
    </style>          
    <div class="modal fade" id="{{$target}}" tabindex="-1" aria-labelledby="{{$target . "Label"}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$target . "Label"}}">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                </div>
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf                                                                                                                                                                 
                    <div class="modal-body">
                        <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around;">                             
                            <div style="height:100%; width:100%; display: flex; align-items:center; margin-top: 20px">
                                <label style="width: 150px" for="product_name" class="form-label text-white">Product Name/Design: </label>
                                <input type="text" style="width: 300px" id="prod_name" class="form-control" name="product_name" placeholder="Ex: Savage Tee, Basic Tee V2, and etc." step="1" min="0">
                            </div>                                               
                            <div style="height:100%; width:100%; display: flex; align-items:center; margin-top:10px;">
                                <label style="width: 170px;" for="product_type" class="form-label text-white">Product Type: </label>
                                <select disabled name="product_type" id="prod_type" class="form-select mb-3" style="width: 100px;" >
                                    <option value="" disabled hidden selected>None</option>                                
                                    <option value="Shirt">Shirt</option>                                                                                                         
                                    <option value="Hat">Hat</option>                                                                                                         
                                    <option value="Bag">Bag</option>                                                                                                         
                                </select>
                            </div>                    
                            <div style="height:100%; width:100%; display: flex; align-items:center;">
                                <label style="width: 170px" for="product_size" class="form-label text-white">Product Size: </label>
                                <select disabled name="product_size" class="form-select mb-3" id="prod_size" style="width: 100px;" >                                    
                                    <option value="None"disabled hidden selected>None</option>                                
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
                                <label style="width: 170px" for="prod_color" class="form-label text-white">Product Color: </label>
                                <input type="color" class="form-control" id="prod_color" name="product_color" placeholder="(Ex: Black, Red, Fuschia, and etc.)">
                            </div>  
                            {{-- <div style="height:100%; width:100%; display: flex; align-items:center;">
                                <label for="product_image" style="width:170px; margin-right:40px">Choose an image: (will be shown on the main page)</label>
                                <input type="file" name="product_image" id="product_image" disabled>                                      
                            </div>                                                                                  --}}
                            <div style="height:100%; width:100%; display: flex; align-items:center; margin-top: 20px">
                                <label style="width: 150px" for="product_price" class="form-label text-white">Product Price: </label>
                                <input type="number" disabled style="width: 150px" id="prod_price" class="form-control" name="product_price" step="1" min="1">
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
    @endif            
    @if($target == 'addPicture')  
    <style>
        #add_button i:hover{
            cursor: pointer;
        }
    </style>          
    <div class="modal fade" id="{{$target}}" tabindex="-1" aria-labelledby="{{$target . "Label"}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$target . "Label"}}">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                </div>
                    <form action="{{ route('carousel.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf                                                                                                                                                                 
                    <div class="modal-body">
                        <label for="carousel_image" class="form-label text-white">Select Image: </label>
                        <input type="file" class="form-control" name="carousel_image" id="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Add Record">                    
                    </div>
                </form>
            </div>        
        </div>
    </div>
    @endif                        
    @if($target == 'addExpense')  
    <style>
        #add_button i:hover{
            cursor: pointer;
        }
    </style>          
    <div class="modal fade" id="{{$target}}" tabindex="-1" aria-labelledby="{{$target . "Label"}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$target . "Label"}}">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                </div>
                    <form action="{{ route('expense.store') }}" method="POST">
                    @csrf                                                                                                                                                                 
                    <div class="modal-body">
                        <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around;">                             
                                                                          
                            <div style="height:100%; width:100%; display: flex; align-items:center; margin-top:10px;">
                                <label style="width: 170px;" for="category_name" class="form-label text-white">Category: </label>
                                <select name="category_name" id="category_name" class="form-select mb-3" style="width: 200px;" >
                                    <option value="" disabled hidden selected>None</option>                                
                                    @foreach ($dataCollection[1] as $index => $category)  
                                        @if ($index != 0)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endif                                                                              
                                    @endforeach
                                </select>
                            </div>                    
                            <div hidden style="height:100%; width:100%; display: flex; align-items:center; margin-top:10px;">
                                <label style="width: 170px;" for="description" class="form-label text-white">Description: </label>
                                <select disabled name="description" id="description" class="form-select mb-3" style="width: 150px;" >
                                    <option value="None" disabled hidden selected>None</option>                                                                                                                                                                            
                                </select>
                            </div>
                            <div hidden style="height:100%; width:100%; display: flex; align-items:center; margin-top: 20px">
                                <label style="width: 150px" for="description" class="form-label text-white">Description: </label>
                                <input disabled type="text" style="width: 150px" id="description" class="form-control" name="description" step="1" min="1">
                            </div>                                                                           
                            <div style="height:100%; width:100%; display: flex; align-items:center; margin-top: 20px">
                                <label style="width: 150px" for="computed_expense" class="form-label text-white">Computed Expense: </label>
                                <input type="number" disabled style="width: 150px" id="computed_expense" class="form-control" name="computed_expense" step="1" min="1">
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
    @endif                        
    @if($target == 'addProductImage')  
    <style>
        #add_button i:hover{
            cursor: pointer;
        }
    </style>
    <div class="modal fade" id="{{$target}}" tabindex="-1" aria-labelledby="{{$target . "Label"}}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color:black; color:white; border: 2px solid white;">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$target . "Label"}}">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                </div>                
                    <form action="{{ url('/dashboard/product/images/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf             
                    <input type="text" hidden value="{{ $dataCollection[0]->prod_name_color_id }}" name="prod_color_name_id">                                                                                                                                     
                    <div class="modal-body">
                        <div style="height: 100%; width:100%; display:flex; flex-direction:column; justify-content:space-around;">
                            <label for="product_image" style="margin-bottom: 10px">Select an image:</label>
                            <input type="file" name="product_image" id="" class="form-control">                                                                                                                                                                                                   
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
    @endif                        
@endif

