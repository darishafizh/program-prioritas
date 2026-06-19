@extends('layouts.app')

@section('title', 'KNMP - Manajemen Calon Lokasi (Master)')

@section('content')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

@php
 $currentStage = request()->query('stage', 'pengajuan');
 
 $stages = [
 'pengajuan' => ['label' => 'Pengajuan Proposal', 'icon' => 'fa-file-lines'],
 'verif-admin' => ['label' => 'Verif Administrasi', 'icon' => 'fa-clipboard-check'],
 'ba-aktivasi' => ['label' => 'BA Aktivasi', 'icon' => 'fa-signature'],
 'verif-teknis' => ['label' => 'Verif Teknis Lapangan', 'icon' => 'fa-map-location-dot'],
 'ba-calon' => ['label' => 'BA Calon', 'icon' => 'fa-file-contract'],
 'penetapan' => ['label' => 'Penetapan Calon (SK)', 'icon' => 'fa-award'],
 ];
@endphp

<div x-data="calonLokasiManager('{{ $currentStage }}')" x-init="initData()" class="flex flex-col gap-4">
 <!-- Header Section -->
 <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
 <div>
 <h2 class="text-xl font-semibold tracking-tight">Manajemen Calon Lokasi</h2>
 <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">Siklus 1: Dari pengajuan usulan baru hingga ditetapkan menjadi Lokasi Definitif.</p>
 </div>

 <!-- Action Buttons -->
 <div class="flex flex-wrap items-center gap-3">
 @if($currentStage === 'pengajuan')
 <a href="{{ route('program.master.calon-lokasi.create') }}" class="bg-teal-light hover:bg-teal-600 text-white rounded-xl px-4 py-2.5 text-xs font-semibold transition-all flex items-center justify-between gap-2 shadow-sm whitespace-nowrap"> 
 Tambah Pengajuan <i class="fa-solid fa-plus bg-white/20 p-1.5 rounded-lg"></i> 
 </a>
 @endif
 </div>
 </div>

 <!-- Stepper / Tabs -->
 <div class="bg-white dark:bg-bgSurface-dark rounded-2xl border border-gray-100 dark:border-gray-800 p-2 overflow-hidden">
 <div class="flex items-center w-full">
 @foreach($stages as $key => $data)
 <a href="?stage={{ $key }}" class="flex items-center group relative py-2 px-2 {{ $loop->last ? '' : 'flex-1' }}">
 <div class="flex items-center gap-2 relative z-10 shrink-0">
 <div class="w-7 h-7 rounded-full flex items-center justify-center font-medium text-xs transition-colors
 {{ $currentStage === $key ? 'bg-teal-light text-white ' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 group-hover:bg-teal-light/20 group-hover:text-teal-light' }}">
 <i class="fa-solid {{ $data['icon'] }} text-[10px]"></i>
 </div>
 <span class="font-medium text-xs transition-colors whitespace-normal max-w-[90px] leading-[1.1] {{ $currentStage === $key ? 'text-teal-light' : 'text-textMuted-light dark:text-textMuted-dark group-hover:text-teal-light' }}">
 {{ $data['label'] }}
 </span>
 </div>
 @if(!$loop->last)
 <div class="flex-1 h-px bg-gray-300 dark:bg-gray-700 mx-2 min-w-[10px]"></div>
 @endif
 
 @if($currentStage === $key)
 <div class="absolute inset-0 bg-teal-light/5 dark:bg-teal-600/10 rounded-xl"></div>
 @endif
 </a>
 @endforeach
 </div>
 </div>

 <!-- Main Data Table -->
 <div class="bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl overflow-hidden flex flex-col mt-2">
 <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50 dark:bg-gray-800/20">
 <div class="flex items-center gap-2 text-sm text-textMuted-light dark:text-textMuted-dark">
 <span>Tampilkan</span>
 <select class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-lg px-2 py-1.5 focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark font-medium ">
 <option>10</option>
 </select>
 <span>entri</span>
 </div>

 <div class="relative w-full sm:w-64">
 <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
 <input x-model="searchQuery" type="text" placeholder="Cari data lokasi..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:border-teal-light outline-none transition-all ">
 </div>
 </div>

 <div class="overflow-x-auto min-h-[300px]">
 
 <!-- TABLE 1: PENGAJUAN PROPOSAL -->
 <table x-show="currentStage === 'pengajuan'" class="w-full text-left text-xs whitespace-nowrap">
 <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
 <tr>
 <th class="px-6 py-4">Pengusul</th>
 <th class="px-6 py-4">Usulan Lokasi</th>
 <th class="px-6 py-4">Tanggal Pengajuan</th>
 <th class="px-6 py-4 text-center">Dokumen</th>
 <th class="px-6 py-4 text-center">Aksi</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
 <template x-for="item in filterData(proposals)" :key="item.id">
 <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
 <td class="px-6 py-4"><a href="#" class="font-medium text-teal-light hover:underline" x-text="item.idUser"></a></td>
 <td class="px-6 py-4">
 <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa"></div>
 <div class="text-[11px] text-textMuted-light dark:text-textMuted-dark mt-0.5" x-text="`${item.kecamatan}, ${item.kabupaten}, ${item.provinsi}`"></div>
 </td>
 <td class="px-6 py-4 text-textMuted-light" x-text="item.tanggal"></td>
 <td class="px-6 py-4 text-center">
  <a :href="item.dokumen_url || 'https://drive.google.com/drive'" target="_blank" class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors inline-flex items-center justify-center mx-auto" title="Lihat Dokumen di Google Drive"><i class="fa-brands fa-google-drive"></i></a>
 </td>
 <td class="px-6 py-4 text-center">
 <div class="flex items-center justify-center gap-2">
    <button @click="openDetailModal(item)" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center" title="Detail"><i class="fa-solid fa-eye"></i></button>
    <template x-if="item.status === 'Menunggu Review' || item.status === undefined || item.status === null">
        <button @click="verifyProposalDirect(item)" class="w-8 h-8 rounded-md bg-success/10 text-success hover:bg-success hover:text-white transition-colors flex items-center justify-center" title="Terima Proposal"><i class="fa-solid fa-check"></i></button>
    </template>
 </div>
 </td>
 </tr>
 </template>
 </tbody>
 </table>

 <!-- TABLE 2: VERIFIKASI ADMINISTRASI -->
 <table x-show="currentStage === 'verif-admin'" style="display: none;" class="w-full text-left text-xs whitespace-nowrap">
 <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
 <tr>
 <th class="px-6 py-4">ID User</th>
 <th class="px-6 py-4">Usulan Lokasi</th>
 <th class="px-6 py-4 text-center">Dokumen</th>
 <th class="px-6 py-4">Nilai Skala Kriteria</th>
 <th class="px-6 py-4">Status</th>
 <th class="px-6 py-4 text-center">Aksi</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
 <template x-for="item in filterData(verifList)" :key="item.id">
 <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
 <td class="px-6 py-4"><span class="font-medium text-teal-light cursor-pointer hover:underline" x-text="item.idUser"></span></td>
 <td class="px-6 py-4">
 <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa"></div>
 <div class="text-[11px] text-textMuted-light dark:text-textMuted-dark mt-0.5" x-text="item.kabupaten"></div>
 </td>
 <td class="px-6 py-4 text-center">
  <button class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto" title="Lihat Dokumen"><i class="fa-solid fa-file-pdf"></i></button>
 </td>
 <td class="px-6 py-4 font-medium text-teal-light" x-text="item.nilaiSkala"></td>
 <td class="px-6 py-4">
 <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-light" x-text="item.status"></span>
 </td>
 <td class="px-6 py-4 text-center">
 <button @click="openDetailModal(item)" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto" title="Detail"><i class="fa-solid fa-eye"></i></button>
 </td>
 </tr>
 </template>
 </tbody>
 </table>

 <!-- TABLE 3: BA AKTIVASI -->
 <table x-show="currentStage === 'ba-aktivasi'" style="display: none;" class="w-full text-left text-xs whitespace-nowrap">
 <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
 <tr>
 <th class="px-6 py-4">ID User</th>
 <th class="px-6 py-4">Usulan Lokasi</th>
 <th class="px-6 py-4 text-center">Berita Acara</th>
 <th class="px-6 py-4">Status</th>
 <th class="px-6 py-4 text-center">Aksi</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
 <template x-for="item in filterData(baAktivasiList)" :key="item.id">
 <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
 <td class="px-6 py-4"><span class="font-medium text-teal-light cursor-pointer hover:underline" x-text="item.idUser"></span></td>
 <td class="px-6 py-4">
 <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa"></div>
 <div class="text-[11px] text-textMuted-light mt-0.5" x-text="item.kabupaten"></div>
 </td>
 <td class="px-6 py-4 text-center">
  <button class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto" title="Berita Acara"><i class="fa-solid fa-file-signature"></i></button>
 </td>
 <td class="px-6 py-4">
 <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-success/10 text-success" x-text="item.status"></span>
 </td>
 <td class="px-6 py-4 text-center">
 <button @click="openDetailModal(item)" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto" title="Detail"><i class="fa-solid fa-eye"></i></button>
 </td>
 </tr>
 </template>
 </tbody>
 </table>

 <!-- TABLE 4: VERIFIKASI TEKNIS LAPANGAN -->
 <table x-show="currentStage === 'verif-teknis'" style="display: none;" class="w-full text-left text-xs whitespace-nowrap">
 <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
 <tr>
 <th class="px-6 py-4">ID User</th>
 <th class="px-6 py-4">Usulan Lokasi</th>
 <th class="px-6 py-4 text-center">Dokumen</th>
 <th class="px-6 py-4">Nilai Skala Kriteria</th>
 <th class="px-6 py-4">Status</th>
 <th class="px-6 py-4 text-center">Aksi</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
 <template x-for="item in filterData(verifTeknisList)" :key="item.id">
 <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
 <td class="px-6 py-4"><span class="font-medium text-teal-light cursor-pointer hover:underline" x-text="item.idUser"></span></td>
 <td class="px-6 py-4">
 <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa"></div>
 </td>
 <td class="px-6 py-4 text-center">
  <button class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto" title="Lihat Dokumen"><i class="fa-solid fa-file-pdf"></i></button>
 </td>
 <td class="px-6 py-4 font-medium text-teal-light" x-text="item.nilaiSkala"></td>
 <td class="px-6 py-4">
 <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-teal-light/10 text-teal-600 dark:text-teal-400" x-text="item.status"></span>
 </td>
 <td class="px-6 py-4 text-center">
 <button @click="openDetailModal(item)" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto" title="Detail"><i class="fa-solid fa-eye"></i></button>
 </td>
 </tr>
 </template>
 </tbody>
 </table>

 <!-- TABLE 5: BA CALON -->
 <table x-show="currentStage === 'ba-calon'" style="display: none;" class="w-full text-left text-xs whitespace-nowrap">
 <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
 <tr>
 <th class="px-6 py-4">ID User</th>
 <th class="px-6 py-4">Usulan Lokasi</th>
 <th class="px-6 py-4 text-center">Berita Acara</th>
 <th class="px-6 py-4">Nilai Skala Kriteria</th>
 <th class="px-6 py-4">Status</th>
 <th class="px-6 py-4 text-center">Aksi</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
 <template x-for="item in filterData(baCalonList)" :key="item.id">
 <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
 <td class="px-6 py-4"><span class="font-medium text-teal-light cursor-pointer hover:underline" x-text="item.idUser"></span></td>
 <td class="px-6 py-4">
 <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa"></div>
 </td>
 <td class="px-6 py-4 text-center">
  <button class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto" title="BA Calon"><i class="fa-solid fa-file-contract"></i></button>
 </td>
 <td class="px-6 py-4 font-medium text-teal-light" x-text="item.nilaiSkala"></td>
 <td class="px-6 py-4">
 <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-warning/10 text-warning" x-text="item.status"></span>
 </td>
 <td class="px-6 py-4 text-center">
 <button @click="openDetailModal(item)" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto" title="Detail"><i class="fa-solid fa-eye"></i></button>
 </td>
 </tr>
 </template>
 </tbody>
 </table>

 <!-- TABLE 6: PENETAPAN CALON -->
 <table x-show="currentStage === 'penetapan'" style="display: none;" class="w-full text-left text-xs whitespace-nowrap">
 <thead class="bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800">
 <tr>
 <th class="px-6 py-4">ID User</th>
 <th class="px-6 py-4">Usulan Lokasi</th>
 <th class="px-6 py-4 text-center">Dokumen</th>
 <th class="px-6 py-4">Nilai Skala Kriteria</th>
 <th class="px-6 py-4">Status</th>
 <th class="px-6 py-4 text-center">Aksi</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-bgSurface-dark">
 <template x-for="item in filterData(penetapanList)" :key="item.id">
 <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
 <td class="px-6 py-4"><span class="font-medium text-teal-light cursor-pointer hover:underline" x-text="item.idUser"></span></td>
 <td class="px-6 py-4">
 <div class="font-medium text-textMain-light dark:text-textMain-dark" x-text="item.desa"></div>
 <div class="text-[11px] text-textMuted-light mt-0.5" x-text="item.kabupaten"></div>
 </td>
 <td class="px-6 py-4 text-center">
  <button class="w-8 h-8 rounded-md bg-teal-light/10 text-teal-light hover:bg-teal-light hover:text-white transition-colors flex items-center justify-center mx-auto" title="Lihat Dokumen"><i class="fa-solid fa-file-pdf"></i></button>
 </td>
 <td class="px-6 py-4 font-medium text-teal-light" x-text="item.nilaiSkala"></td>
 <td class="px-6 py-4">
 <span class="px-2.5 py-1 rounded-md text-[0.7rem] font-medium bg-success/10 text-success border border-success/20 w-max" x-text="item.status"></span>
 </td>
 <td class="px-6 py-4 text-center">
 <button @click="openDetailModal(item)" class="w-8 h-8 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-teal-light hover:bg-teal-light/10 transition-colors flex items-center justify-center mx-auto" title="Detail"><i class="fa-solid fa-eye"></i></button>
 </td>
 </tr>
 </template>
 </tbody>
 </table><!-- SEMUA MODALS DI BAWAH SINI -->

 <!-- MODAL: Preview PDF Pengajuan -->
 <div x-show="showPreviewModal" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
 <div class="flex items-center justify-center min-h-screen p-0 sm:p-4">
 <div x-show="showPreviewModal" x-transition.opacity class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity" @click="showPreviewModal = false"></div>
 
 <div x-show="showPreviewModal" x-transition.scale.origin.center class="relative z-10 bg-bgSurface-light dark:bg-bgSurface-dark sm:rounded-3xl flex flex-col md:flex-row w-full h-full sm:h-[90vh] sm:max-w-6xl border border-gray-100 dark:border-gray-800 overflow-hidden">
 <!-- PDF Viewer -->
 <div class="w-full md:w-3/5 bg-gray-200 dark:bg-gray-900 flex flex-col border-r border-gray-100 dark:border-gray-800">
 <div class="px-4 py-3 bg-gray-800 text-white flex justify-between items-center shrink-0">
 <div class="font-medium text-sm flex items-center gap-2"><i class="fa-solid fa-file-pdf text-danger"></i> Dokumen Proposal</div>
 </div>
 <div class="flex-1 overflow-auto p-4 md:p-8 flex items-start justify-center">
 <div class="bg-white w-full max-w-2xl h-[800px] flex flex-col p-8 border border-gray-300">
 <div class="text-center border-b-2 border-black pb-4 mb-6">
 <h1 class="text-base font-medium uppercase">PROPOSAL PEMBANGUNAN KNMP</h1>
 <h2 class="text-xl font-semibold uppercase" x-text="activeProposal?.desa"></h2>
 </div>
 <div class="space-y-4 text-justify font-serif">
 <p>Yang bertanda tangan di bawah ini, selaku pimpinan daerah mengajukan usulan penetapan lokasi...</p>
 </div>
 </div>
 </div>
 </div>
 <!-- Detail Kanan -->
 <div class="w-full md:w-2/5 flex flex-col bg-white dark:bg-bgSurface-dark">
 <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center shrink-0">
 <h3 class="text-base font-medium text-textMain-light dark:text-textMain-dark">Detail Verifikasi</h3>
 <button @click="showPreviewModal = false" class="text-gray-400 hover:text-danger transition-colors bg-gray-100 dark:bg-gray-800 w-8 h-8 rounded-md flex items-center justify-center"><i class="fa-solid fa-xmark"></i></button>
 </div>

 <div class="flex-1 overflow-y-auto p-6">
 <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-5 mb-6 border border-gray-100 dark:border-gray-700">
 <h4 class="font-medium text-base mb-1" x-text="activeProposal?.desa"></h4>
 <p class="text-sm text-textMuted-light mb-4" x-text="`${activeProposal?.kecamatan}, ${activeProposal?.kabupaten}`"></p>
 <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-warning/10 text-warning" x-text="activeProposal?.status"></span>
 </div>

 <h4 class="font-medium text-sm uppercase text-textMuted-light dark:text-textMuted-dark mb-4 tracking-wider flex items-center gap-2"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Tindakan</h4>
 <div class="relative border-l-2 border-gray-100 dark:border-gray-700 ml-3 space-y-6">
 <template x-for="(hist, index) in activeProposal?.history" :key="index">
 <div class="relative pl-6">
 <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white dark:bg-gray-900 border-2" :class="hist.type === 'upload' ? 'border-teal-light' : 'border-success'"></div>
 <div class="text-xs text-textMuted-light mb-1" x-text="hist.time"></div>
 <div class="font-medium text-sm text-textMain-light dark:text-textMain-dark mb-1" x-text="hist.user"></div>
 <div class="text-sm text-gray-600 dark:text-gray-400" x-text="hist.action"></div>
 </div>
 </template>
 </div>
 </div>

 <div class="p-6 border-t border-gray-100 dark:border-gray-800 shrink-0 bg-white dark:bg-bgSurface-dark">
 <template x-if="activeProposal?.status === 'Menunggu Review'">
 <div class="flex gap-3">
 <button @click="verifyProposal('Ditolak', '')" class="flex-1 px-4 py-2.5 border border-danger text-danger hover:bg-danger hover:text-white rounded-md text-xs font-medium transition-colors flex items-center justify-between gap-2"> Tolak <i class="fa-solid fa-xmark"></i> </button>
 <button @click="verifyProposal('Diverifikasi', '')" class="flex-1 px-4 py-2.5 bg-teal-light hover:bg-teal-600 text-white rounded-md text-xs font-medium transition-colors flex items-center justify-between gap-2"> Verifikasi <i class="fa-solid fa-check-double"></i> </button>
 </div>
 </template>
 <template x-if="activeProposal?.status !== 'Menunggu Review'">
 <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl text-center border border-gray-100 dark:border-gray-700">
 <p class="text-sm font-medium text-textMuted-light dark:text-textMuted-dark"><i class="fa-solid fa-lock text-gray-400"></i> Pengajuan sudah diproses.</p>
 </div>
 </template>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- MODAL 2: Form Ceklis Verifikasi Administrasi -->
 <div x-show="showChecklistModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
 <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
 <div x-show="showChecklistModal" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showChecklistModal = false"></div>
 <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
 
 <div x-show="showChecklistModal" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full border border-gray-100 dark:border-gray-800">
 <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
 <div>
 <h3 class="text-base font-medium text-textMain-light dark:text-textMain-dark">Checklist Verifikasi Administrasi</h3>
 <p class="text-xs font-normal text-teal-light font-medium mt-1" x-text="activeVerif?.desa"></p>
 </div>
 <button @click="showChecklistModal = false" class="text-gray-400 hover:text-danger transition-colors bg-white dark:bg-gray-800 w-8 h-8 rounded-md flex items-center justify-center border border-gray-100 dark:border-gray-700 "><i class="fa-solid fa-xmark"></i></button>
 </div>
 
 <div class="px-6 py-5 bg-white dark:bg-bgSurface-dark">
 <p class="text-sm text-textMuted-light dark:text-textMuted-dark mb-4">Pastikan dokumen pendukung yang diunggah oleh daerah sesuai dengan pedoman teknis KNMP.</p>
 
 <div class="border border-gray-100 dark:border-gray-700 rounded-2xl overflow-hidden divide-y divide-gray-100 dark:divide-gray-800">
 <template x-for="(doc, idx) in activeVerif?.documents" :key="idx">
 <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
 <div class="flex items-start gap-3 flex-1">
 <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-light flex items-center justify-center shrink-0"><i class="fa-regular fa-file-lines text-base"></i></div>
 <div>
 <h4 class="font-medium text-sm text-textMain-light dark:text-textMain-dark" x-text="doc.name"></h4>
 <p class="text-xs text-textMuted-light mt-0.5" x-text="doc.desc"></p>
 <button class="text-[0.7rem] font-medium text-teal-light hover:underline mt-1 flex items-center justify-between gap-1"> Lihat File <i class="fa-solid fa-paperclip"></i> </button>
 </div>
 </div>
 <div class="shrink-0 flex items-center">
 <label class="relative inline-flex items-center cursor-pointer">
 <input type="checkbox" class="sr-only peer" x-model="doc.isValid" @change="updateChecklistProgress()">
 <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-success"></div>
 <span class="ml-3 text-sm font-medium" :class="doc.isValid ? 'text-success' : 'text-gray-400'" x-text="doc.isValid ? 'Sesuai' : 'Belum Sesuai'"></span>
 </label>
 </div>
 </div>
 </template>
 </div>
 </div>
 
 <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
 <div class="flex items-center gap-3">
 <div class="w-10 h-10 rounded-full border-4 flex items-center justify-center font-medium text-xs"
 :class="activeVerif?.checkedDocs === activeVerif?.totalDocs ? 'border-success text-success bg-success/10' : 'border-warning text-warning bg-warning/10'"
 x-text="`${activeVerif?.checkedDocs}/${activeVerif?.totalDocs}`">
 </div>
 <div class="text-sm">
 <div class="font-medium text-textMain-light dark:text-textMain-dark">Progres Verifikasi</div>
 <div class="text-xs text-success font-medium" x-show="activeVerif?.checkedDocs === activeVerif?.totalDocs">Dokumen lengkap, siap diterbitkan BA!</div>
 </div>
 </div>

 <button @click="terbitkanBA()" class="px-5 py-2.5 rounded-md text-xs font-medium transition-all flex items-center justify-between gap-2"
 :class="activeVerif?.checkedDocs === activeVerif?.totalDocs ? 'bg-teal-light hover:bg-teal-600 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-500 cursor-not-allowed'"
 :disabled="activeVerif?.checkedDocs !== activeVerif?.totalDocs"> Terbitkan BA <i class="fa-solid fa-file-signature"></i> </button>
 </div>
 </div>
 </div>
 </div>

 <!-- MODAL 3: Upload BA Tertanda Tangan -->
 <div x-show="showUploadBAModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
 <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
 <div x-show="showUploadBAModal" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showUploadBAModal = false"></div>
 <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
 <div x-show="showUploadBAModal" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom bg-bgSurface-light dark:bg-bgSurface-dark rounded-3xl text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-100 dark:border-gray-800">
 <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
 <h3 class="text-base font-medium text-textMain-light dark:text-textMain-dark">Upload Dokumen BA</h3>
 <button @click="showUploadBAModal = false" class="text-gray-400 hover:text-danger transition-colors"><i class="fa-solid fa-xmark text-base"></i></button>
 </div>
 <div class="px-6 py-5">
 <div class="space-y-4">
 <p class="text-sm text-textMuted-light">Silakan unggah dokumen Berita Acara Aktivasi (<span class="font-medium text-teal-light" x-text="activeBa?.noBa"></span>) yang telah ditandatangani oleh Pemda setempat.</p>
 
 <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-teal-light/5 hover:border-teal-light transition-colors cursor-pointer">
 <div class="flex flex-col items-center justify-center pt-5 pb-6">
 <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
 <p class="mb-1 text-sm text-textMuted-light dark:text-textMuted-dark"><span class="font-medium text-teal-light">Klik untuk upload</span> atau drag and drop</p>
 <p class="text-xs text-gray-500">PDF (Maks. 5MB)</p>
 </div>
 <input type="file" class="hidden" accept=".pdf" @change="uploadBaFile = $event.target.files[0].name" />
 </label>
 <p x-show="uploadBaFile" class="text-sm text-success mt-2 font-medium text-center" x-text="`File: ${uploadBaFile}`"></p>
 </div>
 </div>
 <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800 flex justify-end gap-3">
 <button @click="showUploadBAModal = false" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-textMain-light rounded-md text-xs font-medium transition-colors">Batal</button>
 <button @click="submitUploadBA()" class="px-4 py-2 bg-teal-light hover:bg-teal-600 text-white rounded-md text-xs font-medium transition-colors flex items-center justify-between gap-2"> Selesaikan BA <i class="fa-solid fa-check"></i> </button>
 </div>
 </div>
 </div>
 </div>
 <!-- MODAL 4: Tampilkan Semua Data Detail (Eye Catching) -->
 <div x-show="showDetailDataModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
 <div class="flex items-center justify-center min-h-screen p-4 text-center">
 <!-- Background overlay -->
 <div x-show="showDetailDataModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 backdrop-blur-sm transition-opacity" @click="showDetailDataModal = false" aria-hidden="true"></div>

 <!-- Modal Panel -->
 <div x-show="showDetailDataModal" 
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
      x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
      x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
      class="relative z-10 bg-bgSurface-light dark:bg-bgSurface-dark rounded-2xl sm:rounded-3xl flex flex-col w-full h-auto max-h-[90vh] sm:max-w-3xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-2xl text-left">
 <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gradient-to-r from-teal-light/10 to-transparent">
 <div>
 <h3 class="text-base font-semibold text-textMain-light dark:text-textMain-dark flex items-center gap-2"><i class="fa-solid fa-map-location-dot text-teal-light"></i> Rincian Calon Lokasi</h3>
 <p class="text-[11px] text-textMuted-light mt-0.5" x-text="activeDetail ? `${activeDetail.desa}, ${activeDetail.kecamatan}, ${activeDetail.kabupaten}, ${activeDetail.provinsi}` : ''"></p>
 </div>
 <button @click="showDetailDataModal = false" class="text-gray-400 hover:text-danger transition-colors bg-white dark:bg-gray-800 w-7 h-7 rounded-md flex items-center justify-center shadow-sm border border-gray-100 dark:border-gray-700"><i class="fa-solid fa-xmark text-sm"></i></button>
 </div>
 
 <div class="overflow-y-auto p-5 bg-white dark:bg-bgSurface-dark">
 <template x-if="activeDetail && activeDetail.detail">
 <div class="space-y-6 max-w-3xl mx-auto">
 
 <!-- Pengisi Data -->
 <div>
 <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-100 dark:border-gray-800">
 <div class="w-6 h-6 rounded-md bg-blue-50/50 text-blue-500 flex items-center justify-center"><i class="fa-solid fa-user-tie text-[10px]"></i></div>
 <h4 class="font-medium text-sm text-textMain-light dark:text-textMain-dark">Informasi Pengisi</h4>
 </div>
 <div class="flex flex-row gap-6">
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Nama</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="activeDetail.detail.nama_pengisi || '-'"></div>
 </div>
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Jabatan</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="activeDetail.detail.jabatan_pengisi || '-'"></div>
 </div>
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Kontak</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="activeDetail.detail.no_hp_pengisi || '-'"></div>
 </div>
 </div>
 </div>

 <!-- Karakteristik Fisik -->
 <div class="mt-6">
 <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-100 dark:border-gray-800">
 <div class="w-6 h-6 rounded-md bg-amber-50/50 text-amber-500 flex items-center justify-center"><i class="fa-solid fa-mountain text-[10px]"></i></div>
 <h4 class="font-medium text-sm text-textMain-light dark:text-textMain-dark">Karakteristik Fisik</h4>
 </div>
 <div class="flex flex-row gap-6 mb-4">
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Luas & Dimensi Lahan</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="\`\${activeDetail.pengajuan?.luas_lahan || 0} m² (\${activeDetail.pengajuan?.panjang_lahan || 0}m x \${activeDetail.pengajuan?.lebar_lahan || 0}m)\`"></div>
 </div>
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Kemiringan</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="\`\${activeDetail.pengajuan?.kemiringan_lahan || 0}°\`"></div>
 </div>
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Tekstur & Salinitas</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="\`\${activeDetail.pengajuan?.tekstur_tanah || '-'} | \${activeDetail.pengajuan?.salinitas_air || '-'}\`"></div>
 </div>
 </div>
 <div class="flex flex-row gap-6 mb-4">
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Jarak dari Pantai</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="activeDetail.pengajuan?.jarak_pantai ? \`\${activeDetail.pengajuan?.jarak_pantai} m\` : '-'"></div>
 </div>
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Jarak & Lebar Sungai (Area DAS)</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="(activeDetail.pengajuan?.jarak_sungai || activeDetail.pengajuan?.lebar_sungai) ? \`\${activeDetail.pengajuan?.jarak_sungai || '-'} m | L: \${activeDetail.pengajuan?.lebar_sungai || '-'} m\` : '-'"></div>
 </div>
 <div class="flex-1"></div>
 </div>
 <div class="flex flex-row gap-6">
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Titik Koordinat</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="activeDetail.lat && activeDetail.lng ? \`\${activeDetail.lat}, \${activeDetail.lng}\` : '-'"></div>
 </div>
 <div class="flex-[2]">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Akses Mobilitas Material</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="activeDetail.detail.is_pasang_surut || '-'"></div>
 </div>
 </div>
 </div>

 <!-- Status Kawasan -->
 <div class="mt-6">
 <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-100 dark:border-gray-800">
 <div class="w-6 h-6 rounded-md bg-teal-50/50 text-teal-600 flex items-center justify-center"><i class="fa-solid fa-shield-halved text-[10px]"></i></div>
 <h4 class="font-medium text-sm text-textMain-light dark:text-textMain-dark">Status Kawasan</h4>
 </div>
 <div class="flex flex-row gap-6 mb-4">
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Kepemilikan</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="activeDetail.detail.status_kepemilikan || '-'"></div>
 </div>
 <div class="flex-1"></div>
 <div class="flex-1">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-1">Kesesuaian RTRW</div>
 <div class="text-[13px] font-medium text-gray-800 dark:text-gray-200" x-text="activeDetail.detail.kesesuaian_rtrw || '-'"></div>
 </div>
 </div>
 <div>
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-2">Kriteria Khusus</div>
 <div class="flex flex-row flex-wrap gap-1.5">
 <span class="px-2 py-0.5 rounded text-[10px] font-medium transition-colors" :class="activeDetail.detail.is_mangrove === 'Ya' ? 'bg-success/10 text-success' : 'bg-gray-50 text-gray-400 border border-gray-100'">Area Mangrove</span>
 <span class="px-2 py-0.5 rounded text-[10px] font-medium transition-colors" :class="activeDetail.detail.is_konservasi === 'Ya' ? 'bg-success/10 text-success' : 'bg-gray-50 text-gray-400 border border-gray-100'">Zona Konservasi</span>
 <span class="px-2 py-0.5 rounded text-[10px] font-medium transition-colors" :class="activeDetail.detail.is_hutan_lindung === 'Ya' ? 'bg-success/10 text-success' : 'bg-gray-50 text-gray-400 border border-gray-100'">Hutan Lindung</span>
 <span class="px-2 py-0.5 rounded text-[10px] font-medium transition-colors" :class="activeDetail.detail.is_kawasan_budidaya === 'Ya' ? 'bg-success/10 text-success' : 'bg-gray-50 text-gray-400 border border-gray-100'">Kawasan Budidaya</span>
 <span class="px-2 py-0.5 rounded text-[10px] font-medium transition-colors" :class="activeDetail.detail.is_das === 'Ya' ? 'bg-success/10 text-success' : 'bg-gray-50 text-gray-400 border border-gray-100'">Area DAS</span>
 </div>
 </div>
 </div>

 <!-- Dokumen Pendukung -->
 <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800">
 <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium mb-2">Tautan Dokumen Pendukung</div>
 <template x-if="activeDetail.dokumen">
 <a :href="activeDetail.dokumen" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-light/10 text-teal-light rounded-lg text-xs font-medium hover:bg-teal-light hover:text-white transition-colors">
 <i class="fa-solid fa-folder-open"></i> Buka Tautan Dokumen
 </a>
 </template>
 <template x-if="!activeDetail.dokumen">
 <span class="text-xs text-gray-400 italic">Tidak ada tautan dokumen.</span>
 </template>
 </div>
 
 </div>
 </template>
 <template x-if="!activeDetail || !activeDetail.detail">
 <div class="flex flex-col items-center justify-center h-48 text-gray-400">
 <i class="fa-solid fa-circle-info text-4xl mb-3 text-gray-300"></i>
 <p class="text-sm">Data detail kuesioner tidak tersedia.</p>
 </div>
 </template>
 </div>
 </div>
 </div>
 </div>
    </div>

    <!-- Toast Notification has been moved to a global layout component -->
</div>

<script>
 document.addEventListener('alpine:init', () => {
 Alpine.data('calonLokasiManager', (initialStage) => ({
 currentStage: initialStage,
 // State filter pencarian
 searchQuery: '',

 // Toast Notification
 // (State handled globally via x-toast-notification component)

 // Pengajuan
 proposals: [], showPreviewModal: false, activeProposal: null,

 // Verif Admin
 verifList: [], showChecklistModal: false, activeVerif: null,

 // BA Aktivasi
 baAktivasiList: [], showUploadBAModal: false, activeBa: null, uploadBaFile: '',

 // Detail Data
 showDetailDataModal: false, activeDetail: null,

 // Verifikasi Teknis Lapangan
 verifTeknisList: [],

 // BA Calon
 baCalonList: [],

 // Penetapan Calon
 penetapanList: [],

 // Data Master Wilayah
 provinces: [], regencies: [], districts: [], villages: [],

 initData() {
 // Data diambil secara real-time dari database via Controller
 this.proposals = @json($proposals);
 this.verifList = @json($verifList);
 this.baAktivasiList = @json($baAktivasiList);
 this.verifTeknisList = @json($verifTeknisList);
 this.baCalonList = @json($baCalonList);
 this.penetapanList = @json($penetapanList);
 },

 filterData(list) {
 if (!this.searchQuery) return list;
 const q = this.searchQuery.toLowerCase();
 return list.filter(item => Object.values(item).some(val => String(val).toLowerCase().includes(q)));
 },

 // --- Fungsi Toast ---
 showToastMsg(msg, type='success') {
     Alpine.store('toast').showToast({ message: msg, type: type });
 },

 // --- Fungsi Tabel Pengajuan ---
 openPreviewModal(item) { this.activeProposal = item; this.showPreviewModal = true; },
 openDetailModal(item) { this.activeDetail = item; this.showDetailDataModal = true; },
 verifyProposal(newStatus, noteStr) {
 window.dispatchEvent(new CustomEvent('trigger-confirm', {
 detail: {
 title: 'Konfirmasi Verifikasi', message: `Yakin berikan status: ${newStatus}?`, type: newStatus === 'Ditolak' ? 'danger' : 'success', confirmText: `Ya`,
 onConfirm: () => {
 this.activeProposal.status = newStatus; this.activeProposal = { ...this.activeProposal };
 const idx = this.proposals.findIndex(p => p.id === this.activeProposal.id);
 if(idx !== -1) this.proposals[idx] = this.activeProposal;
 }
 }
 }));
 },
 verifyProposalDirect(item) {
 window.dispatchEvent(new CustomEvent('trigger-confirm', {
 detail: {
 title: 'Terima Proposal', message: `Apakah Anda yakin ingin menerima proposal untuk usulan lokasi ${item.desa}? Data akan diverifikasi secara administratif.`, type: 'success', confirmText: 'Terima Proposal',
 onConfirm: () => {
     fetch(`/master/knmp/calon-lokasi/${item.id}/update-status`, {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json',
             'X-CSRF-TOKEN': '{{ csrf_token() }}'
         },
         body: JSON.stringify({ status_tahapan: 'verif_admin' })
     })
     .then(res => res.json())
     .then(data => {
         if(data.success) {
             this.showToastMsg('Proposal berhasil diverifikasi dan dipindahkan ke tahap Verifikasi Administrasi!');
             setTimeout(() => { window.location.reload(); }, 2000);
         } else {
             this.showToastMsg('Gagal: ' + data.message, 'danger');
         }
     })
     .catch(err => {
         console.error(err);
         this.showToastMsg('Gagal: Terjadi kesalahan sistem saat menghubungi server', 'danger');
     });
 }
 }
 }));
 },

 // --- Fungsi Tabel Verifikasi Administrasi ---
 openChecklistModal(item) { this.activeVerif = JSON.parse(JSON.stringify(item)); this.showChecklistModal = true; },
 updateChecklistProgress() { this.activeVerif.checkedDocs = this.activeVerif.documents.filter(d => d.isValid).length; },
 terbitkanBA() {
 if (this.activeVerif.checkedDocs !== this.activeVerif.totalDocs) return;
 window.dispatchEvent(new CustomEvent('trigger-confirm', {
 detail: {
 title: 'Terbitkan BA', message: 'Lanjutkan ke tahap BA Aktivasi?', type: 'info', confirmText: 'Terbitkan BA',
 onConfirm: () => {
 this.activeVerif.status = 'Selesai (Lanjut BA)';
 const idx = this.verifList.findIndex(p => p.id === this.activeVerif.id);
 if(idx !== -1) this.verifList[idx] = this.activeVerif;
 
 // Tambahkan otomatis ke tabel BA Aktivasi
 this.baList.unshift({
 id: Date.now(), desa: this.activeVerif.desa, kabupaten: this.activeVerif.kabupaten, noBa: '', tglBa: '', status: 'Menunggu Draft'
 });
 this.showChecklistModal = false;
 }
 }
 }));
 },

 // --- Fungsi Tabel BA Aktivasi ---
 generateDraftBA(item) {
 window.dispatchEvent(new CustomEvent('trigger-confirm', {
 detail: {
 title: 'Generate Draft BA', message: 'Sistem akan membuat PDF Draft BA Aktivasi dengan Nomor Surat otomatis. Lanjutkan?', type: 'info', confirmText: 'Ya, Generate',
 onConfirm: () => {
 item.status = 'Menunggu TTD Pemda';
 item.noBa = 'BA.' + Math.floor(Math.random() * 900 + 100) + '/KNMP/2026';
 item.tglBa = this.formatDate(new Date()).split(' ')[0]; // just date
 }
 }
 }));
 },
 openUploadBAModal(item) {
 this.activeBa = item;
 this.uploadBaFile = '';
 this.showUploadBAModal = true;
 },
 submitUploadBA() {
 if(!this.uploadBaFile) return alert('Silakan pilih file PDF Berita Acara!');
 
 window.dispatchEvent(new CustomEvent('trigger-confirm', {
 detail: {
 title: 'Selesaikan BA Aktivasi', message: 'Pastikan dokumen yang diunggah telah memiliki tanda tangan dan stempel resmi.', type: 'success', confirmText: 'Selesai & Simpan',
 onConfirm: () => {
 this.activeBa.status = 'BA Terbit (Selesai)';
 this.showUploadBAModal = false;
 }
 }
 }));
 },

 formatDate(date) {
 const pad = (n) => n.toString().padStart(2, '0');
 return `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()} ${pad(date.getHours())}:${pad(date.getMinutes())}`;
 }
 }));
 });
</script>
@endsection
