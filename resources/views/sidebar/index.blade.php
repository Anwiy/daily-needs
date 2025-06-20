<div class="sidebar">
    <div class="logo-container">
        <img src="{{ asset('assets/image/Title-Icon.jpeg') }}" alt="Logo" class="logo">

    </div>

    <div class="sidebar-container">
        
        <a href="/">
            <div class = "menu-item">
                <i class="bi bi-house-door" style="color: #D1D88D;"></i>
                <span>Home</span>
            </div>
        </a>
        
        <a href="/dashboard/myprofile">
            <div class = "menu-item">
                <i class="bi bi-person" style="color: #D1D88D;"></i>
                <span>My Profile</span>
            </div>
        </a>

        <a href="{{ route('purchasehistory.show', ['status' => 'Completed']) }}">
             <div class = "menu-item">
                <i class="bi bi-clock-history" style="color: #D1D88D;"></i>
                <span>Purchase History</span>
            </div>
        </a>
        <a href="/dashboard/topup">
            <div class = "menu-item">
                <i class="bi bi-wallet2" style="color: #D1D88D;"></i>
                <span>Top Up</span>
            </div>
        </a>
    
        <form action="/logout" method="post" class="menu-item-logout">
            @csrf
            <i class="bi bi-box-arrow-left" style="color: #D1D88D;"></i>
            <button type="submit" class="dropdown-item">Log Out</button>
        </form>
    </div>

</div>