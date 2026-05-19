<!-- Sidebar-right-->
		<div class="sidebar sidebar-left sidebar-animate">
			<div class="panel panel-primary card mb-0 box-shadow">
				<div class="tab-menu-heading border-0 p-3">
					<div class="card-title mb-0">{{__('shurtcuts.shurtcuts')}}</div>
					<div class="card-options mr-auto">
						<a href="#" class="sidebar-remove"><i class="fe fe-x"></i></a>
					</div>
				</div>

				<a 
    href="{{ url('/' . ($page = 'dashboard')) }}" 
    title="dashboard" 
    accesskey="d">
                </a>
				@can('Sales products')
				<a 
    href="{{ url('/' . ($page = 'goToSale')) }}" 
    title="goToSale" 
    accesskey="s">
                </a>
				@endcan
				@can('sales return') 
				<a 
    href="{{ url('/' . ($page = 'return_sale')) }}" 
    title="return_sale" 
    accesskey="r">
                </a>
				@endcan
				@can('Purchases products')
				<a 
    href="{{ url('/' . ($page = 'purchases')) }}" 
    title="purchases" 
    accesskey="p">
                </a>
				@endcan
				@can('purchase return')
				<a 
    href="{{ url('/' . ($page = 'Purchase_returns')) }}" 
    title='Purchase_returns' 
    accesskey="u">
                </a>
				@endcan
				@can('purchase order to resources')
				<a 
    href="{{ url('/' . ($page = 'Purchase_order_of_resources')) }}" 
    title="Purchase_order_of_resources" 
    accesskey="o">
                </a>
				@endcan
				@can('Add new product')
				<a 
    href="{{ url('/' . ($page = 'addnewProduct')) }}" 
    title="addnewProduct" 
    accesskey="t">
                </a>
				@endcan
				@can('Add new supplier')
				<a 
    href="{{ url('/' . ($page = 'addnewsupplier')) }}" 
    title="addnewsupplier" 
    accesskey="l">
                </a>
				@endcan
				@can('Add a new customer')
				<a 
    href="{{ url('/' . ($page = 'addnewcustomer')) }}" 
    title="addnewcustomer" 
    accesskey="a">
                </a>
				@endcan
				@can('Product location changent')
				<a 
    href="{{ url('/' . ($page = 'product_movement')) }}"
    title="product_movement" 
    accesskey="y">
                </a>
				@endcan
				@can('Receipt')
				<a 
    href="{{ url('/' . ($page = 'goToReceipt')) }}" 
    title="goToReceipt" 
    accesskey="g">
                </a>
				@endcan
				@can('offer price to customer')
				<a 
    href="{{ url('/' . ($page = 'getproductspricetocustomer')) }}" 
    title="getproductspricetocustomer" 
    accesskey="c">
                </a>
@endcan
@can('Available quantity')

				<a 
    href="{{ url('/' . ($page = 'getproductsquntitytocustomer')) }}" 
    title="getproductsquntitytocustomer" 
    accesskey="a">
                </a>
				@endcan

				@can('Produects')
				<a 
    href="{{ url('/' . ($page = 'showAllProducts')) }}" 
    title="showAllProducts" 
    accesskey="t">
                </a>
				@endcan
				@can('Voucher')
				<a 
    href="{{ url('/' . ($page = 'voncher')) }}" 
    title="voncher" 
    accesskey="i">
                </a>
				@endcan
				@can('Receipt document')
				<a 
    href="{{ url('/' . ($page = 'reciept_decoument')) }}" 
    title="reciept_decoument" 
    accesskey="v">
                </a>
				@endcan

				@can('Cash expenses')
				<a 
    href="{{ url('/' . ($page = 'cashEcprnse')) }}"
    title="cashEcprnse" 
    accesskey="g">
                </a>
				@endcan
				<div class="panel-body tabs-menu-body latest-tasks p-0 border-0">
  
				<div class="list d-flex align-items-center border-bottom p-3">
					<div class="">
						<span class="avatar bg-info brround avatar-md">D</span>
					</div>
					<a class="wrapper w-100 mr-3" href="#" >
						<p class="mb-0 d-flex ">
							<b>{{__('home.home')}}</b>
						</p>
						<div class="d-flex justify-content-between align-items-center">
							<div class="d-flex align-items-center">
								<i class="mdi las la-pen text-muted ml-1"></i>
								<small class="text-muted ml-auto">Alt + Shift + D</small>
								<p class="mb-0"></p>
							</div>
						</div>
					</a>
				</div>
				
				@can('Sales products')
				
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">S</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.sales')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + S</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>
							@endcan
							@can('sales return')
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">R </span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.salesـreturned')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + R</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>	
							@endcan
							@can('Purchases products')
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">P</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.purchases')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + p</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>	
							@endcan


							@can('Add new product')							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">T</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('supprocesses.addproduct')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + T</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>	
							@endcan
							@can('Add a new customer')
								<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">A</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.addnewcustomer')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + A</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>	
							@endcan
							@can('Add new supplier')
								<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">L</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.addnewsupplier')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + L</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>	
							@endcan



							@can('purchase return')
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">U</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.purchase_return')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + U</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
								
							</div>	
							@endcan

							@can('purchase order to resources')
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">O</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.Purchase_order_of_resources')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + O</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>
							@endcan
							@can('Product location changent')
								<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">Y</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('supprocesses.product_movement')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + Y</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>	
							@endcan
							

							@can('Receipt')		
												<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">E</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.Receipt')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + E</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>	
							@endcan
							@can('request price from supplier')
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">R</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.Requestـpricesـofـproducts')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + R</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>	
							@endcan
							@can('offer price to customer')
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">C</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.Offerـpricesـtoـcustomer')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + C</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>	
							@endcan
							@can('Available quantity')
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">A</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.Showـtheـavailableـquantityـtoـtheـcustomer')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + A</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>
					@endcan
					@can('Produects')
				
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">T</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.showallproduct')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + T</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>
							@endcan


							@can('Receipt document')
				
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">I</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.cash expenses')}} </b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + I</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>
							@endcan

							@can('Voucher')
				
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">V</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.voucher')}}</b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + V</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>
							@endcan

							@can('Cash expenses')
				
							<div class="list d-flex align-items-center border-bottom p-3">
								<div class="">
									<span class="avatar bg-info brround avatar-md">S</span>
								</div>
								<a class="wrapper w-100 mr-3" href="#" >
									<p class="mb-0 d-flex ">
										<b>{{__('home.Receipt document')}} </b>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="d-flex align-items-center">
											<i class="mdi las la-pen text-muted ml-1"></i>
											<small class="text-muted ml-auto">Alt + Shift + S</small>
											<p class="mb-0"></p>
										</div>
									</div>
								</a>
							</div>
							@endcan
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<!--/Sidebar-right-->
