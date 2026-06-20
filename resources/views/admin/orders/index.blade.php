@extends('layouts.app')
@section('content')
    <div class="flex-between mb-3">
        <h1 style="font-size:1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 22px; height: 22px; fill: none; stroke: var(--primary); stroke-width: 2;" viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"></path><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"></path></svg>
            Quản lý Đơn hàng
        </h1>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>
                            <div>{{ $order->customer_name }}</div>
                            <span class="text-dim text-xs">{{ $order->customer_email }}</span>
                        </td>
                        <td>{{ $order->customer_phone }}</td>
                        <td><strong style="color:var(--success);">{{ number_format($order->total_amount) }}đ</strong></td>
                        <td>
                            @if($order->payment_method === 'cod')
                                <span class="badge badge-user">COD</span>
                            @else
                                <span class="badge badge-processing">Chuyển khoản</span>
                            @endif
                        </td>
                        <td>
                            @if($order->status === 'pending')
                                <span class="badge badge-pending">Chờ xử lý</span>
                            @elseif($order->status === 'processing')
                                <span class="badge badge-processing">Đang làm</span>
                            @elseif($order->status === 'completed')
                                <span class="badge badge-completed">Hoàn thành</span>
                            @else
                                <span class="badge badge-cancelled">Đã hủy</span>
                            @endif
                        </td>
                        <td class="text-dim text-xs">{{ $order->created_at->format('H:i d/m/Y') }}</td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-sm">Xem chi tiết</a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="delete-order-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;" class="text-dim">Chưa có đơn hàng nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $orders->links('pagination::bootstrap-4') }}
    </div>

    {{-- Custom Confirmation Modal --}}
    <div id="delete-confirm-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div style="background: var(--bg-card); padding: 2.25rem 2rem; border-radius: var(--radius-lg); width: 100%; max-width: 420px; box-shadow: var(--shadow-lg); border: 1px solid var(--border); margin: 1rem; text-align: center; animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="width: 56px; height: 56px; background: rgba(220,38,38,0.1); color: var(--danger); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto;">
                <svg style="width: 28px; height: 28px; fill: none; stroke: currentColor; stroke-width: 2;" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text);">Xác nhận xóa đơn hàng</h3>
            <p style="color: var(--text-dim); font-size: 0.95rem; margin-bottom: 1.75rem; line-height: 1.5;">Bạn có chắc chắn muốn xóa đơn hàng này? Thao tác này không thể hoàn tác.</p>
            <div class="flex gap-2" style="justify-content: center;">
                <button type="button" id="btn-cancel-delete" class="btn btn-outline" style="flex: 1; padding: 0.65rem 1rem; font-size: 0.9rem;">Hủy bỏ</button>
                <button type="button" id="btn-confirm-delete" class="btn btn-danger" style="flex: 1; padding: 0.65rem 1rem; font-size: 0.9rem;">Xóa đơn</button>
            </div>
        </div>
    </div>

    <style>
        @keyframes modalFadeIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteForms = document.querySelectorAll('.delete-order-form');
        const modal = document.getElementById('delete-confirm-modal');
        const cancelBtn = document.getElementById('btn-cancel-delete');
        const confirmBtn = document.getElementById('btn-confirm-delete');
        let formToSubmit = null;

        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                formToSubmit = this;
                modal.style.display = 'flex';
            });
        });

        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            formToSubmit = null;
        });

        confirmBtn.addEventListener('click', () => {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
                formToSubmit = null;
            }
        });
    });
</script>
@endpush
@endsection
