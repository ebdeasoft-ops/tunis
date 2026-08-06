<div class="table-responsive">
    <table class="table table-hover align-middle text-center mb-0" style="vertical-align: middle;">
        <thead class="bg-light text-dark">
            <tr>
                <th class="py-3 font-weight-bold">#</th>
                <th class="py-3 font-weight-bold">{{ __('Movement No.') }} <br><small class="text-muted">رقم الحركة</small></th>
                <th class="py-3 font-weight-bold">{{ __('To Branch') }} <br><small class="text-muted">إلى فرع</small></th>
                <th class="py-3 font-weight-bold">{{ __('Sender Employee') }} <br><small class="text-muted">الموظف المرسِل</small></th>
                <th class="py-3 font-weight-bold">{{ __('Receiver Employee') }} <br><small class="text-muted">الموظف المستلم</small></th>
                <th class="py-3 font-weight-bold">{{ __('Total Cost') }} <br><small class="text-muted">إجمالي التكلفة</small></th>
                <th class="py-3 font-weight-bold">{{ __('Items Count') }} <br><small class="text-muted">عدد البنود</small></th>
                <th class="py-3 font-weight-bold">{{ __('Date') }} <br><small class="text-muted">التاريخ</small></th>
                <th class="py-3 font-weight-bold">{{ __('Operations') }} <br><small class="text-muted">العمليات</small></th>
            </tr>
        </thead>
        <tbody>
            @forelse($sentMovements as $index => $movement)
                <tr class="transition-hover">
                    <td class="font-weight-bold text-muted">{{ $sentMovements->firstItem() + $index }}</td>
                    <td>
                        <span class="badge badge-pill badge-primary px-3 py-2 font-weight-bold" style="font-size: 0.85rem;">
                            #{{ $movement->id }}
                        </span>
                    </td>
                    <td>
                        <span class="text-dark font-weight-bold">
                            {{ $movement->branchTo->name ?? __('Not Specified') }}
                        </span>
                    </td>
                    <td>
                        <span class="text-secondary">
                            <i class="fas fa-user-shield text-muted ml-1"></i> {{ $movement->userFrom->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="text-secondary">
                            <i class="fas fa-user text-muted ml-1"></i> {{ $movement->userTo->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="text-success font-weight-bold" style="font-size: 0.95rem;">
                            {{ number_format($movement->Totalcost, 2) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-info px-2 py-1">
                            {{ $movement->items->count() }} {{ __('home.Product') }}
                        </span>
                    </td>
                    <td>
                        <span class="text-muted small">
                            <i class="far fa-clock ml-1"></i> {{ $movement->created_at->format('Y-m-d') }}
                            <br>
                            <span class="text-xs">{{ $movement->created_at->format('H:i') }}</span>
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-info px-3 rounded-pill" data-toggle="modal" data-target="#itemsModal{{ $movement->id }}">
                            <i class="fas fa-eye ml-1"></i>  {{ __('home.view_products') }}
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 text-black-50"></i>
                            <p class="mb-0 font-weight-bold">لا توجد حركات إرسال صادرة لهذا الفرع بحسب الفلترة المحددة</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- تذييل الجدول وأزرار الـ Pagination -->
<div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center flex-wrap">
    <div class="text-muted small mb-2 mb-md-0">
        عرض من {{ $sentMovements->firstItem() ?? 0 }} إلى {{ $sentMovements->lastItem() ?? 0 }} من أصل {{ $sentMovements->total() }} حركة
    </div>
    <div>
        {!! $sentMovements->appends(request()->query())->links() !!}
    </div>
</div>
