"use client";


import { modules, getDashboardStats, getMonthlyTrendData, getComparisonData, getRecentActivity } from "@/lib/data";
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
  Database,
  TrendingUp,
  Target,
  PieChart,
  ArrowUpRight,
  ArrowRight,
  Activity,
} from "lucide-react";
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  Legend,
  Area,
  AreaChart,
} from "recharts";

const iconMap = {
  Database,
  TrendingUp,
  Target,
  PieChart,
};

export default function DashboardModulePage({
  params,
}: {
  params: { moduleId: string };
}) {
  const { moduleId } = params;
  const mod = modules.find((m) => m.id === moduleId);
  const moduleName = mod?.label || moduleId;
  const stats = getDashboardStats();
  const monthlyData = getMonthlyTrendData();
  const comparisonData = getComparisonData();
  const recentActivity = getRecentActivity();

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

  return (
    <div className="space-y-6 animate-fade-in">
      {/* Page header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div className="flex items-center gap-2 text-sm text-gray-400 mb-1">
            <span>Dashboard</span>
            <ArrowRight className="w-3 h-3" />
            <span className="text-accent font-medium">{moduleName}</span>
          </div>
          <h1 className="text-2xl font-bold text-navy-800">
            Dashboard {moduleName}
          </h1>
          <p className="text-gray-500 text-sm mt-1">
            Ringkasan data dan statistik modul {moduleName}
          </p>
        </div>
        <div className="flex items-center gap-2">
          <Badge variant="outline" className="bg-accent/5 text-accent border-accent/20 px-3 py-1">
            <Activity className="w-3 h-3 mr-1" />
            Live Data
          </Badge>
        </div>
      </div>

      {/* Stat cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {stats.map((stat, index) => {
          const Icon = iconMap[stat.icon as keyof typeof iconMap];
          return (
            <Card
              key={stat.title}
              className="border-0 shadow-md shadow-gray-100/80 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 animate-count-up"
              style={{ animationDelay: `${index * 100}ms` }}
            >
              <CardContent className="p-5">
                <div className="flex items-start justify-between">
                  <div className="space-y-2">
                    <p className="text-sm text-gray-500 font-medium">{stat.title}</p>
                    <p className="text-2xl font-bold text-navy-800">{stat.value}</p>
                    <div className="flex items-center gap-1.5">
                      {stat.trend === "up" && (
                        <span className="flex items-center gap-0.5 text-xs font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md">
                          <ArrowUpRight className="w-3 h-3" />
                          {stat.change}
                        </span>
                      )}
                      {stat.trend === "neutral" && (
                        <span className="text-xs font-medium text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded-md">
                          {stat.change}
                        </span>
                      )}
                    </div>
                  </div>
                  <div className="w-11 h-11 rounded-xl bg-gradient-to-br from-accent/10 to-navy-100/50 flex items-center justify-center">
                    <Icon className="w-5 h-5 text-accent" />
                  </div>
                </div>
              </CardContent>
            </Card>
          );
        })}
      </div>

      {/* Charts */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Line chart - Monthly trend */}
        <Card className="border border-gray-100 shadow-xl shadow-gray-200/40 hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-300">
          <CardHeader className="pb-2">
            <CardTitle className="text-base font-semibold text-navy-800">
              Tren Bulanan
            </CardTitle>
            <p className="text-xs text-gray-400">Realisasi vs Target (12 bulan)</p>
          </CardHeader>
          <CardContent>
            <div className="h-[300px]">
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={monthlyData}>
                  <defs>
                    <linearGradient id="colorRealisasi" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#2563eb" stopOpacity={0.15} />
                      <stop offset="95%" stopColor="#2563eb" stopOpacity={0} />
                    </linearGradient>
                    <linearGradient id="colorTarget" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#16a34a" stopOpacity={0.1} />
                      <stop offset="95%" stopColor="#16a34a" stopOpacity={0} />
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" stroke="#f1f5f9" />
                  <XAxis
                    dataKey="bulan"
                    tick={{ fill: "#94a3b8", fontSize: 12 }}
                    axisLine={{ stroke: "#e2e8f0" }}
                  />
                  <YAxis
                    tick={{ fill: "#94a3b8", fontSize: 12 }}
                    axisLine={{ stroke: "#e2e8f0" }}
                  />
                  <Tooltip
                    contentStyle={{
                      background: "white",
                      border: "none",
                      borderRadius: "12px",
                      boxShadow: "0 4px 20px rgba(0,0,0,0.08)",
                      padding: "12px 16px",
                    }}
                  />
                  <Legend
                    wrapperStyle={{ fontSize: "12px", paddingTop: "8px" }}
                  />
                  <Area
                    type="monotone"
                    dataKey="realisasi"
                    stroke="#2563eb"
                    strokeWidth={2.5}
                    fill="url(#colorRealisasi)"
                    name="Realisasi"
                  />
                  <Area
                    type="monotone"
                    dataKey="target"
                    stroke="#16a34a"
                    strokeWidth={2}
                    strokeDasharray="5 5"
                    fill="url(#colorTarget)"
                    name="Target"
                  />
                </AreaChart>
              </ResponsiveContainer>
            </div>
          </CardContent>
        </Card>

        {/* Bar chart - Comparison */}
        <Card className="border border-gray-100 shadow-xl shadow-gray-200/40 hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-300">
          <CardHeader className="pb-2">
            <CardTitle className="text-base font-semibold text-navy-800">
              Perbandingan Capaian
            </CardTitle>
            <p className="text-xs text-gray-400">Target vs Realisasi per kategori</p>
          </CardHeader>
          <CardContent>
            <div className="h-[300px]">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={comparisonData} barGap={4}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#f1f5f9" />
                  <XAxis
                    dataKey="kategori"
                    tick={{ fill: "#94a3b8", fontSize: 11 }}
                    axisLine={{ stroke: "#e2e8f0" }}
                  />
                  <YAxis
                    tick={{ fill: "#94a3b8", fontSize: 12 }}
                    axisLine={{ stroke: "#e2e8f0" }}
                  />
                  <Tooltip
                    contentStyle={{
                      background: "white",
                      border: "none",
                      borderRadius: "12px",
                      boxShadow: "0 4px 20px rgba(0,0,0,0.08)",
                      padding: "12px 16px",
                    }}
                  />
                  <Legend
                    wrapperStyle={{ fontSize: "12px", paddingTop: "8px" }}
                  />
                  <Bar
                    dataKey="target"
                    fill="#1e3a5f"
                    radius={[6, 6, 0, 0]}
                    name="Target"
                  />
                  <Bar
                    dataKey="realisasi"
                    fill="#2563eb"
                    radius={[6, 6, 0, 0]}
                    name="Realisasi"
                  />
                </BarChart>
              </ResponsiveContainer>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Recent activity table */}
      <Card className="border border-gray-100 shadow-xl shadow-gray-200/40 hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-300">
        <CardHeader className="pb-3">
          <div className="flex items-center justify-between">
            <div>
              <CardTitle className="text-base font-semibold text-navy-800">
                Aktivitas Terkini
              </CardTitle>
              <p className="text-xs text-gray-400 mt-0.5">
                5 aktivitas terbaru pada modul {moduleName}
              </p>
            </div>
            <Badge variant="outline" className="text-xs">
              {recentActivity.length} entri
            </Badge>
          </div>
        </CardHeader>
        <CardContent>
          <div className="overflow-x-auto">
            <Table>
              <TableHeader>
                <TableRow className="border-gray-100 hover:bg-transparent">
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Kegiatan
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Pengguna
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Waktu
                  </TableHead>
                  <TableHead className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Status
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {recentActivity.map((item) => (
                  <TableRow
                    key={item.id}
                    className="border-gray-50 hover:bg-gray-50/50"
                  >
                    <TableCell className="font-medium text-sm text-navy-800">
                      {item.kegiatan}
                    </TableCell>
                    <TableCell className="text-sm text-gray-600">
                      {item.pengguna}
                    </TableCell>
                    <TableCell className="text-sm text-gray-400">
                      {item.waktu}
                    </TableCell>
                    <TableCell>
                      <span
                        className={`inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium border ${statusColor(item.status)}`}
                      >
                        {item.status}
                      </span>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
