"use client";


import { modules, getKPIData, getEvaluasiComparison } from "@/lib/data";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  ArrowRight,
  TrendingUp,
  Target,
  Award,
  CheckCircle2,
  AlertCircle,
} from "lucide-react";

export default function EvaluasiModulePage({
  params,
}: {
  params: { moduleId: string };
}) {
  const { moduleId } = params;
  const mod = modules.find((m) => m.id === moduleId);
  const moduleName = mod?.label || moduleId;
  const kpiData = getKPIData();
  const comparisonData = getEvaluasiComparison();

  const getStatusConfig = (status: string) => {
    switch (status) {
      case "Sangat Baik":
        return {
          color: "text-emerald-700 bg-emerald-50 border-emerald-200",
          icon: CheckCircle2,
          progressColor: "bg-emerald-500",
        };
      case "Baik":
        return {
          color: "text-blue-700 bg-blue-50 border-blue-200",
          icon: TrendingUp,
          progressColor: "bg-blue-500",
        };
      case "Perlu Peningkatan":
        return {
          color: "text-amber-700 bg-amber-50 border-amber-200",
          icon: AlertCircle,
          progressColor: "bg-amber-500",
        };
      default:
        return {
          color: "text-gray-700 bg-gray-50 border-gray-200",
          icon: Target,
          progressColor: "bg-gray-500",
        };
    }
  };

  const getCapaianColor = (capaian: number) => {
    if (capaian >= 95) return "text-emerald-600 bg-emerald-50";
    if (capaian >= 90) return "text-blue-600 bg-blue-50";
    if (capaian >= 85) return "text-amber-600 bg-amber-50";
    return "text-red-600 bg-red-50";
  };

  return (
    <div className="space-y-6 animate-fade-in">
      {/* Page header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div className="flex items-center gap-2 text-sm text-gray-400 mb-1">
            <span>Evaluasi</span>
            <ArrowRight className="w-3 h-3" />
            <span className="text-accent font-medium">{moduleName}</span>
          </div>
          <h1 className="text-2xl font-bold text-navy-800">
            Evaluasi — {moduleName}
          </h1>
          <p className="text-gray-500 text-sm mt-1">
            Evaluasi kinerja dan capaian modul {moduleName}
          </p>
        </div>
        <Badge variant="outline" className="bg-accent/5 text-accent border-accent/20 px-3 py-1 self-start">
          <Award className="w-3 h-3 mr-1" />
          Periode 2024
        </Badge>
      </div>

      {/* KPI Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
        {kpiData.map((kpi, index) => {
          const config = getStatusConfig(kpi.status);
          const StatusIcon = config.icon;
          const percentage = Math.round((kpi.nilai / kpi.target) * 100);

          return (
            <Card
              key={kpi.id}
              className="border-0 shadow-md shadow-gray-100/80 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 animate-count-up overflow-hidden"
              style={{ animationDelay: `${index * 150}ms` }}
            >
              <CardContent className="p-6">
                <div className="flex items-start justify-between mb-4">
                  <div className="flex-1 min-w-0 mr-3">
                    <h3 className="text-sm font-semibold text-navy-800 line-clamp-1">
                      {kpi.nama}
                    </h3>
                    <p className="text-xs text-gray-400 mt-1 line-clamp-2">
                      {kpi.deskripsi}
                    </p>
                  </div>
                  <div
                    className={`flex-shrink-0 px-2.5 py-1 rounded-lg text-xs font-medium border flex items-center gap-1 ${config.color}`}
                  >
                    <StatusIcon className="w-3 h-3" />
                    <span className="hidden sm:inline">{kpi.status}</span>
                  </div>
                </div>

                <div className="space-y-3">
                  <div className="flex items-end justify-between">
                    <div>
                      <span className="text-3xl font-bold text-navy-800">
                        {kpi.nilai}
                      </span>
                      <span className="text-lg text-gray-400 ml-0.5">
                        {kpi.satuan}
                      </span>
                    </div>
                    <div className="text-right">
                      <p className="text-xs text-gray-400">Target</p>
                      <p className="text-sm font-semibold text-gray-600">
                        {kpi.target}
                        {kpi.satuan}
                      </p>
                    </div>
                  </div>

                  <div className="space-y-1.5">
                    <div className="flex justify-between text-xs">
                      <span className="text-gray-400">Capaian</span>
                      <span className="font-medium text-navy-800">
                        {percentage}%
                      </span>
                    </div>
                    <div className="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                      <div
                        className={`h-full rounded-full transition-all duration-1000 ease-out ${config.progressColor}`}
                        style={{ width: `${Math.min(percentage, 100)}%` }}
                      />
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          );
        })}
      </div>

      {/* Comparison table */}
      <Card className="border border-gray-100 shadow-xl shadow-gray-200/40 hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-300">
        <CardHeader className="pb-3">
          <div className="flex items-center justify-between">
            <div>
              <CardTitle className="text-base font-semibold text-navy-800">
                Perbandingan Target vs Realisasi vs Capaian
              </CardTitle>
              <p className="text-xs text-gray-400 mt-0.5">
                Data evaluasi per indikator kinerja
              </p>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div className="overflow-x-auto">
            <Table>
              <TableHeader>
                <TableRow className="border-gray-100 hover:bg-transparent">
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Indikator
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                    Target
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                    Realisasi
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                    Capaian (%)
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">
                    Progres
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {comparisonData.map((row, index) => (
                  <TableRow
                    key={index}
                    className="border-gray-50 hover:bg-gray-50/50 transition-colors"
                  >
                    <TableCell className="font-medium text-sm text-navy-800">
                      {row.indikator}
                    </TableCell>
                    <TableCell className="text-sm text-gray-600 text-right">
                      {row.target}
                    </TableCell>
                    <TableCell className="text-sm text-gray-600 text-right">
                      {row.realisasi}
                    </TableCell>
                    <TableCell className="text-right">
                      <span
                        className={`inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold ${getCapaianColor(row.capaian)}`}
                      >
                        {row.capaian.toFixed(2)}%
                      </span>
                    </TableCell>
                    <TableCell>
                      <div className="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div
                          className="h-full bg-gradient-to-r from-accent to-blue-400 rounded-full transition-all duration-700"
                          style={{
                            width: `${Math.min(row.capaian, 100)}%`,
                          }}
                        />
                      </div>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
        </CardContent>
      </Card>

      {/* Summary */}
      <Card className="border border-navy-700 shadow-xl shadow-navy-900/30 bg-gradient-to-br from-navy-800 to-navy-600 text-white hover:shadow-2xl hover:-translate-y-0.5 transition-all duration-300">
        <CardContent className="p-6">
          <div className="flex items-start gap-4">
            <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
              <Target className="w-5 h-5 text-accent-light" />
            </div>
            <div>
              <h3 className="font-semibold text-white mb-2">
                Ringkasan Evaluasi — {moduleName}
              </h3>
              <p className="text-blue-200/70 text-sm leading-relaxed">
                Berdasarkan hasil evaluasi kinerja modul {moduleName}, capaian
                keseluruhan menunjukkan tren positif dengan rata-rata pencapaian
                di atas 89%. Indikator Konsumsi Ikan per Kapita mencatatkan
                capaian tertinggi sebesar 95,68%, sementara Produksi Perikanan
                Budidaya masih memerlukan perhatian khusus dengan capaian
                88,21%. Diperlukan strategi akselerasi pada kuartal berikutnya
                untuk memastikan seluruh target tercapai sesuai Renstra KKP
                2020-2024. Tim teknis disarankan melakukan evaluasi mendalam
                pada indikator yang belum mencapai 90% dan menyusun rencana
                tindak lanjut yang terukur.
              </p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
