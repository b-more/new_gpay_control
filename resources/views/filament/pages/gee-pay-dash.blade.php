<x-filament-panels::page>
    <div>
        @if (\Carbon\Carbon::now()->dayOfWeek >= \Carbon\Carbon::MONDAY && \Carbon\Carbon::now()->dayOfWeek <= \Carbon\Carbon::FRIDAY)
            @if (\Carbon\Carbon::now()->hour >= 8 && \Carbon\Carbon::now()->hour < 18)
                <div></div>
            @else
                <div class="p-4 mb-4 text-sm text-red-700 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300" role="alert">
                    <span class="animate-ping absolute inline-flex h-5 w-5 rounded-full bg-red-600 opacity-75"></span><span class="font-medium">Alert!</span> You are accessing your account after office hours, a security flag has been raised.
                </div>
            @endif
        @else
            <div class="p-4 mb-4 text-sm text-red-700 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300" role="alert">
                <span class="animate-ping absolute inline-flex h-5 w-5 rounded-full bg-red-600 opacity-75"></span><span class="font-medium">Alert!</span> You are accessing your account during unofficial working days, a security flag has been raised.
            </div>
        @endif

        @if(Auth::user()->role->id == 1)
            {{--<div id="admin" class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-3 rounded-xl shadow-xl flex items-center justify-between mt-4">
                    <div class="flex space-x-6 items-center">
                        <img src="{{ secure_asset('imgz/cGrate.png') }}" class="w-auto h-12"/>
                        <div>
                            <p class="font-semibold text-base">Utilities Current Float</p>
                            <p class="font-semibold text-xs text-gray-400">{{ $this->konse_konse_float }}</p>
                        </div>
                    </div>

                    <div class="flex space-x-2 items-center">
                        <div class="bg-yellow-200 rounded-md p-2 flex items-center">
                            <p class="text-green-600 font-semibold text-xs">Balance</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-3 rounded-xl shadow-xl flex items-center justify-between mt-4">
                    <div class="flex space-x-6 items-center">
                        <img src="{{ secure_asset('imgz/natsave.png') }}" class="w-auto h-12"/>
                        <div>
                            <p class="font-semibold text-base">Bank Current Balance</p>
                            <p class="font-semibold text-xs text-gray-400">ZMW 0.00</p>
                        </div>
                    </div>

                    <div class="flex space-x-2 items-center">
                        <div class="bg-yellow-200 rounded-md p-2 flex items-center">
                            <p class="text-green-600 font-semibold text-xs">Balance</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-3 rounded-xl shadow-xl flex items-center justify-between mt-4">
                    <div class="flex space-x-6 items-center">
                        <img src="{{ secure_asset('imgz/consumers.png') }}" class="w-auto h-10"/>
                        <div>
                            <p class="font-semibold text-base">Total Consumer</p>
                            <p class="font-semibold text-xs text-gray-400">{{ $this->total_consumer_balances }}</p>
                        </div>
                    </div>

                    <div class="flex space-x-2 items-center">
                        <div class="bg-yellow-200 rounded-md p-2 flex items-center">
                            <p class="text-green-600 font-semibold text-xs">Balance</p>
                        </div>
                    </div>
                </div>
            </div>--}}
            <div id="admin" class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-10">
                <div class="bg-white p-3 rounded-xl shadow-xl flex items-center justify-between mt-4">
                    <div class="flex space-x-6 items-center">
                        <img src="{{ asset('imgz/commission.png') }}" class="w-auto h-10"/>
                        <div>
                            <p class="font-semibold text-base">Total Commissions</p>
                            <p class="font-semibold text-xs text-gray-400">{{ $this->konse_konse_float }}</p>
                        </div>
                    </div>

                    <div class="flex space-x-2 items-center">
                        <div class="bg-yellow-200 rounded-md p-2 flex items-center">
                            <p class="text-green-600 font-semibold text-xs">Balance</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-3 rounded-xl shadow-xl flex items-center justify-between mt-4">
                    <div class="flex space-x-6 items-center">
                        <img src="{{ asset('imgz/finance.png') }}" class="w-auto h-10"/>
                        <div>
                            <p class="font-semibold text-base">Collections</p>
                            <p class="font-semibold text-xs text-gray-400">ZMW 0.00 <span class="text-green-600">
                        </div>
                    </div>

                    <div class="flex space-x-2 items-center">
                        <div class="bg-yellow-200 rounded-md p-2 flex items-center">
                            <p class="text-green-600 font-semibold text-xs">Balance</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-3 rounded-xl shadow-xl flex items-center justify-between mt-4">
                    <div class="flex space-x-6 items-center">
                        <img src="{{ asset('imgz/businesses.png') }}" class="w-auto h-12"/>
                        <div>
                            <p class="font-semibold text-base">Total Businesses</p>
                            <p class="font-semibold text-xs text-gray-400">{{ $this->total_deposit_balances }}</p>
                        </div>
                    </div>

                    <div class="flex space-x-2 items-center">
                        <div class="bg-yellow-200 rounded-md p-2 flex items-center">
                            <p class="text-green-600 font-semibold text-xs">Balance</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(Auth::user()->role->id == 1)
            <div class="grid grid-cols-1 md:grid-cols-3 md:gap-6 h-[378px] mb-10">
                <div class="md:col-span-2 bg-white rounded-lg p-4 shadow-xl">
                    <div class="font-semibold text-lg text-green-900 mb-4">Payments Overview</div>
                    <div>
                        <livewire:transactions-chart />
                    </div>
                </div>

                <div class="md:col-span-1 bg-white rounded-lg p-4 shadow-xl">
                    <div class="font-semibold text-lg text-green-900 mb-4">Total Accounts</div>
                    <div class="group shadow shadow-yellow-300 bg-green-700 bg-opacity-10 hover:bg-[#0B6C14] rounded p-4 w-full">
                        <div class="flex flex-row items-center justify-between">
                            <div class="mb-2 group-hover:text-gray-50 text-gray-700 font-medium text-sm flex flex-row items-center space-x-2">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-4 h-4">
  <path stroke-linecap="round" stroke-linejoin="round"
        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
