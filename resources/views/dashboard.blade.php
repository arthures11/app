<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
<style>
    .overflow-x-auto {
        overflow-x: auto;
    }
</style>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">
                        @if(auth()->user()->type === 1)
                        Admin Dashboard
                        @else
                        Employee Dashboard
                        @endif
                    </h1>

                    <button id="fetch-data" class="bg-blue-500 text-white px-4 py-2 rounded">Fetch All</button>

                    <div class="mt-4">
                        <input id="fetch-id" type="number" placeholder="Enter Order ID" class="border p-2 rounded">
                        <button id="fetch-by-id" class="bg-green-500 text-white px-4 py-2 rounded">Fetch By ID</button>
                    </div>

                    <div id="orders-table" class="hidden mt-4">
                        <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">ID</th>
                                <th class="py-2 px-4 border-b">External ID</th>
                                <th class="py-2 px-4 border-b">Confirmed</th>
                                <th class="py-2 px-4 border-b">Shipping Method</th>
                                <th class="py-2 px-4 border-b">Total Products</th>
                                <th class="py-2 px-4 border-b">Products Description</th>
                                <th class="py-2 px-4 border-b">Currency</th>
                                <th class="py-2 px-4 border-b">Order Sum</th>
                                <th class="py-2 px-4 border-b">Paid</th>
                                <th class="py-2 px-4 border-b">Username</th>
                                <th class="py-2 px-4 border-b">Shipping First Name</th>
                                <th class="py-2 px-4 border-b">Shipping Last Name</th>
                                <th class="py-2 px-4 border-b">Shipping Company</th>
                                <th class="py-2 px-4 border-b">Shipping Street</th>
                                <th class="py-2 px-4 border-b">Shipping Street Number 1</th>
                                <th class="py-2 px-4 border-b">Shipping Street Number 2</th>
                                <th class="py-2 px-4 border-b">Shipping Post Code</th>
                                <th class="py-2 px-4 border-b">Shipping City</th>
                                <th class="py-2 px-4 border-b">Shipping State Code</th>
                                <th class="py-2 px-4 border-b">Shipping State</th>
                                <th class="py-2 px-4 border-b">Shipping Country Code</th>
                                <th class="py-2 px-4 border-b">Shipping Country</th>

                            </tr>
                            </thead>
                            <tbody id="orders-body">
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('fetch-data').addEventListener('click', function() {
            console.log('Button clicked');

            fetch('/api/v1/orders')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    const orders = data.orders;
                    const tbody = document.getElementById('orders-body');
                    tbody.innerHTML = '';

                    orders.forEach(order => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td class="py-2 px-4 border-b">${order.id || 'N/A'}</td>
                        <td class="py-2 px-4 border-b">${order.external_id || 'N/A'}</td>
                        <td class="py-2 px-4 border-b">${order.confirmed === 'True' ? 'Yes' : 'No'}</td>
                        <td class="py-2 px-4 border-b">${order.shipping_method || 'N/A'}</td>
                        <td class="py-2 px-4 border-b">${order.total_products || 0}</td>
                        <td class="py-2 px-4 border-b">
                            ${Array.isArray(order.products) && order.products.length > 0
                            ? order.products.map(product => `
                                    <div>
                                        <strong>Code:</strong> ${product.code}<br>
                                        <strong>Quantity:</strong> ${product.quantity}<br>
                                        <strong>Images:</strong>
                                        ${product.images.length > 0
                                ? product.images.map(image => `<img src="${image}" alt="Product Image" width="50" height="50"><br>`).join('')
                                : 'No images'}
                                    </div>
                                `).join('')
                            : 'No products available.'
                        }
                        </td>
                            <td class="py-2 px-4 border-b">${order.currency || 'N/A'}</td>
                            <td class="py-2 px-4 border-b">${order.order_sum || 0}</td>
                            <td class="py-2 px-4 border-b">${order.paid || 0}</td>
                            <td class="py-2 px-4 border-b">${order.username || 'N/A'}</td>
                    `;

                        tbody.appendChild(row);
                    });

                    document.getElementById('orders-table').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        });

        document.getElementById('fetch-by-id').addEventListener('click', function() {
            const orderId = document.getElementById('fetch-id').value;
            if (!orderId) {
                alert('Please enter an Order ID');
                return;
            }

            console.log('Fetch By ID button clicked with ID:', orderId);

            fetch(`/api/v1/orders/${orderId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data:', data);
                    const order = data.order;
                    const tbody = document.getElementById('orders-body');
                    tbody.innerHTML = '';

                    if (order) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td class="py-2 px-4 border-b">${order.id || 'N/A'}</td>
                        <td class="py-2 px-4 border-b">${order.external_id || 'N/A'}</td>
                        <td class="py-2 px-4 border-b">${order.confirmed === 'True' ? 'Yes' : 'No'}</td>
                        <td class="py-2 px-4 border-b">${order.shipping_method || 'N/A'}</td>
                        <td class="py-2 px-4 border-b">${order.total_products || 0}</td>
                        <td class="py-2 px-4 border-b">
                            ${Array.isArray(order.products) && order.products.length > 0
                            ? order.products.map(product => `
                                    <div>
                                        <strong>Code:</strong> ${product.code}<br>
                                        <strong>Quantity:</strong> ${product.quantity}<br>
                                        <strong>Images:</strong>
                                        ${product.images.length > 0
                                ? product.images.map(image => `<img src="${image}" alt="Product Image" width="50" height="50"><br>`).join('')
                                : 'No images'}
                                    </div>
                                `).join('')
                            : 'No products available.'
                        }
                        </td>
                            <td class="py-2 px-4 border-b">${order.currency || 'N/A'}</td>
                            <td class="py-2 px-4 border-b">${order.order_sum || 0}</td>
                            <td class="py-2 px-4 border-b">${order.paid || 0}</td>
                            <td class="py-2 px-4 border-b">${order.username || 'N/A'}</td>
                        ${order.shipping_first_name ? `<td class="py-2 px-4 border-b">${order.shipping_first_name}</td>` : ''}
                        ${order.shipping_last_name ? `<td class="py-2 px-4 border-b">${order.shipping_last_name}</td>` : ''}
                        ${order.shipping_company ? `<td class="py-2 px-4 border-b">${order.shipping_company}</td>` : ''}
                        ${order.shipping_street ? `<td class="py-2 px-4 border-b">${order.shipping_street}</td>` : ''}
                        ${order.shipping_street_number_1 ? `<td class="py-2 px-4 border-b">${order.shipping_street_number_1}</td>` : ''}
                        ${order.shipping_street_number_2 ? `<td class="py-2 px-4 border-b">${order.shipping_street_number_2}</td>` : ''}
                        ${order.shipping_post_code ? `<td class="py-2 px-4 border-b">${order.shipping_post_code}</td>` : ''}
                        ${order.shipping_city ? `<td class="py-2 px-4 border-b">${order.shipping_city}</td>` : ''}
                        ${order.shipping_state_code ? `<td class="py-2 px-4 border-b">${order.shipping_state_code}</td>` : ''}
                        ${order.shipping_state ? `<td class="py-2 px-4 border-b">${order.shipping_state}</td>` : ''}
                        ${order.shipping_country_code ? `<td class="py-2 px-4 border-b">${order.shipping_country_code}</td>` : ''}
                        ${order.shipping_country ? `<td class="py-2 px-4 border-b">${order.shipping_country}</td>` : ''}
                    `;

                        tbody.appendChild(row);

                        document.getElementById('orders-table').classList.remove('hidden');
                    } else {
                        alert('Order not found');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        });

    </script>



</x-app-layout>
