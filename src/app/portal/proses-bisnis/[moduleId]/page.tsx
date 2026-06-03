"use client";

import { useState } from "react";
import { modules, getProsesBisnisData } from "@/lib/data";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
  DialogDescription,
} from "@/components/ui/dialog";
import {
  Plus,
  Download,
  ArrowRight,
  Pencil,
  Trash2,
  FileSpreadsheet,
  Search,
} from "lucide-react";

interface FormData {
  nama: string;
  lokasi: string;
  status: string;
  tanggal: string;
  keterangan: string;
}

const emptyForm: FormData = {
  nama: "",
  lokasi: "",
  status: "Proses",
  tanggal: "",
  keterangan: "",
};

export default function ProsesBisnisModulePage({
  params,
}: {
  params: { moduleId: string };
}) {
  const { moduleId } = params;
  const mod = modules.find((m) => m.id === moduleId);
  const moduleName = mod?.label || moduleId;
  const tableData = getProsesBisnisData();

  const [dialogOpen, setDialogOpen] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [formData, setFormData] = useState<FormData>(emptyForm);
  const [searchQuery, setSearchQuery] = useState("");

  const handleOpenAdd = () => {
    setEditingId(null);
    setFormData(emptyForm);
    setDialogOpen(true);
  };

  const handleOpenEdit = (row: (typeof tableData)[0]) => {
    setEditingId(row.id);
    setFormData({
      nama: row.nama,
      lokasi: row.lokasi,
      status: row.status,
      tanggal: row.tanggal,
      keterangan: "",
    });
    setDialogOpen(true);
  };

  const handleSave = () => {
    // In a real app, this would save to API
    setDialogOpen(false);
    setFormData(emptyForm);
  };

  const statusColor = (status: string) => {
    switch (status) {
      case "Selesai":
        return "bg-emerald-50 text-emerald-700 border-emerald-200";
      case "Proses":
        return "bg-blue-50 text-blue-700 border-blue-200";
      case "Menunggu":
        return "bg-amber-50 text-amber-700 border-amber-200";
      default:
        return "bg-gray-50 text-gray-700 border-gray-200";
    }
  };

  const filteredData = tableData.filter(
    (row) =>
      row.nama.toLowerCase().includes(searchQuery.toLowerCase()) ||
      row.lokasi.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="space-y-6 animate-fade-in">
      {/* Page header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div className="flex items-center gap-2 text-sm text-gray-400 mb-1">
            <span>Proses Bisnis</span>
            <ArrowRight className="w-3 h-3" />
            <span className="text-accent font-medium">{moduleName}</span>
          </div>
          <h1 className="text-2xl font-bold text-navy-800">
            Proses Bisnis — {moduleName}
          </h1>
          <p className="text-gray-500 text-sm mt-1">
            Kelola data proses bisnis modul {moduleName}
          </p>
        </div>
      </div>

      {/* Data table card */}
      <Card className="border border-gray-100 shadow-xl shadow-gray-200/40 hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-300">
        <CardHeader className="pb-4">
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <CardTitle className="text-base font-semibold text-navy-800">
                Data {moduleName}
              </CardTitle>
              <p className="text-xs text-gray-400 mt-0.5">
                {filteredData.length} dari {tableData.length} entri ditampilkan
              </p>
            </div>
            <div className="flex items-center gap-2">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <Input
                  placeholder="Cari data..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className="pl-9 h-9 w-48 bg-gray-50 border-gray-200 rounded-xl text-sm"
                />
              </div>
              <Button
                variant="outline"
                size="sm"
                className="rounded-xl border-gray-200 text-gray-600 hover:bg-gray-50"
              >
                <Download className="w-4 h-4 mr-1.5" />
                Export
              </Button>
              <Button
                size="sm"
                onClick={handleOpenAdd}
                className="rounded-xl bg-gradient-to-r from-navy-600 to-accent hover:from-navy-700 hover:to-accent-dark text-white shadow-md shadow-accent/15"
              >
                <Plus className="w-4 h-4 mr-1.5" />
                Tambah Data
              </Button>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div className="overflow-x-auto">
            <Table>
              <TableHeader>
                <TableRow className="border-gray-100 hover:bg-transparent">
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                    No
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Nama
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Lokasi
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Status
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Tanggal
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                    Aksi
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {filteredData.map((row, index) => (
                  <TableRow
                    key={row.id}
                    className="border-gray-50 hover:bg-gray-50/50 transition-colors"
                  >
                    <TableCell className="text-sm text-gray-400 font-medium">
                      {index + 1}
                    </TableCell>
                    <TableCell className="font-medium text-sm text-navy-800 max-w-[250px]">
                      <span className="line-clamp-1">{row.nama}</span>
                    </TableCell>
                    <TableCell className="text-sm text-gray-600">
                      {row.lokasi}
                    </TableCell>
                    <TableCell>
                      <span
                        className={`inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium border ${statusColor(row.status)}`}
                      >
                        {row.status}
                      </span>
                    </TableCell>
                    <TableCell className="text-sm text-gray-500">
                      {new Date(row.tanggal).toLocaleDateString("id-ID", {
                        day: "numeric",
                        month: "short",
                        year: "numeric",
                      })}
                    </TableCell>
                    <TableCell className="text-right">
                      <div className="flex items-center justify-end gap-1">
                        <button
                          onClick={() => handleOpenEdit(row)}
                          className="p-1.5 rounded-lg text-gray-400 hover:text-accent hover:bg-accent/5 transition-colors"
                        >
                          <Pencil className="w-3.5 h-3.5" />
                        </button>
                        <button className="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                          <Trash2 className="w-3.5 h-3.5" />
                        </button>
                      </div>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
        </CardContent>
      </Card>

      {/* Add/Edit Modal */}
      <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
        <DialogContent className="sm:max-w-[520px] rounded-2xl border-0 shadow-2xl">
          <DialogHeader>
            <DialogTitle className="text-lg font-bold text-navy-800">
              {editingId ? "Edit Data" : "Tambah Data Baru"}
            </DialogTitle>
            <DialogDescription className="text-sm text-gray-500">
              {editingId
                ? "Perbarui informasi data yang dipilih"
                : "Isi formulir berikut untuk menambahkan data baru"}
            </DialogDescription>
          </DialogHeader>

          <div className="space-y-4 py-2">
            <div className="space-y-2">
              <Label className="text-sm font-medium text-gray-700">
                Nama Kegiatan
              </Label>
              <Input
                value={formData.nama}
                onChange={(e) =>
                  setFormData({ ...formData, nama: e.target.value })
                }
                placeholder="Masukkan nama kegiatan"
                className="rounded-xl border-gray-200 bg-gray-50/50"
              />
            </div>
            <div className="space-y-2">
              <Label className="text-sm font-medium text-gray-700">
                Lokasi
              </Label>
              <Input
                value={formData.lokasi}
                onChange={(e) =>
                  setFormData({ ...formData, lokasi: e.target.value })
                }
                placeholder="Masukkan lokasi"
                className="rounded-xl border-gray-200 bg-gray-50/50"
              />
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-sm font-medium text-gray-700">
                  Status
                </Label>
                <select
                  value={formData.status}
                  onChange={(e) =>
                    setFormData({ ...formData, status: e.target.value })
                  }
                  className="w-full h-10 px-3 rounded-xl border border-gray-200 bg-gray-50/50 text-sm focus:border-accent focus:ring-1 focus:ring-accent/20 outline-none"
                >
                  <option value="Proses">Proses</option>
                  <option value="Selesai">Selesai</option>
                  <option value="Menunggu">Menunggu</option>
                </select>
              </div>
              <div className="space-y-2">
                <Label className="text-sm font-medium text-gray-700">
                  Tanggal
                </Label>
                <Input
                  type="date"
                  value={formData.tanggal}
                  onChange={(e) =>
                    setFormData({ ...formData, tanggal: e.target.value })
                  }
                  className="rounded-xl border-gray-200 bg-gray-50/50"
                />
              </div>
            </div>
            <div className="space-y-2">
              <Label className="text-sm font-medium text-gray-700">
                Keterangan
              </Label>
              <Input
                value={formData.keterangan}
                onChange={(e) =>
                  setFormData({ ...formData, keterangan: e.target.value })
                }
                placeholder="Keterangan tambahan (opsional)"
                className="rounded-xl border-gray-200 bg-gray-50/50"
              />
            </div>
          </div>

          <DialogFooter className="gap-2 sm:gap-2">
            <Button
              variant="outline"
              onClick={() => setDialogOpen(false)}
              className="rounded-xl border-gray-200"
            >
              Batal
            </Button>
            <Button
              onClick={handleSave}
              className="rounded-xl bg-gradient-to-r from-navy-600 to-accent text-white shadow-md shadow-accent/15"
            >
              <FileSpreadsheet className="w-4 h-4 mr-1.5" />
              {editingId ? "Simpan Perubahan" : "Tambah Data"}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
}
