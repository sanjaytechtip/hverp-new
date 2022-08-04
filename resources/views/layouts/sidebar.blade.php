<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation" style="display:flex">
      <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
          <!-- Navbar Toolbar -->
		  <div class="new-menu">
<div class="menu-logo"><span class="logobox"><img src="{{url('public/theme/assets/images/BIzVoiLogo.png')}}" alt=""></span></div>
<ul class="menu-box">
  <li><a href="{{route('dashboard')}}">Dashboard</a></li>
  <li class="mega-menu-li"><a href="javascript:void(0);">Masters</a>
		<div class="mega-menu-box">
		  <ul>
			<li class="mega-menu-title">Management </li>
		    <li><a href="{{ url('admin/listdata/11')}}">Users</a></li>
		    <li><a href="{{ url('admin/customer-list')}}">Customers</a></li>
		    <li><a href="{{ url('admin/vendor-list')}}">Vendors</a></li>
		    <li><a href="{{ url('admin/listdata/35')}}">Items</a></li>
			<li><a href="{{ url('admin/listdata/21')}}">Item Name</a></li>
			<li><a href="{{ url('admin/companylist')}}">Group of Company</a></li>		    
		    <li><a href="{{ url('admin/item_details')}}">Opening Stock</a></li>
		  </ul>
		  <ul>
			<li class="mega-menu-title">Discount/Net Rate</li>
		    <li><a href="{{ url('admin/purchase-discount-net-rate-list')}}">Purchase Discount</a></li>
		    <li><a href="{{ url('admin/purchase-net-rate-list')}}">Purchase Net Rate</a></li>
		    <li><a href="{{ url('admin/discountlist')}}">Sale Discount</a></li>
		    <li><a href="{{ url('admin/netratelist')}}">Sale Net Rate</a></li>
		  </ul>
		  <ul>
		  	<li class="mega-menu-title">Other Masters</li>
		    <li><a href="{{ url('admin/listdata/23')}}">Department</a></li>
		    <li><a href="{{ url('admin/listdata/22')}}">Location</a></li>
		    <li><a href="{{ url('admin/listdata/12')}}">Designation</a></li>
		    <li><a href="{{ url('admin/listdata/20')}}">Payment Terms</a></li>
			<li><a href="{{ url('admin/listdata/16')}}">Segments</a></li>
			<li><a href="{{ url('admin/listdata/17')}}">Customer Categories</a></li>
			<li><a href="{{ url('admin/listdata/7')}}">Rack</a></li>
		  </ul>
		  <ul>
		  	<li class="mega-menu-title"></li>
			<li><a href="{{ url('admin/sapcodelist')}}">SAP Code</a></li>
		    <li><a href="{{ url('admin/listdata/34')}}">HSN Codes & Taxes</a></li>
		    <li><a href="{{ url('admin/listdata/33')}}">Grades</a></li>
		    <li><a href="{{ url('admin/listdata/27')}}">Brand</a></li>
			<li><a href="{{ url('admin/listdata/32')}}">Storage Conditions</a></li>
			<li><a href="{{ url('admin/listdata/29')}}">Unit</a></li>
			<li><a href="{{ url('admin/listdata/3')}}">Other Charges</a></li>
		  </ul>
		  <ul>
		  	<li class="mega-menu-title"></li>
		    <li><a href="{{ url('admin/listdata/28')}}">Pack Size</a></li>
		    <li><a href="{{ url('admin/listdata/25')}}">Item Categories</a></li>
		    <li><a href="{{ url('admin/listdata/19')}}">Process</a></li>
		    <li><a href="{{ url('admin/listdata/15')}}">Item Type</a></li>
			<li><a href="{{ url('admin/listdata/10')}}">Item Sub Type</a></li>
			<li><a href="{{ url('admin/listdata/9')}}">Type of Pack</a></li>
		  </ul>
		  <ul>
		  	<li class="mega-menu-title"></li>
			<li><a href="{{ url('admin/listdata/8')}}">Room</a></li>
		    <li><a href="{{ url('admin/listdata/1')}}">FAQ</a></li>
		    <li><a href="{{ url('admin/listdata/31')}}">Transport</a></li>
		    <li><a href="{{ url('admin/listdata/26')}}">TDS Limit</a></li>
			<li><a href="{{ url('admin/listdata/30')}}">TCS Limit</a></li>
			<li><a href="{{ url('admin/listdata/24')}}">CAS Number</a></li>
		  </ul>
		</div>
  </li>
  <li class="mega-menu-li"><a href="javascript:void(0);">Transaction</a>
		<div class="mega-menu-box">
		  <ul>
			<li class="mega-menu-title">Purchase</li>
		    <li><a href="{{ url('admin/purchaseorder-list')}}">Purchase Order</a></li>
		    <li><a href="{{ url('admin/mrn-list')}}">MRN</a></li>
		    <!--<li><a href="{{ url('admin/stock-inward-list')}}">Stock Inward</a></li>-->
		    <li><a href="{{ url('admin/item_details?type=2')}}">Stock Inward</a></li>
			<li><a href="{{ url('admin/rejected-qty-list')}}">Rejected Quantity</a></li>
			<li><a href="{{ url('admin/breakage-qty-list')}}">Breakage Quantity</a></li>
			<li><a href="{{ url('admin/shortage-qty-list')}}">Shortage Quantity</a></li>
		  </ul>
		  <ul>
			<li class="mega-menu-title">Quotation</li>
		    <li><a href="{{ url('admin/quotation-list')}}">Quotation</a></li>
		  </ul>
		  <ul>
			<li class="mega-menu-title">Sale Order</li>
		    <li><a href="{{ url('admin/order-to-dispatch')}}">Sale Order</a></li>
			<li><a href="{{ url('admin/dispatch-planning')}}">Dispatch Planning</a></li>
			<li><a href="{{ url('admin/outwardchallanlist')}}">Outward Challan</a></li>			
			<li><a href="{{ url('admin/challan-list')}}">Challan Listing</a></li>
		  </ul>
		</div>
  </li>
  <li class="mega-menu-li"><a href="javascript:void(0);">FMS</a>
		<div class="mega-menu-box">
		  <ul>
			<li class="mega-menu-title">Task FMS</li>
		    <li><a href="{{ url('admin/taskfmsdata')}}">Task FMS</a></li>
		    <li><a href="{{ url('admin/task-form-create')}}">Task Form</a></li>
		    <li><a href="{{ url('admin/task-form-create-multiple')}}">Multiple Task Form</a></li>
		    <li><a href="{{ url('admin/listdata/14')}}">Task Category</a></li>
		  </ul>
		  <ul>
			<li class="mega-menu-title">Purchase FMS</li>		   
		  </ul>
		  <ul>
			<li class="mega-menu-title">Sale FMS</li>		   
		  </ul>		  
		</div>
  </li>
