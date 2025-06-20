<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<nav class="fixed w-full flex items-center justify-between px-6 py-4 bg-white text-green-800 shadow-md z-100">
    <div class="flex items-center justify-center space-x-8 ml-5">
        <a href="/">
            <img src="{{ asset('assets/image/Title-Icon.jpeg') }}" alt="Logo" class="h-10 w-auto">
        </a>    

        <div class="flex items-center space-x-8 ml-15">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-green-600 hover:text-green-900 font-medium flex items-center space-x-1 focus:outline-none">
                    <span>Category</span>
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.939l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
                <ul x-show="open" @click.outside="open = false" class="absolute mt-2 w-60 bg-white text-green-800 border-green-200 rounded-md shadow-lg">
                    <li>
                        <a href="/" class="block px-1 py-1 text-gray-700 hover:bg-gray-100 category-list category-all">All</a>
                    </li>
                    @foreach ($categories as $category)
                        <li>
                            <a href="{{ url('/?category=' . $category->category_slug) }}" class="block px-1 py-1 text-green-700 hover:bg-gray-100 category-list">
                                {{ $category->category_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                
            </div>

            <form action="/" method="get" class="flex w-full">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full border-1 border-gray-400 rounded-md px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-green-500 text-left"
                    placeholder="Search">
            </form>

        </div>
    </div>

    <div class="flex items-center space-x-4">
        @auth('customer')
            <a href="/cart" class="text-gray-700 hover:text-green-600 text-xl">
                <i class="bi bi-cart2"></i>
            </a>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center focus:outline-none pl-3">
                    @if(auth('customer')->user()->customer_photo)
                        <img src="{{ asset('storage/customer_photos/' . auth('customer')->user()->customer_photo) }}" 
                            alt="Profile" class="w-11 h-11 rounded-full object-cover mr-2">
                    @else
                        <i class="bi bi-person-circle text-2xl text-gray-700 mr-2"></i>
                    @endif
                </button>

                <ul x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                    <li>
                        <a href="/dashboard/myprofile" class="flex items-center px-0 py-2 text-gray-700 hover:bg-gray-100 my-account">
                            <i class="bi bi-person-circle mr-2"></i> My Account
                        </a>
                    </li>
                    <li><hr class="my-1 border-gray-200"></li>
                    <li>
                        <form action="/logout" method="post">
                            @csrf
                            <button type="submit" class="w-full text-left py-2 text-gray-700 hover:bg-gray-100 flex items-center">
                                <i class="bi bi-box-arrow-right mr-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @else
            <div class="flex items-center space-x-4 ">
                <a href="/login" class="text-gray-700 hover:text-green-600 rounded-lg text-xl pr-4">
                    <i class="bi bi-cart2 block"></i>
                </a>
                <a href="/login" class="px-4 py-2 bg-green-400 text-black hover:bg-green-500 transition rounded-3xl inline-block login-button">
                    Login
                </a>
            </div>
        @endauth
    </div>
</nav>