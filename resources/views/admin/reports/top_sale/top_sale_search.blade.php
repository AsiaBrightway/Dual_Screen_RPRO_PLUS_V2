    @php
        $totalQuantity = 0;
        $totalSales = 0;
        $totalOrders = 0;
    @endphp

    @forelse ($top_sale_items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->item_name }}</td>
            <td>{{ $item->menu_category_name }}</td>
            <td>{{ number_format($item->total_sold_qty) }}</td>
            <td>{{ $item->unit_name }}</td>
            <td>{{ number_format($item->sale_price, 2) }}</td>
            <td>{{ $item->total_orders }}</td>
            <td>{{ number_format($item->total_sales_amount, 2) }}</td>
        </tr>
        @php
            $totalQuantity += $item->total_sold_qty;
            $totalSales += $item->total_sales_amount;
            $totalOrders += $item->total_orders;
        @endphp
    @empty
    @endforelse
