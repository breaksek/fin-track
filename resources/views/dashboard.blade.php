<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('FinTrack - Catatan Keuangan') }}
        </h2>
    </x-slot>

    <!-- Pembungkus State Alpine.js (openModal) -->
    <div x-data="{ openModal: false }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Notifikasi Sukses -->
            @if(session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Ringkasan Saldo & Tombol Panggil Form -->
            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex gap-6">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Sisa Saldo</p>
                        <p class="text-xl font-bold text-indigo-600">Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="border-l pl-6">
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Pemasukkan</p>
                        <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalPemasukkan ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="border-l pl-6">
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Pengeluaran</p>
                        <p class="text-xl font-bold text-red-600">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Tombol Memanggil Form Tambah Data -->
                <button @click="openModal = true" type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold text-sm flex items-center">
                    + Tambah Transaksi
                </button>
            </div>

            <!-- Tabel Utama: Riwayat Transaksi -->
            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Riwayat Transaksi</h3>
                    <span class="text-xs text-gray-500">Total: {{ $transactions->count() }} data</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->waktu->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->tipe === 'pemasukkan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($item->tipe) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $item->tipe === 'pemasukkan' ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->keterangan }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Belum ada riwayat transaksi tersimpan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Form Tambah Data (Disembunyikan secara Default) -->
            <div x-show="openModal" 
                 x-transition
                 style="display: none;"
                 class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
                
                <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 relative">
                    <!-- Tombol Close Modal -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Tambah Transaksi Baru</h3>
                        <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                            <input type="datetime-local" name="waktu" value="{{ now()->format('Y-m-d\TH:i') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Transaksi</label>
                            <select name="tipe" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pemasukkan">Pemasukkan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                            <input type="number" name="nominal" step="0.01" placeholder="Contoh: 100000" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <input type="text" name="keterangan" placeholder="Contoh: Gaji / Beli Makan" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex justify-end gap-2 pt-4 border-t">
                            <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-semibold text-sm">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold text-sm">
                                Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>