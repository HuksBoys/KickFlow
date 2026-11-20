<div>
    <label for="name" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">Nama Item <span class="text-[#ef4444]">*</span></label>
    <input type="text" id="name" name="name" value="{{ old('name', $addon?->name) }}" required placeholder="Contoh: Rompi Tim atau Air Mineral" class="w-full bg-[#15201a] border @error('name') border-[#ef4444] @else border-[#24362d] @enderror rounded-lg px-3.5 py-2.5 text-sm text-[#f8fafc] placeholder-[#64748b] focus:outline-none focus:border-[#10b981]">
    @error('name')
        <p class="mt-1.5 text-xs text-[#ef4444]">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="price" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">Harga per Item (Rp) <span class="text-[#ef4444]">*</span></label>
    <div class="relative">
        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-mono text-[#64748b]">Rp</span>
        <input type="number" id="price" name="price" value="{{ old('price', $addon ? (int) $addon->price : null) }}" required min="0" step="500" placeholder="15000" class="w-full bg-[#15201a] border @error('price') border-[#ef4444] @else border-[#24362d] @enderror rounded-lg pl-10 pr-3.5 py-2.5 text-sm font-mono text-[#f8fafc] placeholder-[#64748b] focus:outline-none focus:border-[#10b981]">
    </div>
    @error('price')
        <p class="mt-1.5 text-xs text-[#ef4444]">{{ $message }}</p>
    @enderror
</div>

<label class="flex items-center gap-3 cursor-pointer select-none">
    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $addon?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded bg-[#15201a] border-[#24362d] text-[#10b981] focus:ring-[#10b981]">
    <span>
        <span class="block text-sm font-bold text-[#f8fafc]">Item Aktif</span>
        <span class="block text-xs text-[#94a3b8]">Customer dapat memilih item ini saat membuat booking.</span>
    </span>
</label>