</svg>

                        </span>
                                <span>Businesses</span>
                            </div>
                            <div class="text-md font-bold text-green-900 text-end group-hover:text-gray-50">
                                {{ $total_businesses }}
                            </div>
                        </div>
                        <div class="flex flex-row items-center justify-between">
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                Approved {{ $active_businesses }}
                            </div>
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                |
                            </div>
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                Pending {{ $pending_businesses }}
                            </div>
                        </div>

                    </div>
                    {{--<div class="group shadow rounded shadow-yellow-300 bg-green-700 bg-opacity-10 p-4 mt-4 hover:bg-[#0B6C14]">
                        <div class="flex flex-row items-center justify-between">
                            <div class="mb-2 text-gray-700 font-medium text-sm flex flex-row items-center space-x-2 group-hover:text-gray-50">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
  <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
</svg>


                        </span>
                                <span>Consumers</span>
                            </div>
                            <div class="text-md font-bold text-green-900 text-end group-hover:text-gray-50">
                                {{ $total_consumers }}
                            </div>
                        </div>

                        <div class="flex flex-row items-center justify-between">
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                Approved {{ $active_consumers }}
                            </div>
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                |
                            </div>
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                Pending {{ $pending_consumers }}
                            </div>
                        </div>
                    </div>--}}

                    <div class="group shadow rounded shadow-blue-300 bg-green-700 bg-opacity-10 p-4 mt-4 hover:bg-[#0B6C14]">
                        <div class="flex flex-row items-center justify-between">
                            <div class="mb-2 text-gray-700 font-medium text-sm flex flex-row items-center space-x-2 group-hover:text-gray-50">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
  <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>



                        </span>
                                <span>System Users</span>
                            </div>
                            <div class="text-md font-bold text-green-900 text-end group-hover:text-gray-50">
                                {{ $total_users }}
                            </div>
                        </div>
                        <div class="flex flex-row items-center justify-between">
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                Approved {{ $active_users }}
                            </div>
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                |
                            </div>
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                Pending {{ $pending_users }}
                            </div>
                        </div>
                    </div>

                    {{--<div class="group shadow rounded shadow-red-600 bg-green-700 bg-opacity-10 p-4 mt-4 hover:bg-red-700">
                        <div class="flex flex-row items-center justify-between">
                            <div class="mb-2 text-gray-700 font-medium text-sm flex flex-row items-center space-x-2 group-hover:text-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                </svg>

                                </span>
                                <span>Disputes</span>
                            </div>
                            <div class="text-md font-bold text-green-900 text-end group-hover:text-gray-50">
                                {{ $total_disputes }}
                            </div>
                        </div>

                        <div class="flex flex-row items-center justify-between">
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                Resolved {{ $active_disputes }}
                            </div>
                            <div class="text-sm font-bold text-green-900 text-end group-hover:text-gray-50">
                                |
                            </div>
                            <div class="text-sm font-bold text-red-700 text-end group-hover:text-gray-50">
                                Pending {{ $pending_disputes }}
                            </div>
                        </div>
                    </div>--}}
                </div>
            </div>
        @elseif(Auth::user()->role->id == 7 || Auth::user()->role->id == 8)
            <div class="w-full py-10">
                <div class="text-2xl font-medium text-green-900">Welcome <span class="font-bold">{{ Auth::user()->name }}</span></div>
                <div class="text-md font-medium text-gray-700">Assigned Role <span class="font-bold">[{{ Auth::user()->role->name }}]</span></div>
                <div class="mt-10">
                    <div>
                        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="relative overflow-hidden rounded-lg bg-white px-4 pt-5 pb-12 shadow sm:px-6 sm:pt-6">
                                <dt>
                                    <div class="absolute rounded-md bg-green-900 p-3">
                                        <!-- Heroicon name: outline/users -->
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 truncate text-sm font-medium text-gray-500">Total Businesses Users</p>
                                </dt>
                                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Client::count() }}</p>

                                    <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                                        <div class="text-sm">
                                            <a href="/clients" class="font-medium text-green-900 hover:text-green-500"> View all<span class="sr-only"> Total Business Users</span></a>
                                        </div>
                                    </div>
                                </dd>
                            </div>

                            <div class="relative overflow-hidden rounded-lg bg-white px-4 pt-5 pb-12 shadow sm:px-6 sm:pt-6">
                                <dt>
                                    <div class="absolute rounded-md bg-green-900 p-3">
                                        <!-- Heroicon name: outline/envelope-open -->
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 truncate text-sm font-medium text-gray-500">Total Business</p>
                                </dt>
                                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Business::count() }}</p>

                                    <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                                        <div class="text-sm">
                                            <a href="/businesses" class="font-medium text-green-600 hover:text-green-500"> View all<span class="sr-only"> Total Businesses</span></a>
                                        </div>
                                    </div>
                                </dd>
                            </div>

                            <div class="relative overflow-hidden rounded-lg bg-white px-4 pt-5 pb-12 shadow sm:px-6 sm:pt-6">
                                <dt>
                                    <div class="absolute rounded-md bg-green-900 p-3">
                                        <!-- Heroicon name: outline/cursor-arrow-rays -->
                                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 truncate text-sm font-medium text-gray-500">Total Webhooks</p>
                                </dt>
                                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Webhook::count() }}</p>

                                    <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                                        <div class="text-sm">
                                            <a href="/webhooks" class="font-medium text-green-600 hover:text-green-500"> View all<span class="sr-only"> Total Webhooks</span></a>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>

                </div>
            </div>
        @endif
        <div class="h-5"></div>
    </div>
</x-filament-panels::page>
