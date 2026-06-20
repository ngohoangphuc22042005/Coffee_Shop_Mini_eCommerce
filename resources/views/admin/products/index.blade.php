@extends('layouts.app')
@section('content')

    <div class="flex-between mb-3">
        <div>
            <h1 class="page-title" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 24px; height: 24px; fill: none; stroke: var(--primary); stroke-width: 2.5;" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"></path><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"></path></svg>
                Quản lý sản phẩm
            </h1>
            <p class="page-sub">Thêm, sửa, xóa và quản lý danh sách sản phẩm trong cửa hàng</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.bulk-create') }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 0.35rem;">
                <svg style="width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2.5;" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                Thêm nhiều sản phẩm
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.35rem;">
                <svg style="width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2.5;" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"></path></svg>
                Thêm 1 sản phẩm
            </a>
        </div>
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <div class="table-wrapper" style="border:none; border-radius:0;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Topping</th>
                        <th>Bán chạy</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="text-muted text-xs">{{ $product->id }}</td>
                            <td>
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-thumb">
                                @else
                                    <div class="product-thumb-placeholder" style="background: var(--bg-subtle);">
                                        <svg style="width: 24px; height: 24px; fill: none; stroke: var(--text-muted); stroke-width: 1.5;" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 018 0v1a4 4 0 01-4 4h-5m-9-5h14a1 1 0 011 1v7a8 8 0 01-8 8H7a8 8 0 01-8-8V9a1 1 0 011-1z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong style="font-size:0.9rem;">{{ $product->name }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-admin" style="font-size:0.7rem;">{{ $product->category->name ?? 'N/A' }}</span>
                            </td>
                            <td style="font-weight:700; color:var(--primary);">{{ number_format($product->base_price) }}đ</td>
                            <td>
                                @if($product->allow_topping)
                                    <span class="badge badge-completed">Có</span>
                                @else
                                    <span class="badge badge-user">Không</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_bestseller)
                                    <span class="badge badge-pending">Best Seller</span>
                                @else
                                    <span class="badge badge-user">Không</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge badge-completed">Hiển thị</span>
                                @else
                                    <span class="badge badge-cancelled">Ẩn</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex gap-1">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-xs" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                        <svg style="width: 12px; height: 12px; fill: none; stroke: currentColor; stroke-width: 2;" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Sửa
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="delete-product-form" style="display: inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <svg style="width: 12px; height: 12px; fill: none; stroke: currentColor; stroke-width: 2;" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:3rem; color:var(--text-muted);">
                                <svg style="width: 48px; height: 48px; fill: none; stroke: var(--text-muted); stroke-width: 1.5; margin: 0 auto 1rem auto; display: block;" viewBox="0 0 24 24"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"></path></svg>
                                Chưa có sản phẩm nào. <a href="{{ route('admin.products.create') }}" style="color:var(--primary); font-weight:600; text-decoration: none;">Thêm ngay →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-wrap">
        {{ $products->links('pagination::bootstrap-4') }}
    </div>

    {{-- Custom Confirmation Modal --}}
    <div id="delete-confirm-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div style="background: var(--bg-card); padding: 2.25rem 2rem; border-radius: var(--radius-lg); width: 100%; max-width: 420px; box-shadow: var(--shadow-lg); border: 1px solid var(--border); margin: 1rem; text-align: center; animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="width: 56px; height: 56px; background: rgba(220,38,38,0.1); color: var(--danger); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto;">
                <svg style="width: 28px; height: 28px; fill: none; stroke: currentColor; stroke-width: 2;" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text);">Xác nhận xóa sản phẩm</h3>
            <p style="color: var(--text-dim); font-size: 0.95rem; margin-bottom: 1.75rem; line-height: 1.5;">Bạn có chắc chắn muốn xóa sản phẩm này? Thao tác này không thể hoàn tác.</p>
            <div class="flex gap-2" style="justify-content: center;">
                <button type="button" id="btn-cancel-delete" class="btn btn-outline" style="flex: 1; padding: 0.65rem 1rem; font-size: 0.9rem;">Hủy bỏ</button>
                <button type="button" id="btn-confirm-delete" class="btn btn-danger" style="flex: 1; padding: 0.65rem 1rem; font-size: 0.9rem;">Xóa sản phẩm</button>
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
        const deleteForms = document.querySelectorAll('.delete-product-form');
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