</ul>
</div>
         
         <div class="burger-icon-menu"><img src="{{url('public/theme/assets/images/hamburger-icon.png')}}" alt="" /></div>
          <!-- End Navbar Toolbar -->
    
          <!-- Navbar Toolbar Right -->
          <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
          
			
            <li class="nav-item dropdown">
              <a class="nav-link navbar-avatar waves-effect waves-light waves-round" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
			  <span class="user-name"><span>Welcome </span>{{Auth::user()->full_name}}</span>   
			  <span class="avatar">
                  <img style="width:30px; height:30px;" src="{{url('public/uploads/images/'.Auth::user()->upload_picture)}}" alt="Avatar">
                </span>
              </a>
              <div class="dropdown-menu" role="menu">
                <a class="dropdown-item waves-effect waves-light waves-round" href="{{url('admin/editdata/users/'.Auth::user()->id.'/11')}}" role="menuitem"><i class="icon md-account" aria-hidden="true"></i> Profile</a>
                <a class="dropdown-item waves-effect waves-light waves-round" href="{{url('admin/addcompanydetails')}}" role="menuitem"><i class="icon md-settings" aria-hidden="true"></i> Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item waves-effect waves-light waves-round" href="{{ route('logout') }}" role="menuitem" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="icon md-power" aria-hidden="true"></i> Logout</a>
              </div>
            </li>
            
            
          </ul>
          <!-- End Navbar Toolbar Right -->
        </div>
        <!-- End Navbar Collapse -->
    
        <!-- Site Navbar Seach -->
        <div class="collapse navbar-search-overlap" id="site-navbar-search">
          <form role="search">
            <div class="form-group">
              <div class="input-search">
                <i class="input-search-icon md-search" aria-hidden="true"></i>
                <input type="text" class="form-control" name="site-search" placeholder="Search...">
                <button type="button" class="input-search-close icon md-close" data-target="#site-navbar-search" data-toggle="collapse" aria-label="Close"></button>
              </div>
            </div>
          </form>
        </div>
        <!-- End Site Navbar Seach -->
      </div>
</nav>