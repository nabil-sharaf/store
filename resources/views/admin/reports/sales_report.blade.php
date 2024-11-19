@extends('admin.layouts.app')

@section('page-title', 'تقرير المبيعات')

@section('content')
    <div class="container">
        <h1 class="text-center text-primary mb-4">تقرير المبيعات</h1>

        <section class="mb-5">
            <h2 class="block-title text-center bg-info text-white py-2">ملخص</h2>
            <div class="p-3 bg-light rounded">
                <p>إجمالي المبيعات: <strong class="text-success">{{ number_format($report['total_sales'], 2) }} جنيه</strong></p>
                <p>عدد الطلبات المكتملة: <strong class="text-success">{{ $report['completed_orders'] }}</strong></p>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="block-title text-center bg-info text-white py-2">حالة الطلبات</h2>
            <div class="p-3 bg-light rounded">
                <ul>
                    @foreach($report['status_breakdown'] as $status => $count)
                        <li>{{ $status }}: <strong>{{ $count }}</strong></li>
                    @endforeach
                </ul>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="block-title text-center bg-info text-white py-2">المبيعات اليومية</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped bg-white">
                    <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">التاريخ</th>
                        <th class="text-center">المبيعات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($report['daily_sales'] as $date => $total)
                        <tr>
                            <td class="text-center">{{ $date }}</td>
                            <td class="text-center">{{ number_format($total, 2) }} جنيه</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="block-title text-center bg-info text-white py-2">أفضل 10 منتجات مبيعًا</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped bg-white">
                    <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">المنتج</th>
                        <th class="text-center">الكمية</th>
                        <th class="text-center">إجمالي المبيعات </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($report['top_products'] as $product)
                        <tr>
                            <td class="text-center">{{ $product['name'] }}</td>
                            <td class="text-center">{{ $product['quantity'] }}</td>
                            <td class="text-center">{{ number_format($product['total'], 2) }} جنيه</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section>
            <h2 class="block-title text-center bg-info text-white py-2">أفضل 10 عملاء</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped bg-white">
                    <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">العميل</th>
                        <th class="text-center">عدد الطلبات</th>
                        <th class="text-center">إجمالي الإنفاق</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($report['top_customers'] as $customer)
                        <tr>
                            <td class="text-center">{{ $customer['name'] }}</td>
                            <td class="text-center">{{ $customer['order_count'] }}</td>
                            <td class="text-center">{{ number_format($customer['total_spent'], 2) }} جنيه</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection

@push('styles')
    <style>
        .block-title {
            font-size: 1.5rem;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
        /* جعل الجداول قابلة للتمرير في حالة الشاشات الصغيرة */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table th, .table td {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        /* التمرير العمودي على الشاشات الصغيرة */
        body {
            overflow-y: auto;
            min-height: 100vh;
        }

        /* تحسين عرض الجداول */
        .table {
            width: 100%;
        }

        /* تحسين مظهر العناوين */
        .block-title {
            font-size: 1.5rem;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }


    </style>
@endpush
